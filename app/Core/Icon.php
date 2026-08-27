<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Inline SVG icon set. Line style, 24×24 grid, 1.6 stroke — the icon rules in
 * docs/brand/DESIGN_SYSTEM.md §5.1. Inlined rather than loaded as a sprite so
 * icons cost nothing extra and inherit currentColor.
 */
final class Icon
{
    private const PATHS = [
        // Brand glyphs
        'shield'   => '<path d="M12 3l8 3v5c0 5-3.5 8-8 10-4.5-2-8-5-8-10V6z"/>',
        'home'     => '<path d="M3 11.5 12 4l9 7.5"/><path d="M5.5 10v9a1 1 0 001 1H9v-5a1 1 0 011-1h4a1 1 0 011 1v5h2.5a1 1 0 001-1v-9"/>',
        'mountain' => '<path d="M2 20l6-9 4 5 3-4 7 8z"/>',
        'book'     => '<path d="M4 4h9a3 3 0 013 3v13a3 3 0 00-3-3H4z"/><path d="M20 4h-9a3 3 0 00-3 3v13a3 3 0 013-3h9z"/>',
        'ribbon'   => '<path d="M9 21h6M12 17v4M7 3h10l-1 8a4 4 0 01-8 0z"/>',

        // Programme icons (values stored in programs.icon)
        'sun'    => '<circle cx="12" cy="12" r="4"/><path d="M12 2v2m0 16v2M2 12h2m16 0h2M4.9 4.9l1.4 1.4m11.4 11.4 1.4 1.4m0-14.2-1.4 1.4M6.3 17.7l-1.4 1.4"/>',
        'pencil' => '<path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 013 3L7 19l-4 1 1-4z"/>',
        'flask'  => '<path d="M9 3h6M10 3v6L4.5 18a2 2 0 001.7 3h11.6a2 2 0 001.7-3L14 9V3"/><path d="M7 15h10"/>',
        'heart'  => '<path d="M20.8 5.6a5 5 0 00-7.1 0L12 7.3l-1.7-1.7a5 5 0 00-7.1 7.1l8.8 8.8 8.8-8.8a5 5 0 000-7.1z"/>',
        'cog'    => '<circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 00.3 1.9l.1.1a2 2 0 11-2.8 2.8l-.1-.1a1.7 1.7 0 00-1.9-.3 1.7 1.7 0 00-1 1.5V21a2 2 0 11-4 0v-.1A1.7 1.7 0 007.9 19a1.7 1.7 0 00-1.9.3l-.1.1a2 2 0 11-2.8-2.8l.1-.1a1.7 1.7 0 00.3-1.9 1.7 1.7 0 00-1.5-1H2a2 2 0 110-4h.1A1.7 1.7 0 003.6 8.4a1.7 1.7 0 00-.3-1.9l-.1-.1a2 2 0 112.8-2.8l.1.1a1.7 1.7 0 001.9.3H8a1.7 1.7 0 001-1.5V2a2 2 0 114 0v.1a1.7 1.7 0 001 1.5 1.7 1.7 0 001.9-.3l.1-.1a2 2 0 112.8 2.8l-.1.1a1.7 1.7 0 00-.3 1.9V8a1.7 1.7 0 001.5 1H22a2 2 0 110 4h-.1a1.7 1.7 0 00-1.5 1z"/>',

        // Feature strip
        'award'    => '<circle cx="12" cy="8" r="6"/><path d="M8.2 13.9 7 22l5-3 5 3-1.2-8.1"/>',
        'users'    => '<path d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 00-3-3.9"/><path d="M16 3.1a4 4 0 010 7.8"/>',
        'flaskLab' => '<path d="M9 3h6M10 3v6L4.5 18a2 2 0 001.7 3h11.6a2 2 0 001.7-3L14 9V3"/>',
        'bus'      => '<path d="M4 17V6a2 2 0 012-2h12a2 2 0 012 2v11"/><path d="M4 11h16M7 17v2m10-2v2M2 17h20"/><circle cx="8" cy="14.5" r="1"/><circle cx="16" cy="14.5" r="1"/>',
        'clock'    => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
        'message'  => '<path d="M21 11.5a8.4 8.4 0 01-9 8.4 8.5 8.5 0 01-3.8-.9L3 21l1.9-5.2A8.4 8.4 0 0112 3a8.4 8.4 0 019 8.5z"/>',
        'arrow'    => '<path d="M5 12h14M13 6l6 6-6 6"/>',
        'check'    => '<path d="M20 6 9 17l-5-5"/>',
        'phone'    => '<path d="M22 16.9v3a2 2 0 01-2.2 2 19.8 19.8 0 01-8.6-3.1 19.5 19.5 0 01-6-6A19.8 19.8 0 012.1 4.2 2 2 0 014.1 2h3a2 2 0 012 1.7c.1 1 .4 1.9.7 2.8a2 2 0 01-.5 2.1L8.1 9.9a16 16 0 006 6l1.3-1.2a2 2 0 012.1-.5c.9.3 1.8.6 2.8.7a2 2 0 011.7 2z"/>',
        'mail'     => '<path d="M4 4h16a2 2 0 012 2v12a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2z"/><path d="m22 7-10 6L2 7"/>',
        'pin'      => '<path d="M21 10c0 6-9 12-9 12s-9-6-9-12a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/>',
        'chat'     => '<path d="M21 15a2 2 0 01-2 2H8l-5 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>',
        'user'     => '<path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>',
        'send'     => '<path d="M22 2 11 13"/><path d="M22 2 15 22l-4-9-9-4z"/>',
        'search'   => '<circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/>',
        'monitor'    => '<rect x="2" y="4" width="20" height="14" rx="2"/><path d="M8 21h8M12 18v3"/>',
        'chart'      => '<path d="M3 3v18h18"/><rect x="7" y="12" width="3" height="6"/><rect x="12" y="8" width="3" height="10"/><rect x="17" y="5" width="3" height="13"/>',
        'calendar'   => '<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>',
        'clipboard'  => '<rect x="6" y="4" width="12" height="18" rx="2"/><path d="M9 4V3a1 1 0 011-1h4a1 1 0 011 1v1"/><path d="M9 11h6M9 15h6"/>',
        'wallet'     => '<path d="M3 7a2 2 0 012-2h13a1 1 0 011 1v3"/><path d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2v-7a2 2 0 00-2-2H5a2 2 0 01-2-2z"/><circle cx="17" cy="14" r="1.5"/>',
        'smartphone' => '<rect x="6" y="2" width="12" height="20" rx="2"/><path d="M11 18h2"/>',
        'userCheck'  => '<path d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="m17 11 2 2 4-4"/>',
    ];

    public static function svg(string $name, int $size = 24, string $classes = ''): string
    {
        $path = self::PATHS[$name] ?? self::PATHS['book'];
        return '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none"'
            . ' stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"'
            . ($classes !== '' ? ' class="' . e($classes) . '"' : '')
            . ' aria-hidden="true" focusable="false">' . $path . '</svg>';
    }

    public static function has(string $name): bool
    {
        return isset(self::PATHS[$name]);
    }
}
