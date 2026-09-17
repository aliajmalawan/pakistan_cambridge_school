<?php

declare(strict_types=1);

use App\Core\ImageService;
use App\Core\LogoService;
use App\Core\Session;
use App\Models\Setting;

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function url(string $path = ''): string
{
    return BASE_URL . '/' . ltrim($path, '/');
}

function asset(string $path): string
{
    return BASE_URL . '/assets/' . ltrim($path, '/');
}

/**
 * asset() plus a cache-busting version query built from the file's own
 * mtime, so an edited CSS/JS file is never served stale from a browser
 * cache — no manual version bump needed.
 */
function asset_v(string $path): string
{
    $path = ltrim($path, '/');
    $file = ROOT_PATH . '/assets/' . $path;
    $version = is_file($file) ? (string) filemtime($file) : PCS_ASSET_VERSION;
    return asset($path) . '?v=' . $version;
}

function upload_url(?string $path): string
{
    if (!$path) {
        return '';
    }
    return UPLOAD_URL . '/' . ltrim($path, '/');
}

/** @param int $status 301 when the old address should stop being used at all. */
function redirect(string $path, int $status = 302): never
{
    header('Location: ' . (str_starts_with($path, 'http') ? $path : url($path)), true, $status);
    exit;
}

/**
 * Two-letter initials for a person, used wherever a photograph is missing.
 *
 * Honorifics are skipped, so "Prof. Syed Ali Raza" gives SA and "Qari Ghulam
 * Hussain" gives GH — taking the first letter blindly would label half the
 * staff M for Mr and Mrs.
 */
function initials(string $name): string
{
    $parts = preg_split('/\s+/', trim($name)) ?: [];
    $parts = array_values(array_filter(
        $parts,
        fn($p) => !preg_match('/^(prof|dr|mr|mrs|ms|miss|engr|qari|syed|hafiz|maulana|sir|madam)\.?$/i', $p)
    ));

    if (!$parts) {
        return mb_strtoupper(mb_substr(trim($name), 0, 1));
    }

    $first = mb_substr($parts[0], 0, 1);
    $second = count($parts) > 1 ? mb_substr($parts[count($parts) - 1], 0, 1) : '';

    return mb_strtoupper($first . $second);
}

/** Escaped for printing straight into an attribute or a textarea. */
function old(string $key, string $default = ''): string
{
    return e(old_raw($key, $default));
}

/** Unescaped — for passing to a partial that escapes on output, so it is not escaped twice. */
function old_raw(string $key, string $default = ''): string
{
    return (string) ($_SESSION['_old'][$key] ?? $default);
}

function keep_old(array $data): void
{
    $_SESSION['_old'] = $data;
}

function clear_old(): void
{
    unset($_SESSION['_old']);
}

function flash(string $key): ?string
{
    return Session::flash($key);
}

function setting(string $key, string $default = ''): string
{
    return Setting::value($key, $default);
}

/** Inline SVG for a name in App\Core\Icon::PATHS — see that class for the full key list. */
function icon(string $name, int $size = 24, string $classes = ''): string
{
    return \App\Core\Icon::svg($name, $size, $classes);
}

/** Deterministic pastel colour for a subject-name tag, so the same subject always matches across rows. */
function subject_tag_color(string $name): string
{
    $palette = ['blue', 'green', 'orange', 'rose', 'teal', 'purple', 'red', 'amber'];
    return $palette[crc32(mb_strtolower(trim($name))) % count($palette)];
}

/**
 * URL for the institution logo at a given size.
 * Serves the admin-uploaded logo when one exists, otherwise the bundled crest.
 * The version query busts the year-long asset cache when the logo changes.
 *
 * @param int    $size 32 | 48 | 96 | 192 | 512
 * @param string $ext  'png' or 'webp'
 */
function logo_url(int $size = 96, string $ext = 'png'): string
{
    $version = LogoService::version();

    if (LogoService::hasCustom()) {
        return UPLOAD_URL . '/' . LogoService::DIR . '/logo-' . $size . '.' . $ext . '?v=' . $version;
    }

    // Bundled default: favicon uses its own filename
    $name = $size === 32 ? 'favicon-32.png' : 'logo-' . $size . '.' . $ext;
    return BASE_URL . '/assets/img/' . $name . '?v=' . $version;
}

