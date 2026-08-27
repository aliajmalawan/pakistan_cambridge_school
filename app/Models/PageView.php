<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use App\Core\Model;

final class PageView extends Model
{
    protected static string $table = 'page_views';

    /** Substrings that identify a crawler rather than a reader. */
    private const BOT_SIGNATURES = [
        'bot', 'crawl', 'spider', 'slurp', 'curl', 'wget', 'python-requests',
        'headless', 'lighthouse', 'pingdom', 'monitor', 'preview', 'fetch',
    ];

    /**
     * Record one public page view. Silently does nothing for admin routes,
     * assets, non-GET requests and known crawlers.
     */
    public static function record(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'GET') {
            return;
        }

        $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
        $base = parse_url(BASE_URL, PHP_URL_PATH) ?? '';
        if ($base !== '' && str_starts_with($path, $base)) {
            $path = substr($path, strlen($base));
        }
        $path = '/' . trim($path, '/');

        if (str_starts_with($path, '/admin')
            || str_starts_with($path, '/assets')
            || str_starts_with($path, '/uploads')
            || preg_match('/\.(css|js|png|jpe?g|webp|gif|svg|ico|woff2?|xml|txt)$/i', $path)) {
            return;
        }

        $agent = strtolower((string) ($_SERVER['HTTP_USER_AGENT'] ?? ''));
        if ($agent === '') {
            return;
        }
        foreach (self::BOT_SIGNATURES as $signature) {
            if (str_contains($agent, $signature)) {
                return;
            }
        }

