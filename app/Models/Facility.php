<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Facility extends Model
{
    protected static string $table = 'facilities';

    /** Icon keys offered in the admin form — must exist in App\Core\Icon. */
    public const ICONS = [
        'flaskLab' => 'Laboratory',
        'book'     => 'Library',
        'cog'      => 'Computer lab',
        'mountain' => 'Sports ground',
        'bus'      => 'Transport',
        'shield'   => 'Security',
        'users'    => 'Common area',
        'sun'      => 'Outdoor space',
        'clock'    => 'Facility hours',
        'award'    => 'Hall / auditorium',
    ];

    public static function published(): array
    {
        return static::where('status = ?', ['published'], 'sort_order ASC, id ASC');
    }
}
