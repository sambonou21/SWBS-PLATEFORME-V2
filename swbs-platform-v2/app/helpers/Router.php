&lt;?php
declare(strict_types=1);

namespace App\Helpers;

/**
 * Very small router implementation.
 * Supports GET/POST routes and basic path parameters (/product/{slug}).
 */
class Router
{
    private array $routes = [
        'GET' =&gt; [],
        'POST' =&gt; [],
    ];

    public function get(string $path, callable $handler): void
    {
        $this-&gt;addRoute('GET', $path, $handler);
    }

    public function post(string $path, callable $handler): void
    {
        $this-&gt;addRoute('POST', $path, $handler);
    }

    private function addRoute(string $method, string $path, callable $handler): void
    {
        $this-&gt;routes[$method][] = [
            'path'    =&gt; $path,
            'handler' =&gt; $handler,
            'pattern' =&gt; $this-&gt;compilePathToRegex($path),
        ];
    }

    private function compilePathToRegex(string $path): string
    {
        $pattern = preg_replace('#\{([a-zA-Z_][a-zA-Z0-9_-]*)\}#', '(?P&lt;$1&gt;[^/]+)', $path);
        return '#^' . $pattern . '$#';
    }

    public function dispatch(string $method, string $uri): void
    {
        $path = parse_url($uri, PHP_URL_PATH) ?? '/';
        $method = strtoupper($method);

        $routes = $this-&gt;routes[$method] ?? [];

        foreach ($routes as $route) {
            if (preg_match($route['pattern'], $path, $matches)) {
                $params = [];
                foreach ($matches as $key =&gt; $value) {
                    if (!is_int($key)) {
                        $params[$key] = $value;
                    }
                }

                call_user_func($route['handler'], $params);
                return;
            }
        }

        http_response_code(404);
        echo '404 Not Found';
    }
}