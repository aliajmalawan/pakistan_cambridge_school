<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Server-rendered SVG charts for the admin dashboard.
 *
 * No chart library and no client-side fetch: the markup arrives with the page,
 * so the dashboard is complete before JavaScript runs. Colours come from the
 * validated categorical palette in docs/brand/BRAND_GUIDELINES.md §7 — never
 * the ceremonial brand hexes, which fail colour-blind separation at mark size.
 *
 * Every chart is role="img" with an aria-label, and every mark carries a
 * <title> so hovering gives the exact figure.
 */
final class Chart
{
    // Validated categorical slots (fixed order — never cycled)
    public const BLUE    = '#2a78d6';
    public const ORANGE  = '#eb6834';
    public const AQUA    = '#1baf7a';
    public const GOLD    = '#eda100';
    public const MAGENTA = '#e87ba4';
    public const RED     = '#e34948';

    private const GRID = '#e1e0d9';
    private const AXIS = '#c3c2b7';
    private const INK  = '#6B7280';

    /** Round a maximum up to a friendly axis top. */
    private static function niceMax(array $values): int
    {
        $max = $values ? max($values) : 0;
        if ($max <= 4) {
            return 4;
        }
        $step = 10 ** (int) floor(log10($max)) / 2;
        return (int) (ceil($max / $step) * $step);
    }

    private static function empty(string $message, int $height = 176): string
    {
        return '<div class="flex flex-col items-center justify-center text-center border-[1.5px] border-dashed border-ink-300 rounded-lg px-4"'
            . ' style="height:' . $height . 'px">'
            . '<p class="text-sm font-medium text-ink-700">Nothing to chart yet</p>'
            . '<p class="text-xs text-ink-500 mt-1 max-w-[36ch]">' . e($message) . '</p></div>';
    }

    /** Screen-reader table so the figures are never colour-only. */
    private static function table(array $data, string $keyHeading, string $valueHeading): string
    {
        $rows = '';
        foreach ($data as $label => $value) {
            $rows .= '<tr><td>' . e((string) $label) . '</td><td>' . (int) $value . '</td></tr>';
        }
        return '<table class="sr-only"><caption>' . e($valueHeading) . '</caption>'
            . '<thead><tr><th>' . e($keyHeading) . '</th><th>' . e($valueHeading) . '</th></tr></thead>'
            . '<tbody>' . $rows . '</tbody></table>';
    }

    /**
     * Line + area chart, one or two series on a single axis (never dual-axis).
     *
     * @param array $series [['label'=>string,'color'=>hex,'data'=>int[]], ...]
     * @param string[] $labels aligned by index with each series' data
     */
    public static function line(array $series, array $labels, string $ariaLabel, string $emptyMessage): string
    {
        $all = [];
        foreach ($series as $s) {
            foreach ($s['data'] as $v) {
                $all[] = (int) $v;
            }
        }
        if (!$all || array_sum($all) === 0) {
            return self::empty($emptyMessage, 210);
        }

        $w = 640; $h = 210; $padL = 38; $padR = 14; $padT = 12; $padB = 26;
        $plotW = $w - $padL - $padR;
        $plotH = $h - $padT - $padB;
        $n = max(1, count($labels));
        $yMax = self::niceMax($all);

        $xAt = fn(int $i): float => $padL + ($n > 1 ? $i / ($n - 1) : 0.5) * $plotW;
        $yAt = fn(float $v): float => $padT + $plotH - ($v / $yMax) * $plotH;

        $out = '<svg viewBox="0 0 ' . $w . ' ' . $h . '" class="w-full h-[210px]" role="img" aria-label="' . e($ariaLabel) . '" preserveAspectRatio="none">';

        for ($g = 0; $g <= 4; $g++) {
            $gy = round($padT + $plotH - ($g / 4) * $plotH, 1);
            $out .= '<line x1="' . $padL . '" y1="' . $gy . '" x2="' . ($w - $padR) . '" y2="' . $gy
                . '" stroke="' . ($g === 0 ? self::AXIS : self::GRID) . '" stroke-width="1"/>';
            $out .= '<text x="' . ($padL - 6) . '" y="' . ($gy + 3) . '" text-anchor="end" font-size="10" fill="' . self::INK . '">'
                . (int) round($yMax * $g / 4) . '</text>';
        }

        foreach (array_unique([0, intdiv($n - 1, 4), intdiv($n - 1, 2), intdiv(3 * ($n - 1), 4), $n - 1]) as $i) {
            $out .= '<text x="' . round($xAt($i), 1) . '" y="' . ($h - 8) . '" text-anchor="middle" font-size="10" fill="' . self::INK . '">'
                . e((string) ($labels[$i] ?? '')) . '</text>';
        }

        foreach ($series as $index => $s) {
            $points = [];
            foreach ($s['data'] as $i => $v) {
                $points[] = round($xAt($i), 1) . ',' . round($yAt((float) $v), 1);
            }
            if ($index === 0) {
                $area = 'M' . round($xAt(0), 1) . ',' . ($padT + $plotH)
                    . ' L' . implode(' L', $points)
                    . ' L' . round($xAt($n - 1), 1) . ',' . ($padT + $plotH) . ' Z';
                $out .= '<path d="' . $area . '" fill="' . $s['color'] . '" opacity="0.12"/>';
            }
            $out .= '<polyline points="' . implode(' ', $points) . '" fill="none" stroke="' . $s['color']
                . '" stroke-width="2" stroke-linejoin="round" stroke-linecap="round"/>';
            $endValue = (float) ($s['data'][$n - 1] ?? 0);
            $out .= '<circle cx="' . round($xAt($n - 1), 1) . '" cy="' . round($yAt($endValue), 1)
                . '" r="3.5" fill="' . $s['color'] . '" stroke="#fff" stroke-width="2"/>';
        }

        // Invisible hover columns give a tooltip anywhere on the chart
        for ($i = 0; $i < $n; $i++) {
            $parts = [];
            foreach ($series as $s) {
                $parts[] = $s['label'] . ': ' . (int) ($s['data'][$i] ?? 0);
            }
            $out .= '<rect x="' . round($xAt($i) - $plotW / $n / 2, 1) . '" y="' . $padT
                . '" width="' . round($plotW / $n, 1) . '" height="' . $plotH . '" fill="transparent">'
                . '<title>' . e(($labels[$i] ?? '') . ' — ' . implode(', ', $parts)) . '</title></rect>';
        }

        $out .= '</svg>';

        if (count($series) > 1) {
            $out .= '<div class="flex gap-5 flex-wrap mt-3 pt-3 border-t border-ink-100 text-xs text-ink-500">';
            foreach ($series as $s) {
                $out .= '<span class="inline-flex items-center gap-1.5">'
                    . '<i class="w-2.5 h-2.5 rounded-[3px] inline-block" style="background:' . $s['color'] . '"></i>'
                    . e($s['label']) . '</span>';
            }
            $out .= '</div>';
        }

        $rows = [];
        foreach ($labels as $i => $label) {
            $rows[$label] = $series[0]['data'][$i] ?? 0;
        }
        return $out . self::table($rows, 'Date', $series[0]['label']);
    }

