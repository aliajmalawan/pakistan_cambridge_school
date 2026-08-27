<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use App\Core\Model;

final class MenuItem extends Model
{
    protected static string $table = 'menu_items';

    /** Built-in destinations an admin can point a menu item at. */
    public const ROUTES = [
        '/'           => 'Home',
        '/vision-mission' => 'Vision & Mission',
        '/leadership' => 'Leadership',
        '/academics'  => 'Academics',
        '/academic-calendar' => 'Academic Calendar',
        '/programs'   => 'Academic Programs',
        '/fees'       => 'Fee Structure',
        '/faculty'    => 'Faculty',
        '/news'       => 'News & Notices',
        '/gallery'    => 'Gallery',
        '/admissions' => 'Admissions',
        '/downloads'  => 'Downloads & Forms',
        '/contact'    => 'Contact',
        '/search'     => 'Search',
    ];

    public const LINK_TYPES = [
        'route'  => 'Built-in page',
        'page'   => 'CMS page',
        'custom' => 'Custom URL',
        'none'   => 'Dropdown heading (no link)',
    ];

    /** Rows of one menu joined to their page target, ordered for display. */
    public static function forMenu(int $menuId): array
    {
        return Database::query(
            "SELECT m.*, p.slug AS page_slug, p.title AS page_title, p.status AS page_status,
                    p.page_type AS page_kind, p.route AS page_route
             FROM menu_items m
             LEFT JOIN pages p ON p.id = m.page_id
             WHERE m.menu_id = ?
             ORDER BY m.sort_order ASC, m.id ASC",
            [$menuId]
        )->fetchAll();
    }

    /**
     * Depth-first flatten of one menu: each row gains 'depth' and 'href'.
     * Used by both the admin builder and the public renderers.
     *
     * @param bool $publishedOnly drop draft items and orphaned page links
     */
    public static function flatten(int $menuId, bool $publishedOnly = false): array
    {
        $rows = self::forMenu($menuId);

        $byParent = [];
        foreach ($rows as $row) {
            if ($publishedOnly) {
                if ($row['status'] !== 'published') {
                    continue;
                }
                // Never link to a page that was deleted or unpublished
                if ($row['link_type'] === 'page' && ($row['page_slug'] === null || $row['page_status'] !== 'published')) {
                    continue;
                }
            }
            $byParent[(int) ($row['parent_id'] ?? 0)][] = $row;
        }

        return self::walk($byParent, 0, 0);
    }

    /** @return array<int, array> */
    private static function walk(array $byParent, int $parent, int $depth): array
    {
        $out = [];
        foreach ($byParent[$parent] ?? [] as $row) {
            $row['depth'] = $depth;
            $row['href'] = self::href($row);
            $out[] = $row;
            $out = array_merge($out, self::walk($byParent, (int) $row['id'], $depth + 1));
        }
        return $out;
    }

    /**
     * Full recursive tree of published items — each row gains a 'children'
     * array. Used by the header, which renders dropdowns and fly-out submenus
     * to whatever depth the menu was built with.
     */
    public static function tree(int $menuId): array
    {
        $rows = self::forMenu($menuId);

        $byParent = [];
        foreach ($rows as $row) {
            if ($row['status'] !== 'published') {
                continue;
            }
            if ($row['link_type'] === 'page' && ($row['page_slug'] === null || $row['page_status'] !== 'published')) {
                continue;
            }
            $byParent[(int) ($row['parent_id'] ?? 0)][] = $row;
        }

        return self::nest($byParent, 0);
    }

    private static function nest(array $byParent, int $parent): array
    {
        $out = [];
        foreach ($byParent[$parent] ?? [] as $row) {
            $row['href'] = self::href($row);
            $row['children'] = self::nest($byParent, (int) $row['id']);
            $out[] = $row;
        }
        return $out;
    }

    /**
     * Two-level tree for the header navbar. Anything nested deeper than one
     * level is folded into its top-level parent's dropdown rather than lost.
     */
    public static function navTree(int $menuId): array
    {
        $flat = self::flatten($menuId, true);
        $tree = [];
        $currentTop = null;

        foreach ($flat as $row) {
            if ($row['depth'] === 0) {
                $row['children'] = [];
                $tree[] = $row;
                $currentTop = count($tree) - 1;
            } elseif ($currentTop !== null) {
                $tree[$currentTop]['children'][] = $row;
            }
        }
        return $tree;
    }

