<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Datasource\ConnectionManager;

class LeavesController extends AppController
{
    /**
     * Return currently authenticated user.
     */
    private function currentUser(): ?array
    {
        $user = $this->request
            ->getSession()
            ->read('Auth.User');

        return is_array($user) ? $user : null;
    }

    /**
     * Return pending-style status list.
     */
    private function pendingStatuses(): array
    {
        return [
            'pending',
            'applied',
            'reviewing',
        ];
    }

    /**
     * Display leave requests according to role.
     */
    public function index()
    {
        $user = $this->currentUser();

        if (!$user) {
            return $this->redirect([
                'controller' => 'Users',
                'action' => 'login',
            ]);
        }

        $connection = ConnectionManager::get('default');

        $role = strtolower(
            trim((string)($user['role'] ?? ''))
        );

        $sql = '
            SELECT
                l.*,
                s.name,
                s.matrix_number,
                s.faculty,
                s.course,
                s.semester,
                a.id AS application_id,
                a.company_id,
                a.total_leave_allowed,
                a.start_date AS internship_start,
                a.end_date AS internship_end,
                COALESCE(
                    c.company_name,
                    a.company_name
                ) AS display_company
            FROM leaves l
            INNER JOIN students s
                ON s.id = l.student_id
            LEFT JOIN applications a
                ON a.id = (
                    SELECT a2.id
                    FROM applications a2
                    WHERE a2.student_id = l.student_id
                    AND LOWER(a2.status)
                        IN (
                            "approved",
                            "offered",
                            "completed"
                        )
                    ORDER BY a2.id DESC
                    LIMIT 1
                )
            LEFT JOIN companies c
                ON c.id = a.company_id
        ';

        $params = [];

        if ($role === 'student') {
            $student = $connection
                ->execute(
                    'SELECT id
                     FROM students
                     WHERE user_id = ?
                     LIMIT 1',
                    [$user['id']]
                )
                ->fetch('assoc');

            if (!$student) {
                $this->Flash->error(
                    'Student profile was not found.'
                );

                return $this->redirect([
                    'controller' => 'Dashboard',
                    'action' => 'index',
                ]);
            }

            $sql .= ' WHERE l.student_id = ?';
            $params[] = $student['id'];
        } elseif ($role === 'company') {
            $company = $connection
                ->execute(
                    'SELECT id, company_name
                     FROM companies
                     WHERE user_id = ?
                     LIMIT 1',
                    [$user['id']]
                )
                ->fetch('assoc');

            if (!$company) {
                $this->Flash->error(
                    'Company profile was not found.'
                );

                return $this->redirect([
                    'controller' => 'Dashboard',
                    'action' => 'index',
                ]);
            }

            $sql .= '
                WHERE (
                    a.company_id = ?
                    OR (
                        a.company_id IS NULL
                        AND a.company_name = ?
                    )
                )
            ';

            $params[] = $company['id'];
            $params[] = $company['company_name'];
        } elseif ($role !== 'admin') {
            $this->Flash->error(
                'You are not authorised to access leave requests.'
            );

            return $this->redirect([
                'controller' => 'Dashboard',
                'action' => 'index',
            ]);
        }

        $sql .= ' ORDER BY l.id DESC';

        $leaves = $connection
            ->execute($sql, $params)
            ->fetchAll('assoc');

        $stats = [
            'total' => count($leaves),
            'pending' => 0,
            'approved' => 0,
            'rejected' => 0,
        ];

        foreach ($leaves as $leave) {
            $status = strtolower(
                trim((string)($leave['status'] ?? 'pending'))
            );

            if (
                in_array(
                    $status,
                    $this->pendingStatuses(),
                    true
                )
            ) {
                $stats['pending']++;
            } elseif ($status === 'approved') {
                $stats['approved']++;
            } elseif ($status === 'rejected') {
                $stats['rejected']++;
            }
        }

        $this->set(compact(
            'leaves',
            'user',
            'role',
            'stats'
        ));
    }