    /** Vertical column chart. @param array<string,int> $data */
    public static function columns(array $data, string $ariaLabel, string $emptyMessage, string $color = self::BLUE): string
    {
        if (!$data || array_sum($data) === 0) {
            return self::empty($emptyMessage);
        }

        $w = 640; $h = 176; $padL = 34; $padR = 10; $padT = 18; $padB = 26;
        $plotW = $w - $padL - $padR;
        $plotH = $h - $padT - $padB;
        $n = max(1, count($data));
        $yMax = self::niceMax(array_values($data));
        $band = $plotW / $n;
        $barW = min(40.0, $band * 0.55);

        $out = '<svg viewBox="0 0 ' . $w . ' ' . $h . '" class="w-full h-[176px]" role="img" aria-label="' . e($ariaLabel) . '" preserveAspectRatio="none">';

        for ($g = 0; $g <= 4; $g++) {
            $gy = round($padT + $plotH - ($g / 4) * $plotH, 1);
            $out .= '<line x1="' . $padL . '" y1="' . $gy . '" x2="' . ($w - $padR) . '" y2="' . $gy
                . '" stroke="' . ($g === 0 ? self::AXIS : self::GRID) . '" stroke-width="1"/>';
            $out .= '<text x="' . ($padL - 6) . '" y="' . ($gy + 3) . '" text-anchor="end" font-size="10" fill="' . self::INK . '">'
                . (int) round($yMax * $g / 4) . '</text>';
        }

        $i = 0;
        foreach ($data as $label => $value) {
            $cx = $padL + $band * ($i + 0.5);
            $barH = ($value / $yMax) * $plotH;
            $y = $padT + $plotH - $barH;

            $out .= '<rect x="' . round($cx - $barW / 2, 1) . '" y="' . round($y, 1)
                . '" width="' . round($barW, 1) . '" height="' . round(max(0, $barH), 1)
                . '" rx="4" fill="' . $color . '"><title>' . e($label . ': ' . (int) $value) . '</title></rect>';

            if ($value > 0) {
                $out .= '<text x="' . round($cx, 1) . '" y="' . round($y - 5, 1)
                    . '" text-anchor="middle" font-size="10" font-weight="600" fill="' . self::INK . '">' . (int) $value . '</text>';
            }
            $out .= '<text x="' . round($cx, 1) . '" y="' . ($h - 9) . '" text-anchor="middle" font-size="10" fill="' . self::INK . '">'
                . e((string) $label) . '</text>';
            $i++;
        }

        return $out . '</svg>' . self::table($data, 'Period', $ariaLabel);
    }

