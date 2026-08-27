<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class News extends Model
{
    protected static string $table = 'news';

    public const CATEGORIES = ['news' => 'News', 'event' => 'Event', 'notice' => 'Notice'];

    public static function latest(int $limit = 3, ?string $category = null): array
    {
        if ($category !== null) {
            return static::where(
                'status = ? AND category = ? AND published_at <= NOW()',
                ['published', $category],
                'published_at DESC',
                $limit
            );
        }
        return static::where('status = ? AND published_at <= NOW()', ['published'], 'published_at DESC', $limit);
    }

    public static function publishedBySlug(string $slug): ?array
    {
        return static::first('slug = ? AND status = ?', [$slug, 'published']);
    }
}
