<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Faculty extends Model
{
    protected static string $table = 'faculty';

    public static function published(): array
    {
        return static::where('status = ?', ['published'], 'sort_order ASC');
    }
}
