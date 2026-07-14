<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\Routing\Router;

class StudentsController extends AppController
{
    private function currentUser(): ?array
    {
        $user = $this->request
            ->getSession()
            ->read('Auth.User');

        return is_array($user) ? $user : null;
    }

    private function generateStudentToken(
        int $studentId,
        string $matrixNumber
    ): string {
        return hash_hmac(
            'sha256',
            $studentId . '|' . $matrixNumber,
            (string)Configure::read('Security.salt')
        );
    }

    private function saveResumeFile(
        mixed $file,
        int $userId
    ): ?string {
        if (
            !$file
            || !method_exists($file, 'getClientFilename')
            || !$file->getClientFilename()
        ) {
            return null;
        }

        if ($file->getError() !== UPLOAD_ERR_OK) {
            throw new \RuntimeException(
                'Resume upload failed.'
            );
        }

        $extension = strtolower(
            pathinfo(
                (string)$file->getClientFilename(),
                PATHINFO_EXTENSION
            )
        );

        if ($extension !== 'pdf') {
            throw new \RuntimeException(
                'Resume must be a PDF file.'
            );
        }

        if ((int)$file->getSize() > 5 * 1024 * 1024) {
            throw new \RuntimeException(
                'Resume cannot exceed 5 MB.'
            );
        }

        $directory = WWW_ROOT . 'files';

        if (
            !is_dir($directory)
            && !mkdir($directory, 0775, true)
            && !is_dir($directory)
        ) {
            throw new \RuntimeException(
                'Resume upload folder could not be created.'
            );
        }

        $filename =
            'resume_user_'
            . $userId
            . '_'
            . date('YmdHis')
            . '_'
            . bin2hex(random_bytes(4))
            . '.pdf';

        $file->moveTo(
            $directory . DS . $filename
        );

        return $filename;
    }

    public function index()
    {
        $u = $this->currentUser();

        if (!$u) {
            return $this->redirect([
                'controller' => 'Users',
                'action' => 'login',
            ]);
        }

        $role = strtolower(
            (string)($u['role'] ?? '')
        );

        if (
            !in_array(
                $role,
                ['admin', 'company', 'student'],
                true
            )
        ) {
            return $this->redirect([
                'controller' => 'Dashboard',
                'action' => 'index',
            ]);
        }

        $conn = ConnectionManager::get('default');

        $search = trim(
            (string)$this->request
                ->getQuery('search')
        );

        $sql =
            'SELECT s.*, u.email
             FROM students s
             INNER JOIN users u
                ON u.id = s.user_id';

        $params = [];

        if ($role === 'student') {
            $sql .= ' WHERE s.user_id = ?';
            $params[] = $u['id'];
        } elseif ($search !== '') {
            $value = '%' . $search . '%';

            $sql .=
                ' WHERE s.name LIKE ?
                   OR s.matrix_number LIKE ?
                   OR s.faculty LIKE ?
                   OR s.course LIKE ?
                   OR u.email LIKE ?';

            $params = [
                $value,
                $value,
                $value,
                $value,
                $value,
            ];
        }

        $sql .= ' ORDER BY s.name ASC';

        $studentsList = $conn
            ->execute($sql, $params)
            ->fetchAll('assoc');

        $this->set(compact(
            'studentsList',
            'u',
            'search'
        ));
    }

    public function view(?int $id = null)
    {
        $u = $this->currentUser();

        if (!$u) {
            return $this->redirect([
                'controller' => 'Users',
                'action' => 'login',
            ]);
        }

        $role = strtolower(
            (string)($u['role'] ?? '')
        );

        $conn = ConnectionManager::get('default');

        if ($role === 'student') {
            $student = $conn
                ->execute(
                    'SELECT s.*, u.email
                     FROM students s
                     INNER JOIN users u
                        ON u.id = s.user_id
                     WHERE s.user_id = ?
                     LIMIT 1',
                    [$u['id']]
                )
                ->fetch('assoc');
        } elseif (
            in_array(
                $role,
                ['admin', 'company'],
                true
            )
            && $id
        ) {
            $student = $conn
                ->execute(
                    'SELECT s.*, u.email
                     FROM students s
                     INNER JOIN users u
                        ON u.id = s.user_id
                     WHERE s.id = ?
                     LIMIT 1',
                    [$id]
                )
                ->fetch('assoc');
        } else {
            $student = null;
        }

        if (!$student) {
            $this->Flash->error(
                'Student profile was not found.'
            );

            return $this->redirect([
                'controller' => 'Dashboard',
                'action' => 'index',
            ]);
        }

        $application = $conn
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
                 ORDER BY a.id DESC
                 LIMIT 1',
                [$student['id']]
            )
            ->fetch('assoc');

