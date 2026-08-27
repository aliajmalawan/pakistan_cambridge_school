<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Session;
use App\Models\ActivityLog;

final class AuthController extends Controller
{
    public function showLogin(): void
    {
        if (Auth::check()) {
            redirect('/admin');
        }
        $this->view('admin/auth/login', ['pageTitle' => 'Admin Login — ' . setting('site_name')], null);
    }

    public function login(): void
    {
        Csrf::verify();
        $email = $this->input('email');
        $password = $this->input('password');

        if (Auth::attempt($email, $password)) {
            ActivityLog::record('login', 'auth', 'Signed in to the admin panel');
            redirect('/admin');
        }

        Session::flash('error', 'Invalid email or password, or the account is disabled.');
        keep_old(['email' => $email]);
        redirect('/admin/login');
    }

    public function logout(): void
    {
        Csrf::verify();
        Auth::logout();
        redirect('/admin/login');
    }
}