    /**
     * Student submits leave request.
     */
    public function add()
    {
        $user = $this->currentUser();

        if (
            !$user
            || strtolower((string)($user['role'] ?? ''))
                !== 'student'
        ) {
            $this->Flash->error(
                'Only students can submit a leave request.'
            );

            return $this->redirect([
                'action' => 'index',
            ]);
        }

        $connection = ConnectionManager::get('default');

        $student = $connection
            ->execute(
                'SELECT *
                 FROM students
                 WHERE user_id = ?
                 LIMIT 1',
                [$user['id']]
            )
            ->fetch('assoc');

        if (!$student) {
            $this->Flash->error(
                'Student profile was not found.'
            );

            return $this->redirect([
                'controller' => 'Dashboard',
                'action' => 'index',
            ]);
        }

        $application = $connection
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
                 AND LOWER(a.status)
                    IN ("approved", "offered")
                 ORDER BY a.id DESC
                 LIMIT 1',
                [$student['id']]
            )
            ->fetch('assoc');

        if (!$application) {
            $this->Flash->error(
                'You can only apply for leave after your internship application has been approved.'
            );

            return $this->redirect([
                'controller' => 'Applications',
                'action' => 'index',
            ]);
        }

        $totalAllowed = (int)(
            $application['total_leave_allowed'] ?? 5
        );

        if ($totalAllowed <= 0) {
            $totalAllowed = 5;
        }

        $approvedLeave = $connection
            ->execute(
                'SELECT
                    COALESCE(
                        SUM(total_days),
                        0
                    ) AS total
                 FROM leaves
                 WHERE student_id = ?
                 AND LOWER(status) = "approved"',
                [$student['id']]
            )
            ->fetch('assoc');

        $usedDays = (int)($approvedLeave['total'] ?? 0);

        $remainingDays = max(
            0,
            $totalAllowed - $usedDays
        );

        if (!$this->request->is('post')) {
            $this->set(compact(
                'user',
                'student',
                'application',
                'totalAllowed',
                'usedDays',
                'remainingDays'
            ));

            return;
        }

        $leaveType = strtolower(
            trim(
                (string)$this->request
                    ->getData('leave_type')
            )
        );

        $startDate = trim(
            (string)$this->request
                ->getData('start_date')
        );

        $endDate = trim(
            (string)$this->request
                ->getData('end_date')
        );

        $reason = trim(
            (string)$this->request
                ->getData('reason')
        );

        $allowedTypes = [
            'medical',
            'emergency',
            'personal',
            'family',
            'other',
        ];

        if (!in_array($leaveType, $allowedTypes, true)) {
            $this->Flash->error(
                'Please select a valid leave type.'
            );

            $this->set(compact(
                'user',
                'student',
                'application',
                'totalAllowed',
                'usedDays',
                'remainingDays'
            ));

            return;
        }

        if ($startDate === '' || $endDate === '') {
            $this->Flash->error(
                'Start date and end date are required.'
            );

            $this->set(compact(
                'user',
                'student',
                'application',
                'totalAllowed',
                'usedDays',
                'remainingDays'
            ));

            return;
        }

        $startTimestamp = strtotime($startDate);
        $endTimestamp = strtotime($endDate);

        if (
            $startTimestamp === false
            || $endTimestamp === false
        ) {
            $this->Flash->error(
                'Please enter valid leave dates.'
            );

            $this->set(compact(
                'user',
                'student',
                'application',
                'totalAllowed',
                'usedDays',
                'remainingDays'
            ));

            return;
        }

        if ($endTimestamp < $startTimestamp) {
            $this->Flash->error(
                'End date cannot be before start date.'
            );

            $this->set(compact(
                'user',
                'student',
                'application',
                'totalAllowed',
                'usedDays',
                'remainingDays'
            ));

            return;
        }

        if (
            !empty($application['start_date'])
            && $startDate < $application['start_date']
        ) {
            $this->Flash->error(
                'Leave cannot start before your internship start date.'
            );

            $this->set(compact(
                'user',
                'student',
                'application',
                'totalAllowed',
                'usedDays',
                'remainingDays'
            ));

            return;
        }

        if (
            !empty($application['end_date'])
            && $endDate > $application['end_date']
        ) {
            $this->Flash->error(
                'Leave cannot end after your internship end date.'
            );

            $this->set(compact(
                'user',
                'student',
                'application',
                'totalAllowed',
                'usedDays',
                'remainingDays'
            ));

            return;
        }

        $totalDays = (int)floor(
            ($endTimestamp - $startTimestamp) / 86400
        ) + 1;

        if ($totalDays <= 0) {
            $this->Flash->error(
                'Invalid leave duration.'
            );

            $this->set(compact(
                'user',
                'student',
                'application',
                'totalAllowed',
                'usedDays',
                'remainingDays'
            ));

            return;
        }

