<?php

declare(strict_types=1);

namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $path, callable $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, callable $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    public function dispatch(string $method, string $path): void
{
    foreach ($this->routes[$method] ?? [] as $route => $handler) {
        $pattern = preg_replace(
            '#\{([^}]+)\}#',
            '([^/]+)',
            $route
        );

        $pattern = '#^' . $pattern . '$#';

        if (preg_match($pattern, $path, $matches)) {
            array_shift($matches);

            call_user_func($handler, ...$matches);

            return;
        }
    }

    http_response_code(404);

    echo 'Página não encontrada.';
}
}