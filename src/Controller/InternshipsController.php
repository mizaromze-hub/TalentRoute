<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Datasource\ConnectionManager;

class InternshipsController extends AppController
{
    /**
     * Return currently logged-in user.
     */
    private function currentUser(): ?array
    {
        $user = $this->request
            ->getSession()
            ->read('Auth.User');

        return is_array($user) ? $user : null;
    }

    /**
     * Browse internship companies.
     */
    public function search()
    {
        $user = $this->currentUser();

        if (!$user) {
            return $this->redirect([
                'controller' => 'Users',
                'action' => 'login',
            ]);
        }

        $role = strtolower(
            trim((string)($user['role'] ?? ''))
        );

        if ($role !== 'student') {
            $this->Flash->error(
                'Only students can browse internship opportunities.'
            );

            return $this->redirect([
                'controller' => 'Dashboard',
                'action' => 'index',
            ]);
        }

        $connection = ConnectionManager::get('default');

        $keyword = trim(
            (string)$this->request->getQuery('keyword')
        );

        if ($keyword !== '') {
            $searchValue = '%' . $keyword . '%';

            $companies = $connection
                ->execute(
                    'SELECT *
                     FROM companies
                     WHERE company_name LIKE ?
                     OR registration_number LIKE ?
                     OR city LIKE ?
                     OR state LIKE ?
                     ORDER BY company_name ASC',
                    [
                        $searchValue,
                        $searchValue,
                        $searchValue,
                        $searchValue,
                    ]
                )
                ->fetchAll('assoc');
        } else {
            $companies = $connection
                ->execute(
                    'SELECT *
                     FROM companies
                     ORDER BY company_name ASC'
                )
                ->fetchAll('assoc');
        }

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
                'Please complete your student profile before applying.'
            );

