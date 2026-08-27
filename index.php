<?php

declare(strict_types=1);

define('ROOT_PATH', __DIR__);
define('APP_PATH', __DIR__ . '/app');

require_once ROOT_PATH . '/config/config.php';

spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    if (str_starts_with($class, $prefix)) {
        $file = APP_PATH . '/' . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';
        if (is_file($file)) {
            require_once $file;
        }
    }
});

require_once APP_PATH . '/Core/Helpers.php';

App\Core\Session::start();

// Record the visit before dispatching — controllers may redirect or exit.
App\Models\PageView::record();

$router = new App\Core\Router();
require_once ROOT_PATH . '/routes/web.php';
$router->dispatch();
