<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use App\Core\Model;

final class Download extends Model
{
    protected static string $table = 'downloads';

    public const CATEGORIES = [
        'admissions' => 'Admissions',
        'academics'  => 'Academics',
        'forms'      => 'Forms',
        'results'    => 'Results',
        'general'    => 'General',
    ];

    public static function published(): array
    {
        return static::where('status = ?', ['published'], 'sort_order ASC, id ASC');
    }

    /** @return array<string, array> published items keyed by category label */
    public static function grouped(): array
    {
        $out = [];
        foreach (self::published() as $item) {
            $out[self::CATEGORIES[$item['category']] ?? 'General'][] = $item;
        }
        return $out;
    }

    public static function registerHit(int $id): void
    {
        Database::query('UPDATE downloads SET download_count = download_count + 1 WHERE id = ?', [$id]);
    }

    public static function humanSize(int $bytes): string
    {
        if ($bytes <= 0) {
            return '';
        }
        $units = ['B', 'KB', 'MB', 'GB'];
        $power = min((int) floor(log($bytes, 1024)), count($units) - 1);
        return round($bytes / (1024 ** $power), $power === 0 ? 0 : 1) . ' ' . $units[$power];
    }
}