        if ($totalDays > $remainingDays) {
            $this->Flash->error(
                'Your leave request exceeds the remaining leave balance of '
                . $remainingDays
                . ' day(s).'
            );

            $this->set(compact(
                'user',
                'student',
                'application',
                'totalAllowed',
                'usedDays',
                'remainingDays'
            ));

            return;
        }

        if (mb_strlen($reason) < 10) {
            $this->Flash->error(
                'Please provide a reason of at least 10 characters.'
            );

            $this->set(compact(
                'user',
                'student',
                'application',
                'totalAllowed',
                'usedDays',
                'remainingDays'
            ));

            return;
        }

        $overlappingLeave = $connection
            ->execute(
                'SELECT id
                 FROM leaves
                 WHERE student_id = ?
                 AND LOWER(status)
                    IN ("pending", "approved")
                 AND start_date <= ?
                 AND end_date >= ?
                 LIMIT 1',
                [
                    $student['id'],
                    $endDate,
                    $startDate,
                ]
            )
            ->fetch('assoc');

        if ($overlappingLeave) {
            $this->Flash->error(
                'You already have another pending or approved leave request within this date range.'
            );

            $this->set(compact(
                'user',
                'student',
                'application',
                'totalAllowed',
                'usedDays',
                'remainingDays'
            ));

            return;
        }

        $mcFilename = null;

        try {
            $file = $this->request
                ->getData('mc_document');

            if (
                $file
                && method_exists(
                    $file,
                    'getClientFilename'
                )
                && $file->getClientFilename()
            ) {
                if ($file->getError() !== UPLOAD_ERR_OK) {
                    throw new \RuntimeException(
                        'Supporting document upload failed.'
                    );
                }

                $originalName = (string)$file
                    ->getClientFilename();

                $extension = strtolower(
                    pathinfo(
                        $originalName,
                        PATHINFO_EXTENSION
                    )
                );

                $allowedExtensions = [
                    'pdf',
                    'jpg',
                    'jpeg',
                    'png',
                ];

                if (
                    !in_array(
                        $extension,
                        $allowedExtensions,
                        true
                    )
                ) {
                    throw new \RuntimeException(
                        'Supporting document must be PDF, JPG, JPEG or PNG.'
                    );
                }

                if ((int)$file->getSize() > 5 * 1024 * 1024) {
                    throw new \RuntimeException(
                        'Supporting document cannot exceed 5 MB.'
                    );
                }

                $directory =
                    WWW_ROOT
                    . 'files'
                    . DS
                    . 'mc';

                if (
                    !is_dir($directory)
                    && !mkdir(
                        $directory,
                        0775,
                        true
                    )
                    && !is_dir($directory)
                ) {
                    throw new \RuntimeException(
                        'Supporting document folder could not be created.'
                    );
                }

                $mcFilename =
                    'leave_'
                    . (int)$student['id']
                    . '_'
                    . date('YmdHis')
                    . '_'
                    . bin2hex(random_bytes(3))
                    . '.'
                    . $extension;

                $file->moveTo(
                    $directory
                    . DS
                    . $mcFilename
                );
            }

            if (
                $leaveType === 'medical'
                && !$mcFilename
            ) {
                throw new \RuntimeException(
                    'A medical certificate is required for medical leave.'
                );
            }

            $connection->execute(
                'INSERT INTO leaves (
                    student_id,
                    leave_type,
                    start_date,
                    end_date,
                    reason,
                    total_days,
                    mc_doc_path,
                    status,
                    created,
                    modified
                 ) VALUES (
                    ?, ?, ?, ?, ?, ?, ?, "pending",
                    NOW(), NOW()
                 )',
                [
                    $student['id'],
                    $leaveType,
                    $startDate,
                    $endDate,
                    $reason,
                    $totalDays,
                    $mcFilename,
                ]
            );

            $this->Flash->success(
                'Leave request submitted successfully.'
            );

