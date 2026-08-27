<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class CoreValue extends Model
{
    protected static string $table = 'core_values';

    /** Icon keys offered in the admin form — must exist in App\Core\Icon. */
    public const ICONS = [
        'shield'   => 'Shield',
        'mountain' => 'Mountain',
        'book'     => 'Open book',
        'ribbon'   => 'Ribbon',
        'award'    => 'Award',
        'users'    => 'People',
        'flaskLab' => 'Laboratory',
        'message'  => 'Message',
        'clock'    => 'Clock',
        'sun'      => 'Sun',
    ];

    public const ACCENTS = [
        'accent-blue'   => 'Blue',
        'accent-orange' => 'Orange',
        'accent-teal'   => 'Teal',
        'accent-violet' => 'Violet',
        'accent-rose'   => 'Rose',
        'accent-amber'  => 'Amber',
    ];

    public static function published(): array
    {
        return static::where('status = ?', ['published'], 'sort_order ASC, id ASC');
    }
}
