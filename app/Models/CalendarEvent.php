<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use App\Core\Model;

final class CalendarEvent extends Model
{
    protected static string $table = 'calendar_events';

    public const TYPES = [
        'holiday'     => 'Holiday',
        'examination' => 'Examination',
        'event'       => 'School event',
        'meeting'     => 'Parent meeting',
        'deadline'    => 'Deadline',
    ];

    /**
     * Colour class per type. Five hues cannot be told apart reliably at this size
     * (the palette validator fails the normal-vision floor), so events and meetings
     * share the "come to campus" colour and are separated by their text label.
     */
    public const TYPE_CLASS = [
        'holiday'     => 'cal-holiday',
        'examination' => 'cal-exam',
        'deadline'    => 'cal-deadline',
        'event'       => 'cal-event',
        'meeting'     => 'cal-event',
    ];

    /** What the legend shows: one row per colour, not per type. */
    public const LEGEND = [
        'cal-holiday'  => 'Holiday — campus closed',
        'cal-exam'     => 'Examinations',
        'cal-deadline' => 'Deadline',
        'cal-event'    => 'Event or parent meeting',
    ];

    public static function typeClass(string $type): string
    {
        return self::TYPE_CLASS[$type] ?? 'cal-event';
    }

    /** Sessions that have at least one published event, newest first. */
    public static function sessions(): array
    {
        return Database::query(
            'SELECT session FROM ' . static::$table . " WHERE status = 'published' AND session != ''"
            . ' GROUP BY session ORDER BY MIN(starts_on) DESC'
        )->fetchAll(\PDO::FETCH_COLUMN);
    }

    /**
     * The session a visitor should land on: the one containing today, else the
     * next one to start, else the most recent. Never guesses when there is no data.
     */
    public static function currentSession(): ?string
    {
        $today = date('Y-m-d');
        $row = Database::query(
            'SELECT session FROM ' . static::$table
            . " WHERE status = 'published' AND session != '' GROUP BY session"
            . ' HAVING MIN(starts_on) <= ? AND MAX(COALESCE(ends_on, starts_on)) >= ?'
            . ' ORDER BY MIN(starts_on) DESC LIMIT 1',
            [$today, $today]
        )->fetch();
        if ($row) {
            return $row['session'];
        }

        $upcoming = Database::query(
            'SELECT session FROM ' . static::$table
            . " WHERE status = 'published' AND session != '' GROUP BY session"
            . ' HAVING MIN(starts_on) > ? ORDER BY MIN(starts_on) ASC LIMIT 1',
            [$today]
        )->fetch();

        return $upcoming ? $upcoming['session'] : (self::sessions()[0] ?? null);
    }

    public static function forSession(string $session): array
    {
        return static::where('status = ? AND session = ?', ['published', $session], 'starts_on ASC, id ASC');
    }

    /** Events grouped by the month they start in, in calendar order. */
    public static function byMonth(array $events): array
    {
        $months = [];
        foreach ($events as $e) {
            $key = date('Y-m', strtotime($e['starts_on']));
            $months[$key][] = $e;
        }
        ksort($months);
        return $months;
    }

    /** The next few things a family needs to know about, from today onwards. */
    public static function upcoming(string $session, int $limit = 3): array
    {
        return static::where(
            'status = ? AND session = ? AND COALESCE(ends_on, starts_on) >= ?',
            ['published', $session, date('Y-m-d')],
            'starts_on ASC, id ASC',
            $limit
        );
    }

    /**
     * How many entries of each type a session holds.
     *
     * Deliberately counts entries, not days: ranges overlap (a public holiday
     * falling inside the winter break) so a day total would double-count, and
     * "115 holiday days" tells a guardian far less than "11 holidays".
     */
    public static function typeCounts(string $session): array
    {
        $counts = array_fill_keys(array_keys(self::TYPES), 0);
        foreach (self::forSession($session) as $e) {
            $counts[$e['type']] = ($counts[$e['type']] ?? 0) + 1;
        }
        return $counts;
    }

    /** First and last day the session covers, for the summary strip. */
    public static function span(array $events): ?string
    {
        if (!$events) {
            return null;
        }
        $start = min(array_column($events, 'starts_on'));
        $end = max(array_map(fn($e) => !empty($e['ends_on']) ? $e['ends_on'] : $e['starts_on'], $events));
        return date('j M Y', strtotime($start)) . ' – ' . date('j M Y', strtotime($end));
    }

    /** Inclusive length of an entry in days. */
    public static function days(array $event): int
    {
        $start = strtotime($event['starts_on']);
        $end = !empty($event['ends_on']) ? strtotime($event['ends_on']) : $start;
        if ($end < $start) {
            return 1;
        }
        return (int) floor(($end - $start) / 86400) + 1;
    }

    /** "14 December" or "1 – 10 December", and the year only when it differs. */
    public static function dateLabel(array $event): string
    {
        $start = strtotime($event['starts_on']);
        if (empty($event['ends_on']) || $event['ends_on'] === $event['starts_on']) {
            return date('j F', $start);
        }
        $end = strtotime($event['ends_on']);
        if (date('Y', $start) !== date('Y', $end)) {
            return date('j M Y', $start) . ' – ' . date('j M Y', $end);
        }
        if (date('n', $start) === date('n', $end)) {
            return date('j', $start) . ' – ' . date('j F', $end);
        }
        return date('j M', $start) . ' – ' . date('j M', $end);
    }

    public static function isPast(array $event): bool
    {
        $end = !empty($event['ends_on']) ? $event['ends_on'] : $event['starts_on'];
        return $end < date('Y-m-d');
    }

    public static function isCurrent(array $event): bool
    {
        $today = date('Y-m-d');
        $end = !empty($event['ends_on']) ? $event['ends_on'] : $event['starts_on'];
        return $event['starts_on'] <= $today && $end >= $today;
    }
}
