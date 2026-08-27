<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use App\Core\Model;

final class Menu extends Model
{
    protected static string $table = 'menus';

    /** The header menu is structural — the site navigation depends on it. */
    public const PROTECTED_SLUG = 'main';

    public static function allMenus(): array
    {
        return static::all('id ASC');
    }

    public static function bySlug(string $slug): ?array
    {
        return static::findBy('slug', $slug);
    }

    public static function withCounts(): array
    {
        return Database::query(
            'SELECT m.*, COUNT(i.id) AS item_count
             FROM menus m
             LEFT JOIN menu_items i ON i.menu_id = m.id
             GROUP BY m.id
             ORDER BY m.id ASC'
        )->fetchAll();
    }

    /** Unique slug from a name, e.g. "Footer Legal" -> footer-legal-2 if taken. */
    public static function uniqueSlug(string $name): string
    {
        $base = slugify($name);
        $slug = $base;
        $suffix = 1;
        while (static::findBy('slug', $slug)) {
            $slug = $base . '-' . (++$suffix);
        }
        return $slug;
    }

    public static function isProtected(array $menu): bool
    {
        return $menu['slug'] === self::PROTECTED_SLUG;
    }
}
