<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class ClassSubject extends Model
{
    protected static string $table = 'class_subjects';

    public static function published(): array
    {
        return static::where('status = ?', ['published'], 'sort_order ASC, id ASC');
    }

    /** @return string[] */
    public static function list(string $subjects): array
    {
        return array_values(array_filter(array_map('trim', explode(',', $subjects))));
    }
}
