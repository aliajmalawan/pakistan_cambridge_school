<?php

declare(strict_types=1);

namespace App\Core;

final class Router
{
    /** @var array<string, array<array{pattern: string, handler: array}>> */
    private array $routes = ['GET' => [], 'POST' => []];

    public function get(string $pattern, array $handler): void
    {
        $this->routes['GET'][] = ['pattern' => $pattern, 'handler' => $handler];
    }

    public function post(string $pattern, array $handler): void
    {
        $this->routes['POST'][] = ['pattern' => $pattern, 'handler' => $handler];
    }

    private function currentPath(): string
    {
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
        $base = parse_url(BASE_URL, PHP_URL_PATH) ?? '';
        if ($base !== '' && str_starts_with($uri, $base)) {
            $uri = substr($uri, strlen($base));
        }
        $uri = '/' . trim($uri, '/');
        return $uri === '' ? '/' : $uri;
    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        if (!isset($this->routes[$method])) {
            $this->abort(405);
            return;
        }
        $path = $this->currentPath();

        foreach ($this->routes[$method] as $route) {
            $regex = '#^' . preg_replace('/\{([a-zA-Z_]+)\}/', '(?P<$1>[^/]+)', $route['pattern']) . '$#';
            if (preg_match($regex, $path, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                [$class, $action] = $route['handler'];
                $controller = new $class();
                $controller->$action(...array_values(array_map('urldecode', $params)));
                return;
            }
        }
        $this->abort(404);
    }

    public function abort(int $code = 404): void
    {
        http_response_code($code);
        View::render('errors/404', ['code' => $code], 'main');
        exit;
    }
}