/** True when a WebP twin exists for this size (custom uploads and defaults both have 96/192/512). */
function logo_has_webp(int $size): bool
{
    return (LogoService::SIZES[$size] ?? false) === true;
}

function slugify(string $text): string
{
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9]+/', '-', $text) ?? '';
    return trim($text, '-') ?: 'item-' . time();
}

/**
 * Plain-text summary, cut on a word boundary so an excerpt never ends
 * mid-word ("… 1st Ye…"). Trailing punctuation is trimmed with it.
 */
/** Strip the internal "placeholder date, replace via Admin..." note some seeded rows carry. */
function public_detail(?string $text): string
{
    return trim(preg_replace('/\s*[—-]\s*placeholder[^.]*\.?\s*$/iu', '', $text ?? ''));
}

function excerpt(string $html, int $length = 160): string
{
    $text = trim(preg_replace('/\s+/', ' ', strip_tags($html)) ?? '');
    if (mb_strlen($text) <= $length) {
        return $text;
    }

    $cut = mb_substr($text, 0, $length);
    $lastSpace = mb_strrpos($cut, ' ');
    // Only fall back to a hard cut if the last word is absurdly long
    if ($lastSpace !== false && $lastSpace > $length * 0.6) {
        $cut = mb_substr($cut, 0, $lastSpace);
    }

    return rtrim($cut, " \t\n\r\0\x0B.,;:!?-–—") . '…';
}

function format_date(?string $date, string $format = 'j F Y'): string
{
    if (!$date) {
        return '';
    }
    return date($format, strtotime($date));
}

function time_ago(?string $datetime): string
{
    if (!$datetime) {
        return '';
    }
    $diff = time() - strtotime($datetime);
    if ($diff < 60) return 'just now';
    if ($diff < 3600) return floor($diff / 60) . ' min ago';
    if ($diff < 86400) return floor($diff / 3600) . ' hr ago';
    if ($diff < 604800) return floor($diff / 86400) . ' days ago';
    return format_date($datetime);
}

/**
 * Handle an image upload. Returns the stored relative path (dir/filename) or null.
 * On validation failure sets a flash error and returns null.
 *
 * Images are resized to $maxWidth and re-encoded (photographs become JPEG, only
 * genuinely transparent images stay PNG), with a WebP twin written alongside.
 * Pass 0 to store the original untouched.
 */
function handle_upload(string $field, string $dir, int $maxWidth = ImageService::WIDTH_CONTENT): ?string
{
    if (empty($_FILES[$field]['name']) || ($_FILES[$field]['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    $file = $_FILES[$field];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        Session::flash('error', 'File upload failed (error code ' . $file['error'] . ').');
        return null;
    }
    if ($file['size'] > UPLOAD_MAX_BYTES) {
        Session::flash('error', 'Image exceeds the 4 MB size limit.');
        return null;
    }
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, UPLOAD_ALLOWED, true)) {
        Session::flash('error', 'Only JPG, PNG, WEBP and GIF images are allowed.');
        return null;
    }
    $info = @getimagesize($file['tmp_name']);
    if ($info === false) {
        Session::flash('error', 'The uploaded file is not a valid image.');
        return null;
    }
    if ($maxWidth > 0) {
        $stored = ImageService::process($file['tmp_name'], $dir, $maxWidth);
        if ($stored === null) {
            Session::flash('error', 'Could not process the uploaded image.');
            return null;
        }
        return $stored;
    }

    $name = date('Ymd-His') . '-' . bin2hex(random_bytes(4)) . '.' . $ext;
    $target = UPLOAD_PATH . '/' . $dir;
    if (!is_dir($target)) {
        mkdir($target, 0775, true);
    }
    if (!move_uploaded_file($file['tmp_name'], $target . '/' . $name)) {
        Session::flash('error', 'Could not save the uploaded image.');
        return null;
    }
    return $dir . '/' . $name;
}

