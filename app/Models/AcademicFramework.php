<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class AcademicFramework extends Model
{
    protected static string $table = 'academic_framework';

    /** Icon keys offered in the admin form — must exist in App\Core\Icon. */
    public const ICONS = CoreValue::ICONS;

    public const ACCENTS = CoreValue::ACCENTS;

    public static function published(): array
    {
        return static::where('status = ?', ['published'], 'sort_order ASC, id ASC');
    }
}
