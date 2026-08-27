<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Page extends Model
{
    protected static string $table = 'pages';

    /** Cache of system pages, keyed by route — one query per request at most. */
    private static ?array $systemCache = null;

    // ------------------------------------------------------------ content pages

    /** A hand-written page, looked up by slug. */
    public static function published(string $slug): ?array
    {
        return static::first('slug = ? AND status = ? AND page_type = ?', [$slug, 'published', 'content']);
    }

    /** @return array all hand-written pages, for the admin list and the page rail */
    public static function contentPages(string $orderBy = 'sort_order ASC, title ASC'): array
    {
        return static::where('page_type = ?', ['content'], $orderBy);
    }

    // ------------------------------------------------------------ system pages

    /**
     * Editable heading, lede, intro and SEO for a route-driven page.
     * Returns an empty-ish row rather than null so callers can always read it.
     *
     * @return array{title:string, lede:string, content:string, meta_description:string, image:?string}
     */
    public static function forRoute(string $route): array
    {
        if (self::$systemCache === null) {
            self::$systemCache = [];
            foreach (static::where('page_type = ?', ['system'], 'id ASC') as $row) {
                self::$systemCache[$row['route']] = $row;
            }
        }

        return self::$systemCache[$route] ?? [
            'title'            => '',
            'lede'             => '',
            'content'          => '',
            'meta_description' => '',
            'image'            => null,
        ];
    }

    /** @return array all route-driven pages, for the admin list */
    public static function systemPages(): array
    {
        return static::where('page_type = ?', ['system'], 'id ASC');
    }

    /**
     * The real route behind a system page's slug, e.g. "sys-academics" → "/academics".
     *
     * System pages are reached by route, not by /page/<slug>, but their slug is
     * visible in the admin and ends up in links. Resolving it here lets the
     * controller send such a link to the one canonical address.
     */
    public static function routeForSlug(string $slug): ?string
    {
        $row = static::first(
            'slug = ? AND status = ? AND page_type = ?',
            [$slug, 'published', 'system']
        );

        return ($row && $row['route'] !== '') ? $row['route'] : null;
    }

    public static function isSystem(array $page): bool
    {
        return ($page['page_type'] ?? 'content') === 'system';
    }

    /**
     * @deprecated Navigation is managed in menu_items (Admin → Menu Builder).
     *             Retained so an older schema import keeps working.
     */
    public static function menuPages(): array
    {
        return static::where('show_in_menu = 1 AND status = ?', ['published'], 'sort_order ASC');
    }
}
