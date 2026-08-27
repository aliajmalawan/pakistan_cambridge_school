<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class ContactMessage extends Model
{
    protected static string $table = 'contact_messages';

    public const EDITABLE = ['name', 'email', 'phone', 'subject', 'message'];

    public static function unreadCount(): int
    {
        return static::count('is_read = 0');
    }

    /** @return string[] human-readable errors, empty when the data is good */
    public static function validate(array $data): array
    {
        $errors = [];

        if (mb_strlen(trim($data['name'] ?? '')) < 2) {
            $errors[] = 'Please enter your name.';
        }
        $email = trim($data['email'] ?? '');
        $phone = trim($data['phone'] ?? '');
        if ($email === '' && $phone === '') {
            $errors[] = 'Please provide an email or phone number so we can reply.';
        }
        if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Please enter a valid email address.';
        }
        if (mb_strlen(trim($data['message'] ?? '')) < 10) {
            $errors[] = 'Please write a message of at least 10 characters.';
        }

        return $errors;
    }
}
