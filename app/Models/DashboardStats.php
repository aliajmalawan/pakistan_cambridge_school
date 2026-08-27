<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

/** Aggregate queries for the admin dashboard. Not a table-backed model. */
final class DashboardStats
{
    /** Last N months as ['ym' => 'YYYY-MM', 'label' => 'Mar'] rows, oldest first. */
    public static function monthWindow(int $months = 6): array
    {
        $out = [];
        for ($i = $months - 1; $i >= 0; $i--) {
            $ts = strtotime(date('Y-m-01') . " -$i months");
            $out[] = ['ym' => date('Y-m', $ts), 'label' => date('M', $ts)];
        }
        return $out;
    }

    /**
     * Monthly counts for a table, zero-filled.
     * $table and $dateColumn are internal identifiers, never user input.
     *
     * @return array<string,int> 'Mar' => 4
     */
    public static function monthlySeries(string $table, string $dateColumn = 'created_at', string $extraWhere = ''): array
    {
        $where = "$dateColumn >= DATE_SUB(DATE_FORMAT(NOW(), '%Y-%m-01'), INTERVAL 5 MONTH)"
            . ($extraWhere !== '' ? " AND $extraWhere" : '');

        $rows = Database::query(
            "SELECT DATE_FORMAT($dateColumn, '%Y-%m') ym, COUNT(*) c FROM $table WHERE $where GROUP BY ym"
        )->fetchAll();

        $map = [];
        foreach ($rows as $row) {
            $map[$row['ym']] = (int) $row['c'];
        }

        $out = [];
        foreach (self::monthWindow(6) as $month) {
            $out[$month['label']] = $map[$month['ym']] ?? 0;
        }
        return $out;
    }

    /** Count within the current calendar month. */
    public static function thisMonth(string $table, string $dateColumn = 'created_at'): int
    {
        return (int) Database::query(
            "SELECT COUNT(*) c FROM $table
             WHERE YEAR($dateColumn) = YEAR(NOW()) AND MONTH($dateColumn) = MONTH(NOW())"
        )->fetch()['c'];
    }

    /** Count within the previous calendar month — the comparison for deltas. */
    public static function lastMonth(string $table, string $dateColumn = 'created_at'): int
    {
        return (int) Database::query(
            "SELECT COUNT(*) c FROM $table
             WHERE $dateColumn >= DATE_FORMAT(NOW() - INTERVAL 1 MONTH, '%Y-%m-01')
               AND $dateColumn <  DATE_FORMAT(NOW(), '%Y-%m-01')"
        )->fetch()['c'];
    }

    /** Top classes by application demand with a per-status split. */
    public static function admissionsByClass(int $limit = 8): array
    {
        return Database::query(
            'SELECT class_applied,
                    COUNT(*) total,
                    SUM(status = "pending") pending,
                    SUM(status = "under_review") under_review,
                    SUM(status = "accepted") accepted,
                    SUM(status = "rejected") rejected
             FROM admissions
             GROUP BY class_applied
             ORDER BY total DESC
             LIMIT ' . $limit
        )->fetchAll();
    }

    /** @return array<string,int> class => applications, for the bar list */
    public static function classDemandBars(int $limit = 6): array
    {
        $out = [];
        foreach (self::admissionsByClass($limit) as $row) {
            $out[$row['class_applied']] = (int) $row['total'];
        }
        return $out;
    }

    public static function admissionStatusSplit(): array
    {
        $row = Database::query(
            'SELECT COUNT(*) total,
                    SUM(status = "pending") pending,
                    SUM(status = "under_review") under_review,
                    SUM(status = "accepted") accepted,
                    SUM(status = "rejected") rejected
             FROM admissions'
        )->fetch();
        return array_map('intval', $row ?: []);
    }

    /** @return array<string,int> album title => photo count */
    public static function galleryByAlbum(int $limit = 8): array
    {
        $rows = Database::query(
            'SELECT a.title, COUNT(i.id) c
             FROM gallery_albums a
             LEFT JOIN gallery_images i ON i.album_id = a.id
             GROUP BY a.id
             HAVING c > 0
             ORDER BY c DESC
             LIMIT ' . $limit
        )->fetchAll();

        $out = [];
        foreach ($rows as $row) {
            $out[$row['title']] = (int) $row['c'];
        }
        return $out;
    }

    /** @return array<string,int> role => user count */
    public static function usersByRole(): array
    {
        $out = ['superadmin' => 0, 'admin' => 0, 'editor' => 0];
        foreach (Database::query('SELECT role, COUNT(*) c FROM users GROUP BY role')->fetchAll() as $row) {
            $out[$row['role']] = (int) $row['c'];
        }
        return $out;
    }

    /** @return array<string,int> day-of-month => admin logins, last N days */
    public static function loginsDaily(int $days = 14): array
    {
        $out = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $out[date('Y-m-d', strtotime("-$i days"))] = 0;
        }
        $rows = Database::query(
            "SELECT DATE(created_at) d, COUNT(*) c FROM activity_log
             WHERE action = 'login' AND created_at >= CURDATE() - INTERVAL ? DAY
             GROUP BY DATE(created_at)",
            [$days - 1]
        )->fetchAll();

        foreach ($rows as $row) {
            if (isset($out[$row['d']])) {
                $out[$row['d']] = (int) $row['c'];
            }
        }

        $labelled = [];
        foreach ($out as $date => $count) {
            $labelled[date('j', strtotime($date))] = $count;
        }
        return $labelled;
    }

    /** News + notices published per month, combined. */
    public static function contentPerMonth(): array
    {
        return self::monthlySeries('news', 'published_at');
    }
}
