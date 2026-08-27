<?php

declare(strict_types=1);

namespace App\Core;

final class Csrf
{
    public static function token(): string
    {
        if (!Session::get('_csrf')) {
            Session::set('_csrf', bin2hex(random_bytes(32)));
        }
        return Session::get('_csrf');
    }

    public static function field(): string
    {
        return '<input type="hidden" name="_token" value="' . e(self::token()) . '">';
    }

    public static function verify(): void
    {
        $sent = $_POST['_token'] ?? '';
        if (!is_string($sent) || !hash_equals(self::token(), $sent)) {
            http_response_code(419);
            die('Invalid or expired form token. Go back, refresh the page, and try again.');
        }
    }
}
