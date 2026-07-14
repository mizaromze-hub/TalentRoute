<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Datasource\ConnectionManager;

class ApplicationsController extends AppController
{
    /**
     * Get currently logged-in user from session.
     *
     * @return array|null
     */
    private function currentUser(): ?array
    {
        $user = $this->request
            ->getSession()
            ->read('Auth.User');

        return is_array($user) ? $user : null;
    }

    /**
     * Display application list according to user role.
     *
     * @return \Cake\Http\Response|null|void
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

        $sql = "
            SELECT
                a.*,
                s.name,
                s.matrix_number,
                s.faculty,
                s.course,
                s.semester,
                s.phone_number,
                s.resume_path,
                COALESCE(
                    NULLIF(c.company_name, ''),
                    NULLIF(a.company_name, ''),
                    'Company'
                ) AS display_company
            FROM applications a
            INNER JOIN students s
                ON s.id = a.student_id
            LEFT JOIN companies c
                ON c.id = a.company_id
        ";

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

            $sql .= ' WHERE a.student_id = ?';
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

            $sql .= "
                WHERE (
                    a.company_id = ?
                    OR (
                        a.company_id IS NULL
                        AND a.company_name = ?
                    )
                )
            ";

            $params[] = $company['id'];
            $params[] = $company['company_name'];
        } elseif ($role !== 'admin') {
            $this->Flash->error(
                'You are not authorised to view this page.'
            );

            return $this->redirect([
                'controller' => 'Dashboard',
                'action' => 'index',
            ]);
        }

        $sql .= ' ORDER BY a.id DESC';

        $applications = $connection
            ->execute($sql, $params)
            ->fetchAll('assoc');

        $this->set(compact(
            'applications',
            'user',
            'role'
        ));
    }


    /**
     * Allow a student to edit their own application while it is pending.
     * The original apply_date is never changed.
     *
     * @param int|null $id Application ID.
     * @return \Cake\Http\Response|null|void
     */
    public function edit(?int $id = null)
    {
        $user = $this->currentUser();

        if (!$user) {
            return $this->redirect([
                'controller' => 'Users',
                'action' => 'login',
            ]);
        }

        $role = strtolower(trim((string)($user['role'] ?? '')));

        if ($role !== 'student') {
            $this->Flash->error(
                'Only students can edit an internship application.'
            );

            return $this->redirect([
                'action' => 'index',
            ]);
        }

        if (!$id) {
            $this->Flash->error('Invalid application.');
            return $this->redirect(['action' => 'index']);
        }

        $connection = ConnectionManager::get('default');

        $student = $connection
            ->execute(
                'SELECT id, resume_path, resume
                 FROM students
                 WHERE user_id = ?
                 LIMIT 1',
                [$user['id']]
            )
            ->fetch('assoc');

        if (!$student) {
            $this->Flash->error('Student profile was not found.');
            return $this->redirect(['action' => 'index']);
        }

        $application = $connection
            ->execute(
                'SELECT
                    a.*,
                    COALESCE(
                        NULLIF(c.company_name, ""),
                        NULLIF(a.company_name, ""),
                        "Company"
                    ) AS display_company
                 FROM applications a
                 LEFT JOIN companies c ON c.id = a.company_id
                 WHERE a.id = ? AND a.student_id = ?
                 LIMIT 1',
                [$id, $student['id']]
            )
            ->fetch('assoc');

        if (!$application) {
            $this->Flash->error(
                'Application was not found or does not belong to you.'
            );

            return $this->redirect(['action' => 'index']);
        }

        $currentStatus = strtolower(
            trim((string)($application['status'] ?? 'pending'))
        );

        $editableStatuses = [
            'applied',
            'pending',
            'reviewing',
            'interview',
        ];

        if (!in_array($currentStatus, $editableStatuses, true)) {
            $this->Flash->error(
                'Only a pending application can be edited.'
            );

            return $this->redirect(['action' => 'index']);
        }

        if ($this->request->is(['post', 'put', 'patch'])) {
            $startDate = trim(
                (string)$this->request->getData('start_date')
            );

            $endDate = trim(
                (string)$this->request->getData('end_date')
            );

            $remarks = trim(
                (string)$this->request->getData('remarks')
            );

            if ($startDate === '' || $endDate === '') {
                $this->Flash->error(
                    'Please enter the internship start date and end date.'
                );

                $application['start_date'] = $startDate;
                $application['end_date'] = $endDate;
                $application['remarks'] = $remarks;
                $this->set(compact('application', 'user'));
                return;
            }

            $start = \DateTimeImmutable::createFromFormat(
                '!Y-m-d',
                $startDate
            );

            $end = \DateTimeImmutable::createFromFormat(
                '!Y-m-d',
                $endDate
            );

            $today = new \DateTimeImmutable('today');

            if (!$start || $start->format('Y-m-d') !== $startDate) {
                $this->Flash->error('Invalid internship start date.');
                $application['start_date'] = $startDate;
                $application['end_date'] = $endDate;
                $application['remarks'] = $remarks;
                $this->set(compact('application', 'user'));
                return;
            }

            if (!$end || $end->format('Y-m-d') !== $endDate) {
                $this->Flash->error('Invalid internship end date.');
                $application['start_date'] = $startDate;
                $application['end_date'] = $endDate;
                $application['remarks'] = $remarks;
                $this->set(compact('application', 'user'));
                return;
            }

            if ($start < $today) {
                $this->Flash->error(
                    'Internship start date cannot be before today.'
                );

                $application['start_date'] = $startDate;
                $application['end_date'] = $endDate;
                $application['remarks'] = $remarks;
                $this->set(compact('application', 'user'));
                return;
            }

            if ($end < $start) {
                $this->Flash->error(
                    'Internship end date cannot be before the start date.'
                );

                $application['start_date'] = $startDate;
                $application['end_date'] = $endDate;
                $application['remarks'] = $remarks;
                $this->set(compact('application', 'user'));
                return;
            }

            $resumeFilename = (string)(
                $application['resume_file']
                ?: ($student['resume_path'] ?? '')
                ?: ($student['resume'] ?? '')
            );

            $newResumeFilename = null;
            $resumeFile = $this->request->getData('resume_file');

            try {
                if (
                    $resumeFile
                    && method_exists($resumeFile, 'getClientFilename')
                    && $resumeFile->getClientFilename()
                ) {
                    if ($resumeFile->getError() !== UPLOAD_ERR_OK) {
                        throw new \RuntimeException(
                            'Resume upload failed.'
                        );
                    }

                    $extension = strtolower(pathinfo(
                        (string)$resumeFile->getClientFilename(),
                        PATHINFO_EXTENSION
                    ));

                    if ($extension !== 'pdf') {
                        throw new \RuntimeException(
                            'Resume must be uploaded in PDF format.'
                        );
                    }

                    if ((int)$resumeFile->getSize() > 5 * 1024 * 1024) {
                        throw new \RuntimeException(
                            'Resume file size cannot exceed 5 MB.'
                        );
                    }

                    $directory = WWW_ROOT . 'files';

                    if (
                        !is_dir($directory)
                        && !mkdir($directory, 0775, true)
                        && !is_dir($directory)
                    ) {
                        throw new \RuntimeException(
                            'Resume upload directory could not be created.'
                        );
                    }

                    $newResumeFilename =
                        'resume_application_'
                        . (int)$student['id']
                        . '_'
                        . (int)$id
                        . '_'
                        . date('YmdHis')
                        . '_'
                        . bin2hex(random_bytes(3))
                        . '.pdf';

                    $resumeFile->moveTo(
                        $directory . DS . $newResumeFilename
                    );

                    $resumeFilename = $newResumeFilename;
                }

                $connection->begin();

                $connection->execute(
                    'UPDATE applications
                     SET
                        start_date = ?,
                        end_date = ?,
                        remarks = ?,
                        resume_file = ?,
                        modified = NOW()
                     WHERE id = ? AND student_id = ?',
                    [
                        $startDate,
                        $endDate,
                        $remarks !== '' ? $remarks : null,
                        $resumeFilename,
                        $id,
                        $student['id'],
                    ]
                );

                if ($newResumeFilename !== null) {
                    $connection->execute(
                        'UPDATE students
                         SET resume_path = ?, resume = ?
                         WHERE id = ?',
                        [
                            $newResumeFilename,
                            $newResumeFilename,
                            $student['id'],
                        ]
                    );
                }

                $connection->commit();

                if (
                    $newResumeFilename !== null
                    && !empty($application['resume_file'])
                    && $application['resume_file'] !== $newResumeFilename
                ) {
                    $oldFile = WWW_ROOT
                        . 'files'
                        . DS
                        . basename((string)$application['resume_file']);

                    if (is_file($oldFile)) {
                        @unlink($oldFile);
                    }
                }

                $this->Flash->success(
                    'Application updated successfully.'
                );

                return $this->redirect(['action' => 'index']);
            } catch (\Throwable $exception) {
                if ($connection->inTransaction()) {
                    $connection->rollback();
                }

                if ($newResumeFilename !== null) {
                    $newFile = WWW_ROOT
                        . 'files'
                        . DS
                        . basename($newResumeFilename);

                    if (is_file($newFile)) {
                        @unlink($newFile);
                    }
                }

                $this->Flash->error($exception->getMessage());
                $application['start_date'] = $startDate;
                $application['end_date'] = $endDate;
                $application['remarks'] = $remarks;
            }
        }

        $this->set(compact('application', 'user'));
    }

