<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

/**
 * Brute-force guard for /admin/login. Locks out an email after too many
 * failed attempts in a short window — the account, not the attacker's IP,
 * since that is what a credential-stuffing attempt actually targets.
 */
final class LoginAttempt extends Model
{
    protected static string $table = 'login_attempts';

    private const MAX_FAILURES = 5;
    private const WINDOW_MINUTES = 15;

    public static function record(string $email, bool $success): void
    {
        static::create([
            'email'   => mb_substr(strtolower(trim($email)), 0, 160),
            'ip_hash' => hash('sha256', ($_SERVER['REMOTE_ADDR'] ?? '') . '|' . BASE_URL),
            'success' => $success ? 1 : 0,
        ]);
    }

    /** True when this email has too many recent failures to try again yet. */
    public static function isLocked(string $email): bool
    {
        return self::recentFailures($email) >= self::MAX_FAILURES;
    }

    public static function recentFailures(string $email): int
    {
        return static::count(
            'email = ? AND success = 0 AND created_at >= (NOW() - INTERVAL ' . self::WINDOW_MINUTES . ' MINUTE)',
            [mb_substr(strtolower(trim($email)), 0, 160)]
        );
    }

    public static function minutesToWait(): int
    {
        return self::WINDOW_MINUTES;
    }
}
