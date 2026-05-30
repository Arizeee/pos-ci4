<?php

namespace App\Controllers;

use App\Models\User;
use Config\Database;

class AuthController extends BaseController
{
    public function index()
    {
        return view('auth/login');
    }
 
    public function login()
{
    // Baca dari JSON body (karena frontend kirim JSON)
    $json = $this->request->getJSON(true); // true = as array

    $username = $json['username'] ?? '';
    $password = $json['password'] ?? '';

    if (empty($username) || empty($password)) {
        return $this->response
            ->setJSON(['success' => false, 'message' => 'Username dan password wajib diisi.'])
            ->setStatusCode(422);
    }

    $userModel = new User();

    // Pakai method findByUsernameWithRole yang sudah ada di model
    $user = $userModel->findByUsernameWithRole($username);

    if (!$user) {
        return $this->response
            ->setJSON(['success' => false, 'message' => 'Username tidak ditemukan.'])
            ->setStatusCode(404);
    }

    if (!password_verify($password, $user['password'])) {
        return $this->response
            ->setJSON(['success' => false, 'message' => 'Password salah.'])
            ->setStatusCode(422);
    }

    $roleName = $user['role_name'] ?? null;

    session()->set([
        'user_id'   => $user['id'],
        'username'  => $user['username'],
        'role'      => $roleName,
        'logged_in' => true,
    ]);

    // Update status online
    $userModel->update($user['id'], ['status' => 'online']);

    return $this->response->setJSON([
        'success' => true,
        'role'    => $roleName,
    ]);
}
 
    public function logout()
    {
        $db        = Database::connect();
        $userModel = new User();
        $userId    = session()->get('user_id');

        if ($userId && $db->fieldExists('status', 'users')) {
            $userModel->update($userId, ['status' => 'offline']);
        }

        session()->destroy();

        // GET → redirect langsung (dari browser atau filter)
        if ($this->request->getMethod() === 'get') {
            return redirect()->to('/auth');
        }

        // POST → return JSON (dari fetch JS)
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Logout berhasil',
        ]);
    }
}