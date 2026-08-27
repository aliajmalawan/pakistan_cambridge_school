<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Leadership extends Model
{
    protected static string $table = 'leadership';

    public static function published(): array
    {
        return static::where('status = ?', ['published'], 'sort_order ASC, id ASC');
    }

    /** The member whose statement opens the page, if one is marked. */
    public static function featured(): ?array
    {
        return static::first("status = 'published' AND featured = 1 AND message != ''", [], 'sort_order ASC');
    }

    /**
     * Profiles that actually have something to say, for the About page's
     * message block. The featured profile leads; the rest follow in sort order.
     */
    public static function withMessages(int $limit = 2): array
    {
        return static::where(
            "status = 'published' AND message != ''",
            [],
            'featured DESC, sort_order ASC, id ASC',
            $limit
        );
    }

    /** Everyone else, for the grid below the opening statement. */
    public static function rest(?int $excludeId): array
    {
        if ($excludeId === null) {
            return self::published();
        }
        return static::where('status = ? AND id != ?', ['published', $excludeId], 'sort_order ASC, id ASC');
    }

    /**
     * Initials for the placeholder shown when there is no photograph.
     * Faculty needs the same thing, so the rule lives in one helper.
     */
    public static function initials(string $name): string
    {
        return initials($name);
    }
}
