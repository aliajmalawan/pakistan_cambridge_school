<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Auth;
use App\Core\Model;

final class ActivityLog extends Model
{
    protected static string $table = 'activity_log';

    /** Icons/colors per subject type for the feed UI. */
    public const SUBJECT_META = [
        'admission' => ['📝', 'info'],
        'message'   => ['✉', 'info'],
        'news'      => ['📰', 'success'],
        'blog'      => ['✍', 'success'],
        'page'      => ['📄', 'success'],
        'slider'    => ['🖼', 'success'],
        'program'   => ['🎓', 'success'],
        'faculty'   => ['👥', 'success'],
        'testimonial' => ['💬', 'success'],
        'gallery'   => ['📷', 'success'],
        'settings'  => ['⚙', 'warning'],
        'user'      => ['🔐', 'warning'],
        'auth'      => ['→', 'warning'],
        'system'    => ['◆', 'info'],
    ];

    public static function record(string $action, string $subjectType, string $subjectLabel, ?string $actorOverride = null): void
    {
        $user = Auth::user();
        static::create([
            'user_id'       => $user['id'] ?? null,
            'actor'         => $actorOverride ?? ($user['name'] ?? 'Website Visitor'),
            'action'        => $action,
            'subject_type'  => $subjectType,
            'subject_label' => mb_substr($subjectLabel, 0, 220),
        ]);
    }

    public static function recent(int $limit = 12): array
    {
        return static::where('1', [], 'created_at DESC', $limit);
    }
}