            return $this->redirect([
                'controller' => 'Students',
                'action' => 'edit',
            ]);
        }

        $applicationRows = $connection
            ->execute(
                'SELECT
                    company_id,
                    company_name,
                    status
                 FROM applications
                 WHERE student_id = ?',
                [$student['id']]
            )
            ->fetchAll('assoc');

        $appliedCompanies = [];

        foreach ($applicationRows as $applicationRow) {
            $companyId = (int)(
                $applicationRow['company_id'] ?? 0
            );

            if ($companyId > 0) {
                $appliedCompanies[$companyId] = strtolower(
                    (string)($applicationRow['status'] ?? 'pending')
                );
            }
        }

        $this->set(compact(
            'companies',
            'appliedCompanies',
            'student',
            'user',
            'keyword'
        ));
    }

    /**
     * Student completes and submits internship application.
     */
    public function add(?int $companyId = null)
    {
        $user = $this->currentUser();

        if (!$user) {
            return $this->redirect([
                'controller' => 'Users',
                'action' => 'login',
            ]);
        }

        $role = strtolower(
            trim((string)($user['role'] ?? ''))
        );

        if ($role !== 'student') {
            $this->Flash->error(
                'Only students can submit internship applications.'
            );

            return $this->redirect([
                'controller' => 'Dashboard',
                'action' => 'index',
            ]);
        }

        if (!$companyId) {
            $this->Flash->error(
                'Please select a valid company.'
            );

            return $this->redirect([
                'action' => 'search',
            ]);
        }

        $connection = ConnectionManager::get('default');

        $student = $connection
            ->execute(
                'SELECT
                    s.*,
                    u.email
                 FROM students s
                 INNER JOIN users u
                    ON u.id = s.user_id
                 WHERE s.user_id = ?
                 LIMIT 1',
                [$user['id']]
            )
            ->fetch('assoc');

        if (!$student) {
            $this->Flash->error(
                'Student profile was not found.'
            );

            return $this->redirect([
                'controller' => 'Students',
                'action' => 'edit',
            ]);
        }

        $company = $connection
            ->execute(
                'SELECT *
                 FROM companies
                 WHERE id = ?
                 LIMIT 1',
                [$companyId]
            )
            ->fetch('assoc');

        if (!$company) {
            $this->Flash->error(
                'The selected company was not found.'
            );

            return $this->redirect([
                'action' => 'search',
            ]);
        }

        $existingApplication = $connection
            ->execute(
                'SELECT id, status
                 FROM applications
                 WHERE student_id = ?
                 AND (
                    company_id = ?
                    OR company_name = ?
                 )
                 LIMIT 1',
                [
                    $student['id'],
                    $company['id'],
                    $company['company_name'],
                ]
            )
            ->fetch('assoc');

        if ($existingApplication) {
            $existingStatus = strtolower(
                (string)$existingApplication['status']
            );

            $this->Flash->error(
                'You have already applied to this company. Current status: '
                . ucfirst($existingStatus)
                . '.'
            );

            return $this->redirect([
                'controller' => 'Applications',
                'action' => 'index',
            ]);
        }

        if (!$this->request->is('post')) {
            $this->set(compact(
                'user',
                'student',
                'company'
            ));

            return;
        }

        $data = $this->request->getData();

        $startDate = trim(
            (string)($data['start_date'] ?? '')
        );

        $endDate = trim(
            (string)($data['end_date'] ?? '')
        );

        $remarks = trim(
            (string)($data['remarks'] ?? '')
        );

        if ($startDate === '' || $endDate === '') {
            $this->Flash->error(
                'Proposed start date and end date are required.'
            );

            $this->set(compact(
                'user',
                'student',
                'company'
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
                'Please enter valid internship dates.'
            );

            $this->set(compact(
                'user',
                'student',
                'company'
            ));

            return;
        }

        if ($startTimestamp < strtotime(date('Y-m-d'))) {
            $this->Flash->error(
                'The proposed start date cannot be in the past.'
            );

            $this->set(compact(
                'user',
                'student',
                'company'
            ));

            return;
        }

        if ($endTimestamp <= $startTimestamp) {
            $this->Flash->error(
                'The proposed end date must be after the start date.'
            );

            $this->set(compact(
                'user',
                'student',
                'company'
            ));

            return;
        }

        if (mb_strlen($remarks) < 20) {
            $this->Flash->error(
                'Please provide an application message of at least 20 characters.'
            );

            $this->set(compact(
                'user',
                'student',
                'company'
            ));

            return;
        }

        $resumeFilename = null;
        $uploadedNewResume = false;

        $resumeFile = $this->request
            ->getData('resume_file');

        try {
            if (
                $resumeFile
                && method_exists(
                    $resumeFile,
                    'getClientFilename'
                )
                && $resumeFile->getClientFilename()
            ) {
                if (
                    $resumeFile->getError()
                    !== UPLOAD_ERR_OK
                ) {
                    throw new \RuntimeException(
                        'Resume upload failed.'
                    );
                }

                $originalName = (string)$resumeFile
                    ->getClientFilename();

                $extension = strtolower(
                    pathinfo(
                        $originalName,
                        PATHINFO_EXTENSION
                    )
                );

                if ($extension !== 'pdf') {
                    throw new \RuntimeException(
                        'Resume must be uploaded in PDF format.'
                    );
                }

                if (
                    (int)$resumeFile->getSize()
                    > 5 * 1024 * 1024
                ) {
                    throw new \RuntimeException(
                        'Resume file size cannot exceed 5 MB.'
                    );
                }

                $directory = WWW_ROOT . 'files';

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
                        'Resume upload directory could not be created.'
                    );
                }

                $resumeFilename =
                    'resume_application_'
                    . (int)$student['id']
                    . '_'
                    . (int)$company['id']
                    . '_'
                    . date('YmdHis')
                    . '_'
                    . bin2hex(random_bytes(3))
                    . '.pdf';

                $resumeFile->moveTo(
                    $directory
                    . DS
                    . $resumeFilename
                );

                $uploadedNewResume = true;

                $connection->execute(
                    'UPDATE students
                     SET
                        resume_path = ?,
                        resume = ?
                     WHERE id = ?',
                    [
                        $resumeFilename,
                        $resumeFilename,
                        $student['id'],
                    ]
                );
            } else {
                $resumeFilename =
                    $student['resume_path']
                    ?: ($student['resume'] ?? null);
            }

            if (!$resumeFilename) {
                throw new \RuntimeException(
                    'Please upload your resume in PDF format before submitting the application.'
                );
            }

            $connection->execute(
                'INSERT INTO applications (
                    student_id,
                    company_id,
                    company_name,
                    apply_date,
                    start_date,
                    end_date,
                    resume_file,
                    status,
                    remarks,
                    created,
                    modified
                 ) VALUES (
                    ?, ?, ?, ?, ?, ?, ?, ?, ?,
                    NOW(), NOW()
                 )',
                [
                    $student['id'],
                    $company['id'],
                    $company['company_name'],
                    date('Y-m-d'),
                    $startDate,
                    $endDate,
                    $resumeFilename,
                    'pending',
                    $remarks,
                ]
            );

            $this->Flash->success(
                'Your internship application was submitted successfully.'
            );

            return $this->redirect([
                'controller' => 'Applications',
                'action' => 'index',
            ]);
        } catch (\Throwable $exception) {
            if ($uploadedNewResume && $resumeFilename) {
                $uploadedFile =
                    WWW_ROOT
                    . 'files'
                    . DS
                    . basename($resumeFilename);

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
                'company'
            ));

            return;
        }
    }

    /**
     * Display approved internship official letter.
     */
    public function letter(?int $id = null)
    {
        $this->viewBuilder()->setLayout('ajax');

        $user = $this->currentUser();

        if (
            !$user
            || strtolower((string)($user['role'] ?? ''))
                !== 'student'
        ) {
            return $this->redirect([
                'controller' => 'Dashboard',
                'action' => 'index',
            ]);
        }

        $connection = ConnectionManager::get('default');

        $student = $connection
            ->execute(
                'SELECT
                    s.*,
                    u.email
                 FROM students s
                 INNER JOIN users u
                    ON u.id = s.user_id
                 WHERE s.user_id = ?
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

        $sql = '
            SELECT
                a.*,
                COALESCE(
                    c.company_name,
                    a.company_name
                ) AS display_company,
                c.address_line1,
                c.address_line2,
                c.postcode,
                c.city,
                c.state,
                c.phone_number AS company_phone
            FROM applications a
            LEFT JOIN companies c
                ON c.id = a.company_id
            WHERE a.student_id = ?
            AND LOWER(a.status)
                IN ("offered", "approved", "rejected")
        ';

        $params = [$student['id']];

        if ($id) {
            $sql .= ' AND a.id = ?';
            $params[] = $id;
        }

        $sql .= ' ORDER BY a.id DESC LIMIT 1';

        $application = $connection
            ->execute($sql, $params)
            ->fetch('assoc');

        if (!$application) {
            $this->Flash->error(
                'Official letter is only available after the company has made a final decision.'
            );

            return $this->redirect([
                'controller' => 'Applications',
                'action' => 'index',
            ]);
        }

        $referenceNumber =
            'TR/INT/'
            . str_pad(
                (string)$application['id'],
                5,
                '0',
                STR_PAD_LEFT
            );

        $letterDate = date('d F Y');

        $this->set(compact(
            'student',
            'application',
            'referenceNumber',
            'letterDate'
        ));
    }
}