    /**
     * Approve or reject an application.
     *
     * Application decision can only be made once.
     *
     * @param int|null $id Application ID.
     * @return \Cake\Http\Response|null
     */
    public function updateStatus(?int $id = null)
    {
        $this->request->allowMethod([
            'post',
            'put',
            'patch',
        ]);

        $user = $this->currentUser();

        if (!$user) {
            $this->Flash->error(
                'Please log in before continuing.'
            );

            return $this->redirect([
                'controller' => 'Users',
                'action' => 'login',
            ]);
        }

        $role = strtolower(
            trim((string)($user['role'] ?? ''))
        );

        if ($role !== 'company') {
            $this->Flash->error(
                'You are not authorised to update this application.'
            );

            return $this->redirect([
                'action' => 'index',
            ]);
        }

        if (!$id) {
            $this->Flash->error(
                'Invalid application.'
            );

            return $this->redirect([
                'action' => 'index',
            ]);
        }

        $connection = ConnectionManager::get('default');

        /*
         * Retrieve the application first.
         * Company users may only update applications
         * belonging to their company.
         */
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
            $this->Flash->error('Company profile was not found.');
            return $this->redirect(['action' => 'index']);
        }

        $application = $connection
            ->execute(
                'SELECT id, status, company_id, company_name, start_date, end_date
                 FROM applications
                 WHERE id = ?
                 AND (
                    company_id = ?
                    OR (company_id IS NULL AND company_name = ?)
                 )
                 LIMIT 1',
                [$id, $company['id'], $company['company_name']]
            )
            ->fetch('assoc');

