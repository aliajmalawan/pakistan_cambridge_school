<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Pakistan Cambridge School — Application Configuration
|--------------------------------------------------------------------------
|
| Nothing in this file needs editing to move the site between machines. The
| environment, the site address and the sub-directory are all read from the
| request, so the same code runs unchanged on XAMPP at
| http://localhost/PCS/ and on cPanel at https://your-domain.pk/.
|
| Database credentials are the one exception — those live in database.php.
|
*/

const APP_NAME = 'Pakistan Cambridge School';

// ---------------------------------------------------------------- environment
// A local hostname means development; anything else is the live site.
$host = strtolower((string) ($_SERVER['HTTP_HOST'] ?? 'localhost'));
$hostName = explode(':', $host)[0];
define('APP_ENV', in_array($hostName, ['localhost', '127.0.0.1', '::1'], true)
    || str_ends_with($hostName, '.local')
    || str_ends_with($hostName, '.test')
        ? 'development'
        : 'production');

// ---------------------------------------------------------------- base URL
// SCRIPT_NAME is "/PCS/index.php" under XAMPP and "/index.php" at a
// domain root, so the sub-directory falls out of it without being hard-coded.
$https = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https')   // behind a proxy or CDN
    || ((int) ($_SERVER['SERVER_PORT'] ?? 80) === 443);
$dir = str_replace('\\', '/', dirname((string) ($_SERVER['SCRIPT_NAME'] ?? '/index.php')));
$dir = ($dir === '/' || $dir === '.') ? '' : rtrim($dir, '/');

define('BASE_URL', ($https ? 'https' : 'http') . '://' . $host . $dir);

// ---------------------------------------------------------------- database
$dbAll = require __DIR__ . '/database.php';
$db = $dbAll[APP_ENV] ?? $dbAll['development'];

define('DB_HOST', $db['host']);
define('DB_PORT', (int) $db['port']);
define('DB_NAME', $db['name']);
define('DB_USER', $db['user']);
define('DB_PASS', $db['pass']);
define('DB_CHARSET', $db['charset'] ?? 'utf8mb4');

// ---------------------------------------------------------------- uploads
define('UPLOAD_PATH', ROOT_PATH . '/uploads');
define('UPLOAD_URL', BASE_URL . '/uploads');
const UPLOAD_MAX_BYTES = 4 * 1024 * 1024; // 4 MB
const UPLOAD_ALLOWED = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

// ---------------------------------------------------------------- pagination
const PER_PAGE = 12;
const ADMIN_PER_PAGE = 20;

// Cache-busting token appended to CSS/JS URLs. Bump after changing assets.
const PCS_ASSET_VERSION = '1.0.0';

// ---------------------------------------------------------------- errors
// A live site never shows a stack trace to a visitor; it writes it to the log
// instead, which cPanel exposes under Metrics → Errors.
if (APP_ENV === 'development') {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
    error_reporting(E_ALL & ~E_DEPRECATED);
}

date_default_timezone_set('Asia/Karachi');
