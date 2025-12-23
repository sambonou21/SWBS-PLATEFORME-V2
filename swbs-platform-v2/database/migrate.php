&lt;?php
/**
 * Simple migration script for SWBS-PLATEFORME-V2
 * - Loads environment from .env (if present)
 * - Connects to MySQL
 * - Executes schema.sql
 */

declare(strict_types=1);

$root = dirname(__DIR__);
$envPath = $root . '/.env';

/**
 * Load .env file into $_ENV / $_SERVER
 */
function loadEnv(string $path): void
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

        // Remove surrounding quotes
        if ((str_starts_with($value, '"') &amp;&amp; str_ends_with($value, '"')) ||
            (str_starts_with($value, "'") &amp;&amp; str_ends_with($value, "'"))) {
            $value = substr($value, 1, -1);
        }

        $_ENV[$name] = $value;
        $_SERVER[$name] = $value;
        putenv($name . '=' . $value);
    }
}

loadEnv($envPath);

$dbHost = $_ENV['DB_HOST'] ?? '127.0.0.1';
$dbPort = (int)($_ENV['DB_PORT'] ?? 3306);
$dbUser = $_ENV['DB_USER'] ?? 'root';
$dbPass = $_ENV['DB_PASS'] ?? '';
$dbName = $_ENV['DB_NAME'] ?? 'swbs_platform_v2';

$dsn = sprintf('mysql:host=%s;port=%d;charset=utf8mb4', $dbHost, $dbPort);

echo "Connecting to MySQL at {$dbHost}:{$dbPort}...\n";

try {
    $pdo = new PDO($dsn, $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE            =&gt; PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE =&gt; PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    fwrite(STDERR, "Connection failed: " . $e-&gt;getMessage() . "\n");
    exit(1);
}

$schemaFile = __DIR__ . '/schema.sql';
if (!is_readable($schemaFile)) {
    fwrite(STDERR, "schema.sql not found in /database directory.\n");
    exit(1);
}

$sql = file_get_contents($schemaFile);
if ($sql === false) {
    fwrite(STDERR, "Unable to read schema.sql.\n");
    exit(1);
}

echo "Running schema.sql...\n";

try {
    // Replace database name with configured DB_NAME if needed
    if ($dbName !== 'swbs_platform_v2') {
        $sql = preg_replace('/CREATE DATABASE IF NOT EXISTS `swbs_platform_v2`/i', 'CREATE DATABASE IF NOT EXISTS `' . $dbName . '`', $sql);
        $sql = preg_replace('/USE `swbs_platform_v2`/i', 'USE `' . $dbName . '`', $sql);
    }

    $pdo-&gt;exec($sql);
    echo "Migration completed successfully.\n";
    echo "Database: {$dbName}\n";
} catch (PDOException $e) {
    fwrite(STDERR, "Migration failed: " . $e-&gt;getMessage() . "\n");
    exit(1);
}