        if (!$application) {
            $this->Flash->error(
                'Application was not found or access was denied.'
            );

            return $this->redirect([
                'action' => 'index',
            ]);
        }

        /*
         * Only pending-style statuses may be decided.
         * Approved and rejected applications become read-only.
         */
        $currentStatus = strtolower(
            trim((string)($application['status'] ?? 'pending'))
        );

        $editableStatuses = [
            'applied',
            'pending',
            'reviewing',
            'interview',
        ];

        if (!in_array($currentStatus, $editableStatuses, true)) {
            $this->Flash->error(
                'This application already has a final decision and cannot be changed.'
            );

            return $this->redirect([
                'action' => 'index',
            ]);
        }

        $status = strtolower(
            trim((string)$this->request->getData('status'))
        );

        $remarks = trim(
            (string)$this->request->getData('remarks')
        );

        if (!in_array($status, ['approved', 'rejected'], true)) {
            $this->Flash->error(
                'Please select Approved or Rejected.'
            );

            return $this->redirect([
                'action' => 'index',
            ]);
        }

        /*
         * Remarks are required for clearer records.
         */
        if ($remarks === '') {
            $this->Flash->error(
                'Please enter remarks for the application decision.'
            );

            return $this->redirect([
                'action' => 'index',
            ]);
        }

        if ($status === 'approved') {
            if (empty($application['start_date']) || empty($application['end_date'])) {
                $this->Flash->error(
                    'The student must provide the internship start and end dates before approval.'
                );
                return $this->redirect(['action' => 'index']);
            }

            $connection->execute(
                'UPDATE applications
                 SET status = ?, remarks = ?, modified = NOW()
                 WHERE id = ?',
                ['approved', $remarks, $id]
            );

            $this->Flash->success('Application approved successfully.');
        } else {
            $connection->execute(
                'UPDATE applications
                 SET status = ?, remarks = ?, modified = NOW()
                 WHERE id = ?',
                ['rejected', $remarks, $id]
            );

            $this->Flash->success('Application rejected successfully.');
        }

        return $this->redirect([
            'action' => 'index',
        ]);
    }

    /**
     * Delete application.
     *
     * Admin only.
     *
     * @param int|null $id Application ID.
     * @return \Cake\Http\Response|null
     */
    public function delete(?int $id = null)
    {
        $this->request->allowMethod([
            'post',
            'delete',
        ]);

        $user = $this->currentUser();

        $role = strtolower(
            trim((string)($user['role'] ?? ''))
        );

        if (!$user || $role !== 'admin') {
            $this->Flash->error(
                'Only an administrator can delete an application.'
            );

            return $this->redirect([
                'action' => 'index',
            ]);
        }

        if (!$id) {
            $this->Flash->error(
                'Invalid application.'
            );

            return $this->redirect([
                'action' => 'index',
            ]);
        }

        $connection = ConnectionManager::get('default');

        $application = $connection
            ->execute(
                'SELECT id
                 FROM applications
                 WHERE id = ?
                 LIMIT 1',
                [$id]
            )
            ->fetch('assoc');

        if (!$application) {
            $this->Flash->error(
                'Application was not found.'
            );

            return $this->redirect([
                'action' => 'index',
            ]);
        }

        $connection->execute(
            'DELETE FROM applications
             WHERE id = ?',
            [$id]
        );

        $this->Flash->success(
            'Application deleted successfully.'
        );

        return $this->redirect([
            'action' => 'index',
        ]);
    }
}