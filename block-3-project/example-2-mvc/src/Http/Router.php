<?php
declare(strict_types=1);

namespace App\Http;

use App\Exceptions\HttpException;
use App\Exceptions\NotFoundException;

class Router
{
    private array $routes = [];     // ['GET' => ['/product/{slug}' => callable]]

    public function get(string $path, callable $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, callable $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    public function delete(string $path, callable $handler): void
    {
        $this->routes['DELETE'][$path] = $handler;
    }

    public function dispatch(Request $request): Response
    {
        $method = $request->method === 'HEAD' ? 'GET' : $request->method;

        foreach ($this->routes[$method] ?? [] as $route => $handler) {
            $params = $this->match($route, $request->path);

            if ($params !== null) {
                return $handler($request, ...$params);
            }
        }

        // Ուղին կա, բայց ուրիշ մեթոդով → 405, ոչ 404 — PHP Դաս 15-ի խորացված տնային
        foreach ($this->routes as $otherMethod => $routes) {
            foreach (array_keys($routes) as $route) {
                if ($otherMethod !== $method && $this->match($route, $request->path) !== null) {
                    throw new HttpException('Մեթոդը չի թույլատրվում', 405);
                }
            }
        }

        throw new NotFoundException();
    }

    /** /product/{slug} → ['noutbuq-pro-14'] կամ null */
    private function match(string $route, string $path): ?array
    {
        $pattern = '#^' . preg_replace('#\{[a-z_]+\}#', '([^/]+)', $route) . '$#u';

        if (!preg_match($pattern, $path, $matches)) {
            return null;
        }

        array_shift($matches);

        return array_map('urldecode', $matches);         // հայերեն slug-երի համար
    }
}
