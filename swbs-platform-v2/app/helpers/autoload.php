&lt;?php
declare(strict_types=1);

/**
 * PSR-4 like autoloader for the application namespaces.
 *
 * Namespaces:
 * - App\Controllers
 * - App\Models
 * - App\Services
 * - App\Middlewares
 * - App\Helpers
 */

spl_autoload_register(function (string $class): void {
    $prefixes = [
        'App\\Controllers\\' =&gt; ABS_PATH_PROJECT . '/app/controllers/',
        'App\\Models\\'      =&gt; ABS_PATH_PROJECT . '/app/models/',
        'App\\Services\\'    =&gt; ABS_PATH_PROJECT . '/app/services/',
        'App\\Middlewares\\' =&gt; ABS_PATH_PROJECT . '/app/middlewares/',
        'App\\Helpers\\'     =&gt; ABS_PATH_PROJECT . '/app/helpers/',
    ];

    foreach ($prefixes as $prefix =&gt; $baseDir) {
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            continue;
        }

        $relativeClass = substr($class, $len);
        $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

        if (is_readable($file)) {
            require_once $file;
        }
    }
});