        try {
            static::create([
                'path'         => mb_substr($path, 0, 255),
                'visitor_hash' => self::visitorHash(),
                'referrer'     => mb_substr(self::externalReferrer(), 0, 255),
            ]);
        } catch (\Throwable) {
            // Analytics must never take the site down.
        }
    }

    /**
     * Stable per-visitor fingerprint that cannot be reversed to an IP.
     * Salted with the install's own base URL so hashes are not portable.
     */
    private static function visitorHash(): string
    {
        $ip = (string) ($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0');
        $agent = (string) ($_SERVER['HTTP_USER_AGENT'] ?? '');
        return hash('sha256', $ip . '|' . $agent . '|' . BASE_URL);
    }

    /** Referrers from our own host are noise; only outside sources are useful. */
    private static function externalReferrer(): string
    {
        $referrer = (string) ($_SERVER['HTTP_REFERER'] ?? '');
        if ($referrer === '') {
            return '';
        }
        $host = parse_url($referrer, PHP_URL_HOST);
        return $host && $host === parse_url(BASE_URL, PHP_URL_HOST) ? '' : $referrer;
    }

    // ------------------------------------------------------------ reporting

    public static function viewsOn(string $date): int
    {
        return (int) Database::query(
            'SELECT COUNT(*) c FROM page_views WHERE DATE(created_at) = ?',
            [$date]
        )->fetch()['c'];
    }

    public static function onlineNow(int $minutes = 5): int
    {
        return (int) Database::query(
            'SELECT COUNT(DISTINCT visitor_hash) c FROM page_views WHERE created_at >= NOW() - INTERVAL ? MINUTE',
            [$minutes]
        )->fetch()['c'];
    }

    public static function uniqueVisitors(int $days = 30): int
    {
        return (int) Database::query(
            'SELECT COUNT(DISTINCT visitor_hash) c FROM page_views WHERE created_at >= NOW() - INTERVAL ? DAY',
            [$days]
        )->fetch()['c'];
    }

    /** @return array<string,int> 'Y-m-d' => count, zero-filled */
    public static function dailySeries(int $days = 30, bool $unique = false): array
    {
        $out = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $out[date('Y-m-d', strtotime("-$i days"))] = 0;
        }
        $expr = $unique ? 'COUNT(DISTINCT visitor_hash)' : 'COUNT(*)';
        $rows = Database::query(
            "SELECT DATE(created_at) d, $expr c FROM page_views
             WHERE created_at >= CURDATE() - INTERVAL ? DAY
             GROUP BY DATE(created_at)",
            [$days - 1]
        )->fetchAll();

        foreach ($rows as $row) {
            if (isset($out[$row['d']])) {
                $out[$row['d']] = (int) $row['c'];
            }
        }
        return $out;
    }

    /** @return array<string,int> path => views, most visited first */
    public static function topPages(int $days = 30, int $limit = 8): array
    {
        $rows = Database::query(
            'SELECT path, COUNT(*) c FROM page_views
             WHERE created_at >= NOW() - INTERVAL ? DAY
             GROUP BY path ORDER BY c DESC LIMIT ' . $limit,
            [$days]
        )->fetchAll();

        $out = [];
        foreach ($rows as $row) {
            $out[$row['path'] === '/' ? 'Home' : $row['path']] = (int) $row['c'];
        }
        return $out;
    }

    /** Total views within the period. */
    public static function totalViews(int $days = 30): int
    {
        return (int) Database::query(
            'SELECT COUNT(*) c FROM page_views WHERE created_at >= NOW() - INTERVAL ? DAY',
            [$days]
        )->fetch()['c'];
    }

    /**
     * Where visitors arrived from, grouped by host. Internal navigation is
     * already excluded at write time, so anything here is an outside source.
     *
     * @return array<string,int> host => visits
     */
    public static function topReferrers(int $days = 30, int $limit = 8): array
    {
        $rows = Database::query(
            "SELECT referrer, COUNT(*) c FROM page_views
             WHERE created_at >= NOW() - INTERVAL ? DAY AND referrer != ''
             GROUP BY referrer",
            [$days]
        )->fetchAll();

        $byHost = [];
        foreach ($rows as $row) {
            $host = parse_url($row['referrer'], PHP_URL_HOST) ?: $row['referrer'];
            $host = preg_replace('/^www\./', '', (string) $host);
            $byHost[$host] = ($byHost[$host] ?? 0) + (int) $row['c'];
        }
        arsort($byHost);
        return array_slice($byHost, 0, $limit, true);
    }

    /** Share of visits that arrived without a referrer (typed, bookmarked, app). */
    public static function directShare(int $days = 30): int
    {
        $row = Database::query(
            "SELECT COUNT(*) total, SUM(referrer = '') direct
             FROM page_views WHERE created_at >= NOW() - INTERVAL ? DAY",
            [$days]
        )->fetch();
        $total = (int) ($row['total'] ?? 0);
        return $total > 0 ? (int) round(((int) $row['direct']) / $total * 100) : 0;
    }

    /**
     * Average pages viewed per visitor — a rough engagement signal.
     * Returned to one decimal place.
     */
    public static function pagesPerVisitor(int $days = 30): float
    {
        $row = Database::query(
            'SELECT COUNT(*) views, COUNT(DISTINCT visitor_hash) visitors
             FROM page_views WHERE created_at >= NOW() - INTERVAL ? DAY',
            [$days]
        )->fetch();
        $visitors = (int) ($row['visitors'] ?? 0);
        return $visitors > 0 ? round(((int) $row['views']) / $visitors, 1) : 0.0;
    }

    /** @return array<string,int> weekday name => views, Monday first */
    public static function byWeekday(int $days = 30): array
    {
        $out = ['Mon' => 0, 'Tue' => 0, 'Wed' => 0, 'Thu' => 0, 'Fri' => 0, 'Sat' => 0, 'Sun' => 0];
        $rows = Database::query(
            'SELECT DAYNAME(created_at) d, COUNT(*) c FROM page_views
             WHERE created_at >= NOW() - INTERVAL ? DAY GROUP BY d',
            [$days]
        )->fetchAll();

        foreach ($rows as $row) {
            $short = substr((string) $row['d'], 0, 3);
            if (isset($out[$short])) {
                $out[$short] = (int) $row['c'];
            }
        }
        return $out;
    }

    /** Total rows retained — shown so admins know the reporting depth. */
    public static function firstRecordedAt(): ?string
    {
        $row = Database::query('SELECT MIN(created_at) d FROM page_views')->fetch();
        return $row && $row['d'] ? (string) $row['d'] : null;
    }
}
