<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use App\Core\Model;

final class DashboardPref extends Model
{
    protected static string $table = 'dashboard_prefs';

    /** All available dashboard widgets: key => label. */
    public const WIDGETS = [
        'stats'      => 'Stat cards (top row)',
        'attention'  => 'Needs attention',
        'trend'      => 'This week at a glance',
        'recent'     => 'Recent applications & messages',
        'content'    => 'Content summary',
        'activity'   => 'Activity feed',
        'quick'      => 'Quick actions',
    ];

    public const DEFAULT = 'stats,attention,trend,recent,content,activity,quick';

    /** @return string[] visible widget keys for a user, in order */
    public static function forUser(int $userId): array
    {
        $row = static::findBy('user_id', $userId);
        $raw = $row['widgets'] ?? self::DEFAULT;
        $keys = array_values(array_filter(
            array_map('trim', explode(',', $raw)),
            fn($k) => isset(self::WIDGETS[$k])
        ));
        return $keys ?: array_keys(self::WIDGETS);
    }

    public static function save(int $userId, array $keys): void
    {
        $keys = array_values(array_filter($keys, fn($k) => isset(self::WIDGETS[$k])));
        $value = implode(',', $keys ?: array_keys(self::WIDGETS));
        Database::query(
            'INSERT INTO dashboard_prefs (user_id, widgets) VALUES (?, ?)
             ON DUPLICATE KEY UPDATE widgets = VALUES(widgets)',
            [$userId, $value]
        );
    }
}
