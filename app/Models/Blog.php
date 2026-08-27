<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Blog extends Model
{
    protected static string $table = 'blogs';

    public static function latest(int $limit = 30): array
    {
        return static::where('status = ? AND published_at <= NOW()', ['published'], 'published_at DESC', $limit);
    }

    public static function publishedBySlug(string $slug): ?array
    {
        return static::first('slug = ? AND status = ?', [$slug, 'published']);
    }
}
