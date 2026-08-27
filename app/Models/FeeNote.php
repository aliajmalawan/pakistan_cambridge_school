<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class FeeNote extends Model
{
    protected static string $table = 'fee_notes';

    public const PANELS = [
        'concession' => 'Concessions',
        'payment'    => 'Payment',
    ];

    /** Published notes for one of the two boxes beside the fee table. */
    public static function panel(string $panel): array
    {
        return static::where(
            'panel = ? AND status = ?',
            [$panel, 'published'],
            'sort_order ASC, id ASC'
        );
    }
}
