&lt;?php
declare(strict_types=1);

/**
 * Global configuration loader for SWBS-PLATEFORME-V2
 */

$rootPath = dirname(__DIR__, 2);

if (!defined('ABS_PATH_PROJECT')) {
    define('ABS_PATH_PROJECT', $rootPath);
}

$envFile = $rootPath . '/.env';

/**
 * Load environment variables from .env file
 */
function swbs_load_env(string $path): void
{
    if (!is_readable($path)) {
        return;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($lines === false) {
        return;
    }

    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }

        [$name, $value] = array_pad(explode('=', $line, 2), 2, '');
        $name = trim($name);
        $value = trim($value);

        if ($name === '') {
            continue;
        }

        if ((str_starts_with($value, '"') &amp;&amp; str_ends_with($value, '"')) ||
            (str_starts_with($value, "'") &amp;&amp; str_ends_with($value, "'"))) {
            $value = substr($value, 1, -1);
        }

        $_ENV[$name] = $value;
        $_SERVER[$name] = $value;
        putenv($name . '=' . $value);
    }
}

swbs_load_env($envFile);

/**
 * App configuration array
 */
return [
    'app' =&gt; [
        'env'         =&gt; $_ENV['APP_ENV'] ?? 'production',
        'debug'       =&gt; (bool)($_ENV['APP_DEBUG'] ?? false),
        'domain'      =&gt; $_ENV['DOMAIN'] ?? ($_SERVER['HTTP_HOST'] ?? 'localhost'),
        'base_url'    =&gt; ($_ENV['DOMAIN'] ?? '') ? ('https://' . ($_ENV['DOMAIN'] ?? 'localhost')) : '',
        'timezone'    =&gt; $_ENV['APP_TIMEZONE'] ?? 'UTC',
        'locale'      =&gt; $_ENV['APP_LOCALE'] ?? 'fr',
    ],
    'db' =&gt; [
        'host' =&gt; $_ENV['DB_HOST'] ?? '127.0.0.1',
        'port' =&gt; (int)($_ENV['DB_PORT'] ?? 3306),
        'name' =&gt; $_ENV['DB_NAME'] ?? 'swbs_platform_v2',
        'user' =&gt; $_ENV['DB_USER'] ?? 'root',
        'pass' =&gt; $_ENV['DB_PASS'] ?? '',
    ],
    'mail' =&gt; [
        'host'      =&gt; $_ENV['SMTP_HOST'] ?? '',
        'port'      =&gt; (int)($_ENV['SMTP_PORT'] ?? 587),
        'user'      =&gt; $_ENV['SMTP_USER'] ?? '',
        'pass'      =&gt; $_ENV['SMTP_PASS'] ?? '',
        'from'      =&gt; $_ENV['MAIL_FROM'] ?? 'no-reply@localhost',
        'from_name' =&gt; $_ENV['MAIL_FROM_NAME'] ?? 'SWBS Plateforme',
    ],
    'fedepay' =&gt; [
        'public' =&gt; $_ENV['FEDEPAY_PUBLIC'] ?? '',
        'secret' =&gt; $_ENV['FEDEPAY_SECRET'] ?? '',
    ],
    'ai' =&gt; [
        'provider' =&gt; $_ENV['AI_PROVIDER'] ?? '',
        'api_key'  =&gt; $_ENV['AI_API_KEY'] ?? '',
    ],
];