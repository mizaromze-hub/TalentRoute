<?php
declare(strict_types=1);
namespace App\Controller;
use Cake\Datasource\ConnectionManager;

class UsersController extends AppController
{
    public function login()
    {
        $this->viewBuilder()->setLayout('ajax');
        if ($this->request->is('post')) {
            $email = trim((string)$this->request->getData('email'));
            $password = (string)$this->request->getData('password');
            $conn = ConnectionManager::get('default');
            $user = $conn->execute('SELECT * FROM users WHERE email = ?', [$email])->fetch('assoc');
            $valid = false;
            if ($user) {
                $stored = (string)$user['password'];
                $valid = password_verify($password, $stored) || hash_equals($stored, $password);
            }
            if ($valid) {
                $name = 'Administrator';
                if ($user['role'] === 'student') {
                    $profile = $conn->execute('SELECT name FROM students WHERE user_id = ?', [$user['id']])->fetch('assoc');
                    $name = $profile['name'] ?? 'Student';
                } elseif ($user['role'] === 'company') {
                    $profile = $conn->execute('SELECT company_name FROM companies WHERE user_id = ?', [$user['id']])->fetch('assoc');
                    $name = $profile['company_name'] ?? 'Company';
                }
                $this->request->getSession()->write('Auth.User', [
                    'id'=>(int)$user['id'], 'email'=>$user['email'], 'role'=>$user['role'], 'name'=>$name
                ]);
                return $this->redirect(['controller'=>'Dashboard','action'=>'index']);
            }
            $this->Flash->error('Email atau kata laluan salah.');
        }
    }

    public function logout()
    {
        $this->request->getSession()->delete('Auth.User');
        $this->Flash->success('Anda telah log keluar.');
        return $this->redirect(['action'=>'login']);
    }

    public function index()
    {
        $u=$this->request->getSession()->read('Auth.User');
        if (!$u || $u['role']!=='admin') return $this->redirect(['controller'=>'Dashboard','action'=>'index']);
        $conn=ConnectionManager::get('default');
        $users=$conn->execute('SELECT id,email,role,created FROM users ORDER BY id DESC')->fetchAll('assoc');
        $this->set(compact('users','u'));
    }
}
