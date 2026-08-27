<?php

declare(strict_types=1);

namespace App\Core;

use App\Models\User;

final class Auth
{
    public static function attempt(string $email, string $password): bool
    {
        $user = User::findBy('email', $email);
        if ($user && $user['status'] === 'active' && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            Session::set('auth_user_id', (int) $user['id']);
            User::update((int) $user['id'], ['last_login_at' => date('Y-m-d H:i:s')]);
            return true;
        }
        return false;
    }

    public static function user(): ?array
    {
        static $cached = false;
        static $user = null;
        if ($cached === false) {
            $id = Session::get('auth_user_id');
            $user = $id ? User::find((int) $id) : null;
            $cached = true;
        }
        return $user;
    }

    public static function check(): bool
    {
        return self::user() !== null;
    }

    public static function id(): ?int
    {
        $u = self::user();
        return $u ? (int) $u['id'] : null;
    }

    public static function logout(): void
    {
        Session::destroy();
    }

    public static function requireAdmin(): void
    {
        if (!self::check()) {
            Session::flash('error', 'Please sign in to continue.');
            redirect('/admin/login');
        }
    }

    public static function requireRole(string $role): void
    {
        self::requireAdmin();
        if (self::user()['role'] !== $role) {
            Session::flash('error', 'You do not have permission to access that area.');
            redirect('/admin');
        }
    }
}
