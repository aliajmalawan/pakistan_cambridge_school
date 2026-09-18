<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Session;
use App\Models\ActivityLog;
use App\Models\LoginAttempt;

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

        if ($email !== '' && LoginAttempt::isLocked($email)) {
            Session::flash('error', 'Too many failed sign-in attempts. Please wait '
                . LoginAttempt::minutesToWait() . ' minutes and try again.');
            keep_old(['email' => $email]);
            redirect('/admin/login');
        }

        if (Auth::attempt($email, $password)) {
            LoginAttempt::record($email, true);
            ActivityLog::record('login', 'auth', 'Signed in to the admin panel');
            redirect('/admin');
        }

        LoginAttempt::record($email, false);
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