            return $this->redirect([
                'action' => 'index',
            ]);
        } catch (\Throwable $exception) {
            if ($mcFilename) {
                $uploadedFile =
                    WWW_ROOT
                    . 'files'
                    . DS
                    . 'mc'
                    . DS
                    . basename($mcFilename);

                if (is_file($uploadedFile)) {
                    @unlink($uploadedFile);
                }
            }

            $this->Flash->error(
                $exception->getMessage()
            );

            $this->set(compact(
                'user',
                'student',
                'application',
                'totalAllowed',
                'usedDays',
                'remainingDays'
            ));

            return;
        }
    }

    /**
     * Company approves or rejects leave request.
     */
    public function updateStatus(?int $id = null)
    {
        $this->request->allowMethod([
            'post',
            'put',
            'patch',
        ]);

        $user = $this->currentUser();

        $role = strtolower(
            trim((string)($user['role'] ?? ''))
        );

        if (!$user || $role !== 'company') {
            $this->Flash->error(
                'Only the assigned company can make a leave decision.'
            );

            return $this->redirect([
                'action' => 'index',
            ]);
        }

        if (!$id) {
            $this->Flash->error(
                'Invalid leave request.'
            );

            return $this->redirect([
                'action' => 'index',
            ]);
        }

        $status = strtolower(
            trim(
                (string)$this->request
                    ->getData('status')
            )
        );

        if (!in_array($status, ['approved', 'rejected'], true)) {
            $this->Flash->error(
                'Please select Approved or Rejected.'
            );

            return $this->redirect([
                'action' => 'index',
            ]);
        }

        $connection = ConnectionManager::get('default');

        $company = $connection
            ->execute(
                'SELECT id, company_name
                 FROM companies
                 WHERE user_id = ?
                 LIMIT 1',
                [$user['id']]
            )
            ->fetch('assoc');

        if (!$company) {
            $this->Flash->error(
                'Company profile was not found.'
            );

            return $this->redirect([
                'action' => 'index',
            ]);
        }

        $leave = $connection
            ->execute(
                'SELECT
                    l.id,
                    l.status,
                    l.total_days,
                    l.student_id,
                    a.total_leave_allowed
                 FROM leaves l
                 INNER JOIN applications a
                    ON a.id = (
                        SELECT a2.id
                        FROM applications a2
                        WHERE a2.student_id = l.student_id
                        AND LOWER(a2.status)
                            IN (
                                "approved",
                                "offered",
                                "completed"
                            )
                        ORDER BY a2.id DESC
                        LIMIT 1
                    )
                 WHERE l.id = ?
                 AND (
                    a.company_id = ?
                    OR (
                        a.company_id IS NULL
                        AND a.company_name = ?
                    )
                 )
                 LIMIT 1',
                [
                    $id,
                    $company['id'],
                    $company['company_name'],
                ]
            )
            ->fetch('assoc');

        if (!$leave) {
            $this->Flash->error(
                'Leave request was not found or does not belong to your company.'
            );

            return $this->redirect([
                'action' => 'index',
            ]);
        }

        $currentStatus = strtolower(
            trim((string)$leave['status'])
        );

        if (
            !in_array(
                $currentStatus,
                $this->pendingStatuses(),
                true
            )
        ) {
            $this->Flash->error(
                'This leave request already has a final decision.'
            );

            return $this->redirect([
                'action' => 'index',
            ]);
        }

        if ($status === 'approved') {
            $totalAllowed = (int)(
                $leave['total_leave_allowed'] ?? 5
            );

            if ($totalAllowed <= 0) {
                $totalAllowed = 5;
            }

            $approvedLeave = $connection
                ->execute(
                    'SELECT
                        COALESCE(
                            SUM(total_days),
                            0
                        ) AS total
                     FROM leaves
                     WHERE student_id = ?
                     AND LOWER(status) = "approved"
                     AND id <> ?',
                    [
                        $leave['student_id'],
                        $id,
                    ]
                )
                ->fetch('assoc');

            $alreadyUsed = (int)(
                $approvedLeave['total'] ?? 0
            );

            if (
                $alreadyUsed + (int)$leave['total_days']
                > $totalAllowed
            ) {
                $this->Flash->error(
                    'This request cannot be approved because it exceeds the student leave allowance.'
                );

                return $this->redirect([
                    'action' => 'index',
                ]);
            }
        }

        $connection->execute(
            'UPDATE leaves
             SET
                status = ?,
                modified = NOW()
             WHERE id = ?',
            [
                $status,
                $id,
            ]
        );

        $this->Flash->success(
            $status === 'approved'
                ? 'Leave request approved successfully.'
                : 'Leave request rejected successfully.'
        );

        return $this->redirect([
            'action' => 'index',
        ]);
    }

    /**
     * Admin deletes leave request.
     */
    public function delete(?int $id = null)
    {
        $this->Flash->error('Leave records are read-only for administrators.');
        return $this->redirect(['action' => 'index']);
    }
}