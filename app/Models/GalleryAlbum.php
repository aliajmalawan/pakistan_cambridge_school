<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use App\Core\Model;

final class GalleryAlbum extends Model
{
    protected static string $table = 'gallery_albums';

    public static function published(): array
    {
        return static::where('status = ?', ['published'], 'sort_order ASC');
    }

    public static function publishedBySlug(string $slug): ?array
    {
        return static::first('slug = ? AND status = ?', [$slug, 'published']);
    }

    public static function withCounts(): array
    {
        return Database::query(
            'SELECT a.*, COUNT(i.id) AS image_count
             FROM gallery_albums a
             LEFT JOIN gallery_images i ON i.album_id = a.id
             GROUP BY a.id
             ORDER BY a.sort_order ASC'
        )->fetchAll();
    }
}
