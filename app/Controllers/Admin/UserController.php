<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Session;
use App\Models\ActivityLog;
use App\Models\User;

final class UserController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        if (Auth::user()['role'] !== 'superadmin') {
            Session::flash('error', 'Only the super admin can manage admin users.');
            redirect('/admin');
        }
    }

    public function index(): void
    {
        $this->adminView('admin/users/index', [
            'pageTitle' => 'Admin Users',
            'users'     => User::all('name ASC'),
        ]);
    }

    public function create(): void
    {
        $this->adminView('admin/users/form', ['pageTitle' => 'Add Admin User', 'user' => null]);
    }

    public function store(): void
    {
        $data = $this->validated(true);
        if ($data === null) {
            redirect('/admin/users/create');
        }
        if (User::findBy('email', $data['email'])) {
            Session::flash('error', 'A user with that email already exists.');
            redirect('/admin/users/create');
        }
        User::create($data);
        ActivityLog::record('created', 'user', $data['name'] . ' (' . $data['role'] . ')');
        clear_old();
        Session::flash('success', 'Admin user created.');
        redirect('/admin/users');
    }

    public function edit(string $id): void
    {
        $user = User::find((int) $id);
        if (!$user) {
            Session::flash('error', 'User not found.');
            redirect('/admin/users');
        }
        $this->adminView('admin/users/form', ['pageTitle' => 'Edit Admin User', 'user' => $user]);
    }

    public function update(string $id): void
    {
        $user = User::find((int) $id);
        if (!$user) {
            redirect('/admin/users');
        }
        $data = $this->validated(false);
        if ($data === null) {
            redirect('/admin/users/edit/' . $id);
        }
        $existing = User::findBy('email', $data['email']);
        if ($existing && (int) $existing['id'] !== (int) $id) {
            Session::flash('error', 'Another user already uses that email.');
            redirect('/admin/users/edit/' . $id);
        }
        // Never let the superadmin lock themselves out
        if ((int) $id === Auth::id()) {
            $data['role'] = 'superadmin';
            $data['status'] = 'active';
        }
        if ($data['password'] === null) {
            unset($data['password']);
        }
        User::update((int) $id, $data);
        clear_old();
        Session::flash('success', 'User updated.');
        redirect('/admin/users');
    }

    public function destroy(string $id): void
    {
        if ((int) $id === Auth::id()) {
            Session::flash('error', 'You cannot delete your own account.');
            redirect('/admin/users');
        }
        if (User::find((int) $id)) {
            User::delete((int) $id);
            Session::flash('success', 'User deleted.');
        }
        redirect('/admin/users');
    }

    private function validated(bool $passwordRequired): ?array
    {
        $role = $this->input('role');
        $data = [
            'name'   => $this->input('name'),
            'email'  => $this->input('email'),
            'role'   => in_array($role, ['superadmin', 'admin', 'editor'], true) ? $role : 'editor',
            'status' => $this->input('status') === 'disabled' ? 'disabled' : 'active',
        ];
        $password = (string) ($_POST['password'] ?? '');

        $errors = [];
        if (mb_strlen($data['name']) < 3) {
            $errors[] = 'Name is required.';
        }
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'A valid email is required.';
        }
        if ($passwordRequired && strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters.';
        }
        if (!$passwordRequired && $password !== '' && strlen($password) < 8) {
            $errors[] = 'New password must be at least 8 characters (leave blank to keep the current one).';
        }
        if ($errors) {
            keep_old(['name' => $data['name'], 'email' => $data['email']]);
            Session::flash('error', implode(' ', $errors));
            return null;
        }
        $data['password'] = $password !== '' ? password_hash($password, PASSWORD_BCRYPT) : null;
        if ($passwordRequired && $data['password'] === null) {
            return null;
        }
        return $data;
    }
}
