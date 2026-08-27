<?php

declare(strict_types=1);

namespace App\Core;

abstract class Controller
{
    protected function view(string $template, array $data = [], ?string $layout = 'main'): void
    {
        View::render($template, $data, $layout);
    }

    protected function adminView(string $template, array $data = []): void
    {
        View::render($template, $data, 'admin');
    }

    protected function input(string $key, string $default = ''): string
    {
        return trim((string) ($_POST[$key] ?? $default));
    }

    protected function queryParam(string $key, string $default = ''): string
    {
        return trim((string) ($_GET[$key] ?? $default));
    }

    protected function pageParam(): int
    {
        return max(1, (int) ($_GET['page'] ?? 1));
    }
}
