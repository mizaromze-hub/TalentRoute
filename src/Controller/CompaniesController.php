<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Datasource\ConnectionManager;

class CompaniesController extends AppController
{
    private function currentUser(): ?array
    {
        $user = $this->request->getSession()->read('Auth.User');
        return is_array($user) ? $user : null;
    }

    public function index()
    {
        $user = $this->currentUser();
        if (!$user) {
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        $role = strtolower((string)($user['role'] ?? ''));
        if (!in_array($role, ['admin', 'company'], true)) {
            $this->Flash->error('You are not authorised to view companies.');
            return $this->redirect(['controller' => 'Dashboard', 'action' => 'index']);
        }

        $keyword = trim((string)$this->request->getQuery('keyword'));
        $connection = ConnectionManager::get('default');

        $sql = 'SELECT c.*, u.email
                FROM companies c
                INNER JOIN users u ON u.id = c.user_id';
        $params = [];
        $conditions = [];

        if ($role === 'company') {
            $conditions[] = 'c.user_id = ?';
            $params[] = (int)$user['id'];
        }

        if ($keyword !== '') {
            $value = '%' . $keyword . '%';
            $conditions[] = '(c.company_name LIKE ?
                OR c.registration_number LIKE ?
                OR c.industry LIKE ?
                OR c.contact_person LIKE ?
                OR c.city LIKE ?
                OR c.state LIKE ?
                OR u.email LIKE ?)';
            array_push($params, $value, $value, $value, $value, $value, $value, $value);
        }

        if ($conditions) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }

        $sql .= ' ORDER BY c.company_name ASC';
        $companies = $connection->execute($sql, $params)->fetchAll('assoc');