    /** Flat published list for footer columns (top level only). */
    public static function footerList(string $menuSlug): array
    {
        $menu = Menu::bySlug($menuSlug);
        if (!$menu) {
            return [];
        }
        return array_values(array_filter(
            self::flatten((int) $menu['id'], true),
            fn($row) => $row['depth'] === 0
        ));
    }

    /** Resolve a row to a final href. */
    public static function href(array $row): string
    {
        return match ($row['link_type']) {
            'page'   => self::pageHref($row),
            'custom' => $row['custom_url'] !== '' ? self::resolveCustom($row['custom_url']) : url('/'),
            'none'   => '#',
            default  => url($row['route'] !== '' ? $row['route'] : '/'),
        };
    }

    /**
     * A system page is served by its own route, not by /page/<slug>. An item
     * pointing at one must emit that route, or the link lands on a redirect at
     * best and a 404 at worst.
     */
    private static function pageHref(array $row): string
    {
        if (($row['page_kind'] ?? 'content') === 'system' && !empty($row['page_route'])) {
            return url($row['page_route']);
        }
        if (!empty($row['page_slug'])) {
            $cleanRoots = ['about', 'facilities', 'rules'];
            return in_array($row['page_slug'], $cleanRoots, true)
                ? url('/' . $row['page_slug'])
                : url('/page/' . $row['page_slug']);
        }
        return url('/');
    }

    /**
     * A custom URL is either somewhere else entirely, or a path on this site.
     * Site paths must go through url() — this install lives under
     * /kohsarschool, so emitting a bare "/vision-mission" would send the
     * browser to the domain root and 404.
     */
    private static function resolveCustom(string $value): string
    {
        $value = trim($value);

        // Already absolute, protocol-relative, an anchor, or another scheme
        if ($value === ''
            || str_starts_with($value, '//')
            || str_starts_with($value, '#')
            || preg_match('#^[a-z][a-z0-9+.-]*:#i', $value)) {
            return $value !== '' ? $value : url('/');
        }

        return url('/' . ltrim($value, '/'));
    }

    /** Human-readable destination for the builder list. */
    public static function describeTarget(array $row): string
    {
        return match ($row['link_type']) {
            'page'   => $row['page_title'] !== null ? '/page/' . $row['page_slug'] : 'page deleted',
            'custom' => $row['custom_url'],
            'none'   => 'no link',
            default  => $row['route'],
        };
    }

    /**
     * Persist a drag-and-drop arrangement.
     * Depth may only ever increase by one step at a time, so the resulting
     * parent chain is always valid no matter what the browser submitted.
     *
     * @param int[] $orderedIds ids in visual order
     * @param int[] $depths     matching indent level per id
     */
    public static function saveStructure(int $menuId, array $orderedIds, array $depths): void
    {
        if (!$orderedIds || count($orderedIds) !== count($depths)) {
            return;
        }

        // Only ids that really belong to this menu may be arranged. A stale
        // browser tab submitting a deleted id must not break the whole save,
        // and ids from another menu must never be pulled in.
        $owned = [];
        foreach (Database::query('SELECT id FROM menu_items WHERE menu_id = ?', [$menuId])->fetchAll() as $row) {
            $owned[(int) $row['id']] = true;
        }

        $pairs = [];
        foreach (array_values($orderedIds) as $i => $id) {
            $id = (int) $id;
            if (isset($owned[$id])) {
                $pairs[] = [$id, (int) ($depths[$i] ?? 0)];
            }
        }
        if (!$pairs) {
            return;
        }

        $stack = [];        // depth => id of the last row seen at that depth
        $previousDepth = 0;

        foreach ($pairs as $i => [$id, $requested]) {
            $depth = $i === 0 ? 0 : max(0, min($requested, $previousDepth + 1));
            $parent = $depth > 0 ? ($stack[$depth - 1] ?? null) : null;

            Database::query(
                'UPDATE menu_items SET sort_order = ?, parent_id = ? WHERE id = ? AND menu_id = ?',
                [$i + 1, $parent, $id, $menuId]
            );

            $stack[$depth] = $id;
            $previousDepth = $depth;
        }
    }

    public static function nextSortOrder(int $menuId): int
    {
        $row = Database::query(
            'SELECT COALESCE(MAX(sort_order), 0) m FROM menu_items WHERE menu_id = ?',
            [$menuId]
        )->fetch();
        return ((int) $row['m']) + 1;
    }
}