        $token = $this->generateStudentToken(
            (int)$student['id'],
            (string)$student['matrix_number']
        );

        /*
         * Controller tidak boleh guna $this->Url.
         * Gunakan Router::url() untuk bina full URL.
         */
        $verificationUrl = Router::url(
            [
                'controller' => 'Students',
                'action' => 'verify',
                $student['id'],
                $token,
            ],
            true
        );

        $this->set(compact(
            'student',
            'application',
            'verificationUrl',
            'u',
            'role'
        ));
    }

    public function add()
    {
        $u = $this->currentUser();

        if (
            !$u
            || strtolower(
                (string)($u['role'] ?? '')
            ) !== 'admin'
        ) {
            return $this->redirect([
                'controller' => 'Dashboard',
                'action' => 'index',
            ]);
        }

        if (!$this->request->is('post')) {
            $this->set(compact('u'));
            return;
        }

        $data = $this->request->getData();

        $name = trim(
            (string)($data['name'] ?? '')
        );

        $matrixNumber = trim(
            (string)($data['matrix_number'] ?? '')
        );

        $email = strtolower(
            trim(
                (string)($data['email'] ?? '')
            )
        );

        $password = (string)(
            $data['password'] ?? ''
        );

        $faculty = trim(
            (string)($data['faculty'] ?? '')
        );

        $course = trim(
            (string)($data['course'] ?? '')
        );

        $semester = (int)(
            $data['semester'] ?? 0
        );

        $phoneNumber = trim(
            (string)($data['phone_number'] ?? '')
        );

        $address = trim(
            (string)($data['address'] ?? '')
        );

        $city = trim(
            (string)($data['city'] ?? '')
        );

        $postcode = trim(
            (string)($data['postcode'] ?? '')
        );

        $state = trim(
            (string)($data['state'] ?? '')
        );

        if (
            !preg_match(
                '/^[0-9]{12}$/',
                $matrixNumber
            )
        ) {
            $this->Flash->error(
                'Matrix number must contain exactly 12 digits.'
            );

            $this->set(compact('u'));
            return;
        }

        if (
            $name === ''
            || !filter_var(
                $email,
                FILTER_VALIDATE_EMAIL
            )
            || strlen($password) < 8
            || $faculty === ''
            || $course === ''
            || $semester < 1
            || $semester > 8
            || $phoneNumber === ''
        ) {
            $this->Flash->error(
                'Please complete all required fields correctly.'
            );

            $this->set(compact('u'));
            return;
        }

        $conn = ConnectionManager::get('default');

        $existingEmail = $conn
            ->execute(
                'SELECT id
                 FROM users
                 WHERE LOWER(email) = LOWER(?)
                 LIMIT 1',
                [$email]
            )
            ->fetch('assoc');

        if ($existingEmail) {
            $this->Flash->error(
                'This email is already registered.'
            );

            $this->set(compact('u'));
            return;
        }

        $existingMatrix = $conn
            ->execute(
                'SELECT id
                 FROM students
                 WHERE matrix_number = ?
                 LIMIT 1',
                [$matrixNumber]
            )
            ->fetch('assoc');

        if ($existingMatrix) {
            $this->Flash->error(
                'This matrix number is already registered.'
            );

            $this->set(compact('u'));
            return;
        }

        $resumeFilename = null;

        $conn->begin();

        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            if ($hashedPassword === false) {
                throw new \RuntimeException('Password could not be secured.');
            }

            $conn->execute(
                'INSERT INTO users (
                    email,
                    password,
                    role
                 ) VALUES (?, ?, ?)',
                [
                    $email,
                    $hashedPassword,
                    'student',
                ]
            );

            $userId = (int)$conn
                ->execute(
                    'SELECT LAST_INSERT_ID() AS id'
                )
                ->fetch('assoc')['id'];

            $resumeFilename =
                $this->saveResumeFile(
                    $this->request
                        ->getData('resume_file'),
                    $userId
                );

            $conn->execute(
                'INSERT INTO students (
                    user_id,
                    name,
                    matrix_number,
                    faculty,
                    course,
                    semester,
                    phone_number,
                    address,
                    city,
                    postcode,
                    state,
                    resume_path,
                    resume
                 ) VALUES (
                    ?, ?, ?, ?, ?, ?, ?,
                    ?, ?, ?, ?, ?, ?
                 )',
                [
                    $userId,
                    $name,
                    $matrixNumber,
                    $faculty,
                    $course,
                    $semester,
                    $phoneNumber,
                    $address !== '' ? $address : null,
                    $city !== '' ? $city : null,
                    $postcode !== '' ? $postcode : null,
                    $state !== '' ? $state : null,
                    $resumeFilename,
                    $resumeFilename,
                ]
            );

            $conn->commit();

            $this->Flash->success(
                'Student registered successfully.'
            );

            return $this->redirect([
                'action' => 'index',
            ]);
        } catch (\Throwable $e) {
            $conn->rollback();

            if (
                $resumeFilename
                && is_file(
                    WWW_ROOT
                    . 'files'
                    . DS
                    . basename($resumeFilename)
                )
            ) {
                @unlink(
                    WWW_ROOT
                    . 'files'
                    . DS
                    . basename($resumeFilename)
                );
            }

            $this->Flash->error(
                'Student registration failed: '
                . $e->getMessage()
            );

            $this->set(compact('u'));
        }
    }

    public function edit(?int $id = null)
    {
        $u = $this->currentUser();

        if (!$u) {
            return $this->redirect([
                'controller' => 'Users',
                'action' => 'login',
            ]);
        }

        $conn = ConnectionManager::get('default');

        $role = strtolower(
            (string)($u['role'] ?? '')
        );

        if ($role === 'student') {
            $student = $conn
                ->execute(
                    'SELECT *
                     FROM students
                     WHERE user_id = ?
                     LIMIT 1',
                    [$u['id']]
                )
                ->fetch('assoc');

            $id = (int)(
                $student['id'] ?? 0
            );
        } elseif (
            $role === 'admin'
            && $id
        ) {
            $student = $conn
                ->execute(
                    'SELECT *
                     FROM students
                     WHERE id = ?
                     LIMIT 1',
                    [$id]
                )
                ->fetch('assoc');
        } else {
            $student = null;
        }

        if (!$student || !$id) {
            $this->Flash->error(
                'Student profile was not found.'
            );

            return $this->redirect([
                'controller' => 'Dashboard',
                'action' => 'index',
            ]);
        }

        if (
            $this->request->is([
                'post',
                'put',
                'patch',
            ])
        ) {
            $data = $this->request->getData();

            $name = trim(
                (string)($data['name'] ?? '')
            );

            $faculty = trim(
                (string)($data['faculty'] ?? '')
            );

            $course = trim(
                (string)($data['course'] ?? '')
            );

            $semester = (int)(
                $data['semester'] ?? 0
            );

            $phoneNumber = trim(
                (string)($data['phone_number'] ?? '')
            );

            $address = trim(
                (string)($data['address'] ?? '')
            );

            $city = trim(
                (string)($data['city'] ?? '')
            );

            $postcode = trim(
                (string)($data['postcode'] ?? '')
            );

            $state = trim(
                (string)($data['state'] ?? '')
            );

            if (
                $name === ''
                || $faculty === ''
                || $course === ''
                || $semester < 1
                || $semester > 8
                || $phoneNumber === ''
            ) {
                $this->Flash->error(
                    'Please complete all required fields.'
                );

                $this->set(compact(
                    'student',
                    'u'
                ));

                return;
            }

            /*
             * Matrix number memang tidak dibaca daripada form
             * dan tidak akan dikemas kini.
             */
            $conn->execute(
                'UPDATE students
                 SET
                    name = ?,
                    faculty = ?,
                    course = ?,
                    semester = ?,
                    phone_number = ?,
                    address = ?,
                    city = ?,
                    postcode = ?,
                    state = ?
                 WHERE id = ?',
                [
                    $name,
                    $faculty,
                    $course,
                    $semester,
                    $phoneNumber,
                    $address !== '' ? $address : null,
                    $city !== '' ? $city : null,
                    $postcode !== '' ? $postcode : null,
                    $state !== '' ? $state : null,
                    $id,
                ]
            );

            $this->Flash->success(
                'Profile updated successfully.'
            );

            return $this->redirect(
                $role === 'student'
                    ? ['action' => 'view']
                    : ['action' => 'view', $id]
            );
        }

        $this->set(compact(
            'student',
            'u'
        ));
    }

    public function uploadResume()
    {
        $this->request
            ->allowMethod(['post']);

        $u = $this->currentUser();

        if (
            !$u
            || strtolower(
                (string)($u['role'] ?? '')
            ) !== 'student'
        ) {
            return $this->redirect([
                'controller' => 'Dashboard',
                'action' => 'index',
            ]);
        }

        $conn = ConnectionManager::get('default');

        $old = $conn
            ->execute(
                'SELECT resume_path
                 FROM students
                 WHERE user_id = ?
                 LIMIT 1',
                [$u['id']]
            )
            ->fetch('assoc');

        try {
            $filename = $this->saveResumeFile(
                $this->request
                    ->getData('resume_file'),
                (int)$u['id']
            );

            if (!$filename) {
                throw new \RuntimeException(
                    'Please select a PDF resume.'
                );
            }

            $conn->execute(
                'UPDATE students
                 SET
                    resume_path = ?,
                    resume = ?
                 WHERE user_id = ?',
                [
                    $filename,
                    $filename,
                    $u['id'],
                ]
            );

            if (!empty($old['resume_path'])) {
                $oldFile =
                    WWW_ROOT
                    . 'files'
                    . DS
                    . basename(
                        (string)$old['resume_path']
                    );

                if (is_file($oldFile)) {
                    @unlink($oldFile);
                }
            }

            $this->Flash->success(
                'Resume uploaded successfully.'
            );
        } catch (\Throwable $e) {
            $this->Flash->error(
                $e->getMessage()
            );
        }

        return $this->redirect([
            'action' => 'view',
        ]);
    }

    public function verify(
        ?int $id = null,
        ?string $token = null
    ) {
        if (!$id || !$token) {
            throw new \Cake\Http\Exception\NotFoundException(
                'Invalid verification link.'
            );
        }

        $conn = ConnectionManager::get('default');

        $student = $conn
            ->execute(
                'SELECT
                    s.id,
                    s.name,
                    s.matrix_number,
                    s.faculty,
                    s.course,
                    s.semester,
                    a.status,
                    a.start_date,
                    a.end_date,
                    COALESCE(
                        c.company_name,
                        a.company_name
                    ) AS display_company
                 FROM students s
                 LEFT JOIN applications a
                    ON a.id = (
                        SELECT a2.id
                        FROM applications a2
                        WHERE a2.student_id = s.id
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
                 WHERE s.id = ?
                 LIMIT 1',
                [$id]
            )
            ->fetch('assoc');

        if (!$student) {
            throw new \Cake\Http\Exception\NotFoundException(
                'Student record was not found.'
            );
        }

        $expectedToken =
            $this->generateStudentToken(
                (int)$student['id'],
                (string)$student['matrix_number']
            );

        if (
            !hash_equals(
                $expectedToken,
                $token
            )
        ) {
            throw new \Cake\Http\Exception\ForbiddenException(
                'Invalid verification token.'
            );
        }

        $isVerified = true;

        $this->set(compact(
            'student',
            'isVerified'
        ));
    }
}