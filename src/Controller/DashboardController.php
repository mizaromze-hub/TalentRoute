<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;

class DashboardController extends AppController
{
    /**
     * Generate secure student verification token.
     */
    private function generateStudentToken(
        int $studentId,
        string $matrixNumber
    ): string {
        $salt = (string)Configure::read('Security.salt');

        return hash_hmac(
            'sha256',
            $studentId . '|' . $matrixNumber,
            $salt
        );
    }

    /**
     * Dashboard for Admin, Student and Company.
     */
    public function index()
    {
        $session = $this->request->getSession();
        $userAttr = $session->read('Auth.User');

        if (!is_array($userAttr)) {
            return $this->redirect([
                'controller' => 'Users',
                'action' => 'login',
            ]);
        }

        $conn = ConnectionManager::get('default');

        $role = strtolower(
            trim((string)($userAttr['role'] ?? ''))
        );

        $studentProfile = null;
        $companyProfile = null;

        $applications = [];
        $leaves = [];

        $stats = [];
        $statusChartData = [];
        $monthlyChartData = [];
        $facultyChartData = [];
        $leaveChartData = [];

        $approvalRate = 0;
        $studentQrUrl = null;
        $activeInternship = null;

        /*
         * ==========================================================
         * ADMIN DASHBOARD
         * ==========================================================
         */
        if ($role === 'admin') {
            $stats = [
                'students' => (int)$conn
                    ->execute(
                        'SELECT COUNT(*) AS total
                         FROM students'
                    )
                    ->fetch('assoc')['total'],

                'companies' => (int)$conn
                    ->execute(
                        'SELECT COUNT(*) AS total
                         FROM companies'
                    )
                    ->fetch('assoc')['total'],

                'applications' => (int)$conn
                    ->execute(
                        'SELECT COUNT(*) AS total
                         FROM applications'
                    )
                    ->fetch('assoc')['total'],

                'leaves' => (int)$conn
                    ->execute(
                        'SELECT COUNT(*) AS total
                         FROM leaves'
                    )
                    ->fetch('assoc')['total'],
            ];

            $applications = $conn
                ->execute(
                    'SELECT
                        a.*,
                        s.name,
                        s.matrix_number,
                        c.company_name AS linked_company
                     FROM applications a
                     INNER JOIN students s
                        ON s.id = a.student_id
                     LEFT JOIN companies c
                        ON c.id = a.company_id
                     ORDER BY a.id DESC
                     LIMIT 8'
                )
                ->fetchAll('assoc');

            $statusChartData = $conn
                ->execute(
                    'SELECT
                        LOWER(status) AS status,
                        COUNT(*) AS total
                     FROM applications
                     GROUP BY LOWER(status)
                     ORDER BY total DESC'
                )
                ->fetchAll('assoc');

            $monthlyChartData = $conn
                ->execute(
                    "SELECT
                        DATE_FORMAT(
                            COALESCE(
                                apply_date,
                                DATE(created)
                            ),
                            '%Y-%m'
                        ) AS month,
                        COUNT(*) AS total
                     FROM applications
                     GROUP BY month
                     ORDER BY month"
                )
                ->fetchAll('assoc');

            $facultyChartData = $conn
                ->execute(
                    "SELECT
                        faculty,
                        COUNT(*) AS total
                     FROM students
                     WHERE faculty IS NOT NULL
                     AND faculty <> ''
                     GROUP BY faculty
                     ORDER BY total DESC"
                )
                ->fetchAll('assoc');

            $approvedCount = (int)$conn
                ->execute(
                    "SELECT COUNT(*) AS total
                     FROM applications
                     WHERE LOWER(status)
                     IN ('approved', 'offered')"
                )
                ->fetch('assoc')['total'];

            $approvalRate = $stats['applications'] > 0
                ? round(
                    ($approvedCount / $stats['applications']) * 100,
                    1
                )
                : 0;
        }

        /*
         * ==========================================================
         * STUDENT DASHBOARD
         * ==========================================================
         */
        elseif ($role === 'student') {
            $studentProfile = $conn
                ->execute(
                    'SELECT
                        s.*,
                        u.email
                     FROM students s
                     INNER JOIN users u
                        ON u.id = s.user_id
                     WHERE s.user_id = ?
                     LIMIT 1',
                    [$userAttr['id']]
                )
                ->fetch('assoc');

            if ($studentProfile) {
                $studentId = (int)$studentProfile['id'];

                $applications = $conn
                    ->execute(
                        'SELECT
                            a.*,
                            COALESCE(
                                c.company_name,
                                a.company_name
                            ) AS display_company
                         FROM applications a
                         LEFT JOIN companies c
                            ON c.id = a.company_id
                         WHERE a.student_id = ?
                         ORDER BY a.id DESC',
                        [$studentId]
                    )
                    ->fetchAll('assoc');

                $leaves = $conn
                    ->execute(
                        'SELECT *
                         FROM leaves
                         WHERE student_id = ?
                         ORDER BY id DESC
                         LIMIT 8',
                        [$studentId]
                    )
                    ->fetchAll('assoc');

                $statusChartData = $conn
                    ->execute(
                        'SELECT
                            LOWER(status) AS status,
                            COUNT(*) AS total
                         FROM applications
                         WHERE student_id = ?
                         GROUP BY LOWER(status)
                         ORDER BY total DESC',
                        [$studentId]
                    )
                    ->fetchAll('assoc');

                $monthlyChartData = $conn
                    ->execute(
                        "SELECT
                            DATE_FORMAT(
                                COALESCE(
                                    apply_date,
                                    DATE(created)
                                ),
                                '%Y-%m'
                            ) AS month,
                            COUNT(*) AS total
                         FROM applications
                         WHERE student_id = ?
                         GROUP BY month
                         ORDER BY month",
                        [$studentId]
                    )
                    ->fetchAll('assoc');

                $leaveChartData = $conn
                    ->execute(
                        'SELECT
                            LOWER(status) AS status,
                            COUNT(*) AS total
                         FROM leaves
                         WHERE student_id = ?
                         GROUP BY LOWER(status)
                         ORDER BY total DESC',
                        [$studentId]
                    )
                    ->fetchAll('assoc');

                $stats = [
                    'applications' => count($applications),
                    'pending' => 0,
                    'approved' => 0,
                    'rejected' => 0,
                    'leaves' => count($leaves),
                ];

                foreach ($applications as $application) {
                    $status = strtolower(
                        trim((string)$application['status'])
                    );

                    if (
                        in_array(
                            $status,
                            [
                                'applied',
                                'pending',
                                'reviewing',
                                'interview',
                            ],
                            true
                        )
                    ) {
                        $stats['pending']++;
                    }

                    if (
                        in_array(
                            $status,
                            ['approved', 'offered'],
                            true
                        )
                    ) {
                        $stats['approved']++;
                    }

                    if ($status === 'rejected') {
                        $stats['rejected']++;
                    }
                }

                $approvalRate = $stats['applications'] > 0
                    ? round(
                        (
                            $stats['approved']
                            / $stats['applications']
                        ) * 100,
                        1
                    )
                    : 0;

                $activeInternship = $conn
                    ->execute(
                        "SELECT
                            a.*,
                            COALESCE(
                                c.company_name,
                                a.company_name
                            ) AS display_company
                         FROM applications a
                         LEFT JOIN companies c
                            ON c.id = a.company_id
                         WHERE a.student_id = ?
                         AND LOWER(a.status)
                            IN ('approved', 'offered')
                         ORDER BY a.id DESC
                         LIMIT 1",
                        [$studentId]
                    )
                    ->fetch('assoc');

                $matrixNumber = (string)(
                    $studentProfile['matrix_number'] ?? ''
                );

                $token = $this->generateStudentToken(
                    $studentId,
                    $matrixNumber
                );

                $studentQrUrl = $this->getRequest()
                    ->getAttribute('webroot')
                    . 'students/verify/'
                    . $studentId
                    . '/'
                    . $token;

                $studentQrUrl = $this->getRequest()
                    ->getUri()
                    ->withPath($studentQrUrl)
                    ->withQuery('')
                    ->withFragment('')
                    ->__toString();
            }
        }

        /*
         * ==========================================================
         * COMPANY DASHBOARD
         * ==========================================================
         */
        elseif ($role === 'company') {
            $companyProfile = $conn
                ->execute(
                    'SELECT
                        c.*,
                        u.email
                     FROM companies c
                     INNER JOIN users u
                        ON u.id = c.user_id
                     WHERE c.user_id = ?
                     LIMIT 1',
                    [$userAttr['id']]
                )
                ->fetch('assoc');

            if ($companyProfile) {
                $companyId = (int)$companyProfile['id'];
                $companyName = (string)$companyProfile['company_name'];

                $applications = $conn
                    ->execute(
                        'SELECT
                            a.*,
                            s.name,
                            s.matrix_number,
                            s.faculty,
                            s.course,
                            s.semester,
                            s.resume_path
                         FROM applications a
                         INNER JOIN students s
                            ON s.id = a.student_id
                         WHERE (
                            a.company_id = ?
                            OR (
                                a.company_id IS NULL
                                AND a.company_name = ?
                            )
                         )
                         ORDER BY a.id DESC',
                        [
                            $companyId,
                            $companyName,
                        ]
                    )
                    ->fetchAll('assoc');

                $statusChartData = $conn
                    ->execute(
                        'SELECT
                            LOWER(status) AS status,
                            COUNT(*) AS total
                         FROM applications
                         WHERE (
                            company_id = ?
                            OR (
                                company_id IS NULL
                                AND company_name = ?
                            )
                         )
                         GROUP BY LOWER(status)
                         ORDER BY total DESC',
                        [
                            $companyId,
                            $companyName,
                        ]
                    )
                    ->fetchAll('assoc');

                $monthlyChartData = $conn
                    ->execute(
                        "SELECT
                            DATE_FORMAT(
                                COALESCE(
                                    apply_date,
                                    DATE(created)
                                ),
                                '%Y-%m'
                            ) AS month,
                            COUNT(*) AS total
                         FROM applications
                         WHERE (
                            company_id = ?
                            OR (
                                company_id IS NULL
                                AND company_name = ?
                            )
                         )
                         GROUP BY month
                         ORDER BY month",
                        [
                            $companyId,
                            $companyName,
                        ]
                    )
                    ->fetchAll('assoc');

                $stats = [
                    'applications' => count($applications),
                    'pending' => 0,
                    'approved' => 0,
                    'rejected' => 0,
                    'active_interns' => 0,
                ];

                foreach ($applications as $application) {
                    $status = strtolower(
                        trim((string)$application['status'])
                    );

                    if (
                        in_array(
                            $status,
                            [
                                'applied',
                                'pending',
                                'reviewing',
                                'interview',
                            ],
                            true
                        )
                    ) {
                        $stats['pending']++;
                    }

                    if (
                        in_array(
                            $status,
                            ['approved', 'offered'],
                            true
                        )
                    ) {
                        $stats['approved']++;

                        $today = date('Y-m-d');
                        $startDate = $application['start_date'] ?? null;
                        $endDate = $application['end_date'] ?? null;

                        if (
                            $startDate
                            && $endDate
                            && $today >= $startDate
                            && $today <= $endDate
                        ) {
                            $stats['active_interns']++;
                        }
                    }

                    if ($status === 'rejected') {
                        $stats['rejected']++;
                    }
                }

                $approvalRate = $stats['applications'] > 0
                    ? round(
                        (
                            $stats['approved']
                            / $stats['applications']
                        ) * 100,
                        1
                    )
                    : 0;
            }
        } else {
            $this->Flash->error(
                'You are not authorised to access the dashboard.'
            );

            return $this->redirect([
                'controller' => 'Users',
                'action' => 'logout',
            ]);
        }

        $this->set(compact(
            'userAttr',
            'role',
            'studentProfile',
            'companyProfile',
            'applications',
            'leaves',
            'stats',
            'statusChartData',
            'monthlyChartData',
            'facultyChartData',
            'leaveChartData',
            'approvalRate',
            'studentQrUrl',
            'activeInternship'
        ));
    }
}