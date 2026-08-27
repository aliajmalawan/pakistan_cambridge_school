<?php

declare(strict_types=1);

namespace App\Core;

final class View
{
    public static function render(string $template, array $data = [], ?string $layout = 'main'): void
    {
        extract($data, EXTR_SKIP);

        ob_start();
        require APP_PATH . '/Views/' . $template . '.php';
        $content = ob_get_clean();

        if ($layout !== null) {
            require APP_PATH . '/Views/layouts/' . $layout . '.php';
        } else {
            echo $content;
        }
    }

    public static function partial(string $template, array $data = []): void
    {
        extract($data, EXTR_SKIP);
        require APP_PATH . '/Views/' . $template . '.php';
    }
}
