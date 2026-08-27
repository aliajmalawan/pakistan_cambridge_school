<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use App\Core\Model;

final class Setting extends Model
{
    protected static string $table = 'settings';

    private static ?array $cache = null;

    public static function allAsMap(): array
    {
        if (self::$cache === null) {
            self::$cache = [];
            foreach (Database::query('SELECT skey, svalue FROM settings')->fetchAll() as $row) {
                self::$cache[$row['skey']] = $row['svalue'];
            }
        }
        return self::$cache;
    }

    /** An unset row and a row saved blank both mean "nothing configured" — both fall back to $default. */
    public static function value(string $key, string $default = ''): string
    {
        $value = self::allAsMap()[$key] ?? '';
        return $value !== '' ? $value : $default;
    }

    public static function put(string $key, string $value): void
    {
        Database::query(
            'INSERT INTO settings (skey, svalue) VALUES (?, ?) ON DUPLICATE KEY UPDATE svalue = VALUES(svalue)',
            [$key, $value]
        );
        self::$cache = null;
    }
}