/**
 * Save a non-image document (PDF/DOC/XLS) as-is — no resizing, no WebP twin.
 * Returns ['path' => ..., 'size' => bytes, 'ext' => ...] or null.
 */
function handle_document_upload(string $field, string $dir): ?array
{
    if (empty($_FILES[$field]['name']) || ($_FILES[$field]['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    $file = $_FILES[$field];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        Session::flash('error', 'File upload failed (error code ' . $file['error'] . ').');
        return null;
    }
    if ($file['size'] > DOCUMENT_MAX_BYTES) {
        Session::flash('error', 'File exceeds the 8 MB size limit.');
        return null;
    }
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, DOCUMENT_ALLOWED, true)) {
        Session::flash('error', 'Only PDF, DOC, DOCX, XLS and XLSX files are allowed.');
        return null;
    }
    $name = date('Ymd-His') . '-' . bin2hex(random_bytes(4)) . '.' . $ext;
    $target = UPLOAD_PATH . '/' . $dir;
    if (!is_dir($target)) {
        mkdir($target, 0775, true);
    }
    if (!move_uploaded_file($file['tmp_name'], $target . '/' . $name)) {
        Session::flash('error', 'Could not save the uploaded file.');
        return null;
    }
    return ['path' => $dir . '/' . $name, 'size' => (int) $file['size'], 'ext' => $ext];
}

/**
 * Did the admin tick "Remove this image" for this field?
 *
 * Paired with admin/partials/image_field, which posts <field>_remove. A blank
 * file input means "leave it alone", so clearing an image needs its own signal.
 */
function upload_cleared(string $field): bool
{
    return ($_POST[$field . '_remove'] ?? '0') === '1';
}

function delete_upload_set(?string $path): void
{
    delete_upload($path);
    if ($path) {
        delete_upload(preg_replace('/\.\w+$/', '.webp', $path));
    }
}

function delete_upload(?string $path): void
{
    if ($path && is_file(UPLOAD_PATH . '/' . $path)) {
        @unlink(UPLOAD_PATH . '/' . $path);
    }
}

/** Render pagination links for a Model::paginate() result. */
function pagination_links(array $p, string $baseUrl): string
{
    if ($p['pages'] <= 1) {
        return '';
    }
    $sep = str_contains($baseUrl, '?') ? '&' : '?';
    $html = '<nav class="flex items-center gap-1.5 flex-wrap" aria-label="Pagination">';
    $link = function (int $page, string $label, bool $current = false, ?string $aria = null) use ($baseUrl, $sep): string {
        $cls = $current
            ? 'min-w-[2rem] h-8 px-2 inline-flex items-center justify-center rounded-lg bg-navy text-paper text-sm font-semibold'
            : 'min-w-[2rem] h-8 px-2 inline-flex items-center justify-center rounded-lg text-sm text-ink-700 hover:bg-ink-100 transition';
        $ariaAttr = $current ? ' aria-current="page"' : ($aria ? ' aria-label="' . e($aria) . '"' : '');
        return '<a class="' . $cls . '" href="' . e($baseUrl . $sep . 'page=' . $page) . '"' . $ariaAttr . '>' . $label . '</a>';
    };
    if ($p['page'] > 1) {
        $html .= $link($p['page'] - 1, '‹', false, 'Previous page');
    }
    $window = array_unique(array_filter(
        [1, $p['page'] - 1, $p['page'], $p['page'] + 1, $p['pages']],
        fn($n) => $n >= 1 && $n <= $p['pages']
    ));
    sort($window);
    $prev = 0;
    foreach ($window as $n) {
        if ($n - $prev > 1) {
            $html .= '<span class="px-1 text-ink-500">…</span>';
        }
        $html .= $link($n, (string) $n, $n === $p['page']);
        $prev = $n;
    }
    if ($p['page'] < $p['pages']) {
        $html .= $link($p['page'] + 1, '›', false, 'Next page');
    }
    return $html . '</nav>';
}