        $this->set(compact('companies', 'user', 'role', 'keyword'));
    }

    public function view(?int $id = null)
    {
        $user = $this->currentUser();
        if (!$user) {
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        $role = strtolower((string)($user['role'] ?? ''));
        if (!in_array($role, ['admin', 'company'], true)) {
            $this->Flash->error('You are not authorised to view this company profile.');
            return $this->redirect(['controller' => 'Dashboard', 'action' => 'index']);
        }

        $connection = ConnectionManager::get('default');

        if ($role === 'company') {
            $company = $connection->execute(
                'SELECT c.*, u.email
                 FROM companies c
                 INNER JOIN users u ON u.id = c.user_id
                 WHERE c.user_id = ?
                 LIMIT 1',
                [(int)$user['id']]
            )->fetch('assoc');
        } else {
            if (!$id) {
                $this->Flash->error('Please select a company.');
                return $this->redirect(['action' => 'index']);
            }

            $company = $connection->execute(
                'SELECT c.*, u.email
                 FROM companies c
                 INNER JOIN users u ON u.id = c.user_id
                 WHERE c.id = ?
                 LIMIT 1',
                [$id]
            )->fetch('assoc');
        }

        if (!$company) {
            $this->Flash->error('Company profile was not found.');
            return $this->redirect(['action' => 'index']);
        }

        $this->set(compact('company', 'user', 'role'));
    }

    public function add()
    {
        $user = $this->currentUser();
        if (!$user || strtolower((string)($user['role'] ?? '')) !== 'admin') {
            $this->Flash->error('Only admin can add a company.');
            return $this->redirect(['controller' => 'Dashboard', 'action' => 'index']);
        }

        if (!$this->request->is('post')) {
            $this->set(compact('user'));
            return;
        }

        $data = $this->request->getData();
        $companyName = trim((string)($data['company_name'] ?? ''));
        $registrationNumber = trim((string)($data['registration_number'] ?? ''));
        $industry = trim((string)($data['industry'] ?? ''));
        $email = strtolower(trim((string)($data['email'] ?? '')));
        $password = (string)($data['password'] ?? '');
        $contactPerson = trim((string)($data['contact_person'] ?? ''));
        $phoneNumber = preg_replace('/\D+/', '', (string)($data['phone_number'] ?? ''));
        $addressLine1 = trim((string)($data['address_line1'] ?? ''));
        $addressLine2 = trim((string)($data['address_line2'] ?? ''));
        $postcode = preg_replace('/\D+/', '', (string)($data['postcode'] ?? ''));
        $city = trim((string)($data['city'] ?? ''));
        $state = trim((string)($data['state'] ?? ''));

        if ($companyName === '' || $industry === '' || $contactPerson === '' || $addressLine1 === '' || $city === '' || $state === '') {
            $this->Flash->error('Please complete all required fields.');
            $this->set(compact('user'));
            return;
        }
        if (!preg_match('/^\d{12}$/', $registrationNumber)) {
            $this->Flash->error('SSM registration number must contain exactly 12 digits.');
            $this->set(compact('user'));
            return;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->Flash->error('Please enter a valid company email.');
            $this->set(compact('user'));
            return;
        }
        if (strlen($password) < 8) {
            $this->Flash->error('Password must contain at least 8 characters.');
            $this->set(compact('user'));
            return;
        }
        if (!preg_match('/^\d{10,11}$/', $phoneNumber)) {
            $this->Flash->error('Phone number must contain 10 or 11 digits.');
            $this->set(compact('user'));
            return;
        }
        if (!preg_match('/^\d{5}$/', $postcode)) {
            $this->Flash->error('Postcode must contain exactly 5 digits.');
            $this->set(compact('user'));
            return;
        }

        $connection = ConnectionManager::get('default');
        if ($connection->execute('SELECT id FROM users WHERE LOWER(email) = LOWER(?) LIMIT 1', [$email])->fetch('assoc')) {
            $this->Flash->error('This email is already registered.');
            $this->set(compact('user'));
            return;
        }
        if ($connection->execute('SELECT id FROM companies WHERE registration_number = ? LIMIT 1', [$registrationNumber])->fetch('assoc')) {
            $this->Flash->error('This SSM registration number is already registered.');
            $this->set(compact('user'));
            return;
        }

        $connection->begin();
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            if ($hashedPassword === false) {
                throw new \RuntimeException('Password could not be secured.');
            }
            $connection->execute(
                'INSERT INTO users (email, password, role, created, modified)
                 VALUES (?, ?, ?, NOW(), NOW())',
                [$email, $hashedPassword, 'company']
            );
            $userId = (int)$connection->execute('SELECT LAST_INSERT_ID() AS id')->fetch('assoc')['id'];

            $connection->execute(
                'INSERT INTO companies
                (user_id, company_name, registration_number, industry, address_line1, address_line2, postcode, city, state, contact_person, phone_number)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                [
                    $userId, $companyName, $registrationNumber, $industry,
                    $addressLine1, $addressLine2 !== '' ? $addressLine2 : null,
                    $postcode, $city, $state, $contactPerson, $phoneNumber,
                ]
            );

            $connection->commit();
            $this->Flash->success('Company account created successfully.');
            return $this->redirect(['action' => 'index']);
        } catch (\Throwable $e) {
            $connection->rollback();
            $this->Flash->error('Company registration failed: ' . $e->getMessage());
            $this->set(compact('user'));
        }
    }

    public function edit(?int $id = null)
    {
        $user = $this->currentUser();
        if (!$user) {
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        $role = strtolower((string)($user['role'] ?? ''));
        if (!in_array($role, ['admin', 'company'], true)) {
            return $this->redirect(['controller' => 'Dashboard', 'action' => 'index']);
        }

        $connection = ConnectionManager::get('default');
        if ($role === 'company') {
            $company = $connection->execute(
                'SELECT c.*, u.email FROM companies c INNER JOIN users u ON u.id = c.user_id WHERE c.user_id = ? LIMIT 1',
                [(int)$user['id']]
            )->fetch('assoc');
            $id = (int)($company['id'] ?? 0);
        } else {
            $company = $connection->execute(
                'SELECT c.*, u.email FROM companies c INNER JOIN users u ON u.id = c.user_id WHERE c.id = ? LIMIT 1',
                [$id]
            )->fetch('assoc');
        }

        if (!$company || !$id) {
            $this->Flash->error('Company profile was not found.');
            return $this->redirect(['action' => 'index']);
        }

        if ($this->request->is(['post', 'put', 'patch'])) {
            $data = $this->request->getData();
            $companyName = trim((string)($data['company_name'] ?? ''));
            $registrationNumber = trim((string)($data['registration_number'] ?? ''));
            $industry = trim((string)($data['industry'] ?? ''));
            $email = strtolower(trim((string)($data['email'] ?? '')));
            $contactPerson = trim((string)($data['contact_person'] ?? ''));
            $phoneNumber = preg_replace('/\D+/', '', (string)($data['phone_number'] ?? ''));
            $addressLine1 = trim((string)($data['address_line1'] ?? ''));
            $addressLine2 = trim((string)($data['address_line2'] ?? ''));
            $postcode = preg_replace('/\D+/', '', (string)($data['postcode'] ?? ''));
            $city = trim((string)($data['city'] ?? ''));
            $state = trim((string)($data['state'] ?? ''));

            if ($companyName === '' || $industry === '' || $contactPerson === '' || $addressLine1 === '' || $city === '' || $state === '') {
                $this->Flash->error('Please complete all required fields.');
            } elseif (!preg_match('/^\d{12}$/', $registrationNumber)) {
                $this->Flash->error('SSM registration number must contain exactly 12 digits.');
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->Flash->error('Please enter a valid company email.');
            } elseif (!preg_match('/^\d{10,11}$/', $phoneNumber)) {
                $this->Flash->error('Phone number must contain 10 or 11 digits.');
            } elseif (!preg_match('/^\d{5}$/', $postcode)) {
                $this->Flash->error('Postcode must contain exactly 5 digits.');
            } elseif ($connection->execute('SELECT id FROM users WHERE LOWER(email)=LOWER(?) AND id<>? LIMIT 1', [$email, $company['user_id']])->fetch('assoc')) {
                $this->Flash->error('This email is already used by another account.');
            } elseif ($connection->execute('SELECT id FROM companies WHERE registration_number=? AND id<>? LIMIT 1', [$registrationNumber, $id])->fetch('assoc')) {
                $this->Flash->error('This SSM registration number is already used.');
            } else {
                $connection->begin();
                try {
                    $connection->execute(
                        'UPDATE companies SET company_name=?, registration_number=?, industry=?, address_line1=?, address_line2=?, postcode=?, city=?, state=?, contact_person=?, phone_number=? WHERE id=?',
                        [
                            $companyName, $registrationNumber, $industry, $addressLine1,
                            $addressLine2 !== '' ? $addressLine2 : null,
                            $postcode, $city, $state, $contactPerson, $phoneNumber, $id,
                        ]
                    );
                    $connection->execute('UPDATE users SET email=?, modified=NOW() WHERE id=?', [$email, $company['user_id']]);
                    $connection->commit();
                    $this->Flash->success('Company profile updated successfully.');
                    return $this->redirect(
                        $role === 'company'
                            ? ['action' => 'view']
                            : ['action' => 'view', $id]
                    );
                } catch (\Throwable $e) {
                    $connection->rollback();
                    $this->Flash->error($e->getMessage());
                }
            }

            $company = array_merge($company, $data);
        }

        $this->set(compact('company', 'user', 'role'));
    }
}