    /**
     * Donut with a centred total and a legend.
     * @param array $slices [['label'=>string,'value'=>int,'color'=>hex], ...]
     */
    public static function donut(array $slices, string $ariaLabel, string $centreLabel, string $emptyMessage): string
    {
        $total = 0;
        foreach ($slices as $s) {
            $total += (int) $s['value'];
        }
        if ($total === 0) {
            return self::empty($emptyMessage);
        }

        $cx = 70; $cy = 70; $r = 52; $stroke = 22;
        $circumference = 2 * M_PI * $r;
        $gap = 1.5; // surface gap between segments

        $out = '<div class="flex items-center gap-6 flex-wrap">';
        $out .= '<svg viewBox="0 0 140 140" class="w-[140px] h-[140px] shrink-0" role="img" aria-label="' . e($ariaLabel) . '">';
        $out .= '<circle cx="' . $cx . '" cy="' . $cy . '" r="' . $r . '" fill="none" stroke="#EEF0F2" stroke-width="' . $stroke . '"/>';

        $offset = 0.0;
        foreach ($slices as $s) {
            $value = (int) $s['value'];
            if ($value <= 0) {
                continue;
            }
            $fraction = $value / $total;
            $length = max(0.0, $fraction * $circumference - $gap);
            $out .= '<circle cx="' . $cx . '" cy="' . $cy . '" r="' . $r . '" fill="none" stroke="' . $s['color']
                . '" stroke-width="' . $stroke . '"'
                . ' stroke-dasharray="' . round($length, 2) . ' ' . round($circumference - $length, 2) . '"'
                . ' stroke-dashoffset="' . round(-$offset, 2) . '"'
                . ' transform="rotate(-90 ' . $cx . ' ' . $cy . ')">'
                . '<title>' . e($s['label'] . ': ' . $value . ' (' . round($fraction * 100) . '%)') . '</title></circle>';
            $offset += $fraction * $circumference;
        }

        $out .= '<text x="70" y="67" text-anchor="middle" font-size="24" font-weight="700" fill="#1B1F23">' . $total . '</text>';
        $out .= '<text x="70" y="86" text-anchor="middle" font-size="10" fill="' . self::INK . '">' . e($centreLabel) . '</text>';
        $out .= '</svg>';

        $out .= '<div class="flex flex-col gap-2 text-sm min-w-0">';
        foreach ($slices as $s) {
            $percent = $total > 0 ? round((int) $s['value'] / $total * 100) : 0;
            $out .= '<span class="inline-flex items-center gap-2 text-ink-700">'
                . '<i class="w-2.5 h-2.5 rounded-[3px] inline-block shrink-0" style="background:' . $s['color'] . '"></i>'
                . e($s['label'])
                . ' <strong class="tabular">' . (int) $s['value'] . '</strong>'
                . ' <span class="text-ink-500 text-xs tabular">' . $percent . '%</span></span>';
        }
        $out .= '</div></div>';

        $rows = [];
        foreach ($slices as $s) {
            $rows[$s['label']] = $s['value'];
        }
        return $out . self::table($rows, 'Category', $centreLabel);
    }

    /** Horizontal bar list. @param array<string,int> $data */
    public static function bars(array $data, string $emptyMessage, string $color = self::BLUE): string
    {
        if (!$data || array_sum($data) === 0) {
            return self::empty($emptyMessage);
        }
        $max = max(array_values($data)) ?: 1;

        $out = '<div class="flex flex-col gap-2.5">';
        foreach ($data as $label => $value) {
            $percent = (int) round($value / $max * 100);
            $out .= '<div class="grid grid-cols-[minmax(0,9rem)_1fr_2.5rem] items-center gap-3">'
                . '<span class="text-sm text-ink-700 truncate" title="' . e((string) $label) . '">' . e((string) $label) . '</span>'
                . '<span class="h-3.5 bg-sunken rounded-full overflow-hidden">'
                . '<span class="block h-full rounded-full" style="width:' . $percent . '%;background:' . $color . '"></span></span>'
                . '<span class="text-sm text-ink-700 text-right tabular">' . (int) $value . '</span></div>';
        }
        $out .= '</div>';

        return $out . self::table($data, 'Item', 'Count');
    }

    /** "▲ 12%" / "▼ 8%" against the previous period, or "new" from zero. */
    public static function delta(int $current, int $previous): string
    {
        if ($previous === 0 && $current === 0) {
            return '';
        }
        if ($previous === 0) {
            return '<span class="inline-flex items-center gap-1 text-xs font-semibold text-success">▲ new</span>';
        }
        $percent = (int) round(($current - $previous) / $previous * 100);
        if ($percent === 0) {
            return '<span class="inline-flex items-center gap-1 text-xs font-semibold text-ink-500">no change</span>';
        }
        return $percent > 0
            ? '<span class="inline-flex items-center gap-1 text-xs font-semibold text-success">▲ ' . $percent . '%</span>'
            : '<span class="inline-flex items-center gap-1 text-xs font-semibold text-danger">▼ ' . abs($percent) . '%</span>';
    }
}
