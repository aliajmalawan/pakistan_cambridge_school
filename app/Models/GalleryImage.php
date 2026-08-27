<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use App\Core\Model;

final class GalleryImage extends Model
{
    protected static string $table = 'gallery_images';

    public static function forAlbum(int $albumId): array
    {
        return static::where('album_id = ?', [$albumId], 'sort_order ASC, id ASC');
    }

    /**
     * Every photo in a published album, carrying the album it belongs to so the
     * gallery can show one grid and filter it by category without a second query.
     */
    public static function allPublished(): array
    {
        return Database::query(
            'SELECT i.*, a.slug AS album_slug, a.title AS album_title
             FROM gallery_images i
             JOIN gallery_albums a ON a.id = i.album_id
             WHERE a.status = ?
             ORDER BY a.sort_order ASC, i.sort_order ASC, i.id ASC',
            ['published']
        )->fetchAll();
    }
}
