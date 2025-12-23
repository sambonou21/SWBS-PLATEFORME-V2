&lt;?php
declare(strict_types=1);

use App\Helpers\Router;

require_once dirname(__DIR__) . '/app/config/config.php';

$config = require dirname(__DIR__) . '/app/config/config.php';

if (!defined('ABS_PATH_PROJECT')) {
    define('ABS_PATH_PROJECT', dirname(__DIR__));
}

// Set timezone
date_default_timezone_set($config['app']['timezone'] ?? 'UTC');

// Start secure session
ini_set('session.use_strict_mode', '1');
ini_set('session.use_only_cookies', '1');
session_start([
    'cookie_httponly' =&gt; true,
    'cookie_secure'   =&gt; isset($_SERVER['HTTPS']) &amp;&amp; $_SERVER['HTTPS'] === 'on',
    'cookie_samesite' =&gt; 'Lax',
]);

require_once ABS_PATH_PROJECT . '/app/helpers/autoload.php';
require_once ABS_PATH_PROJECT . '/app/helpers/Router.php';

$router = new Router();

/**
 * Basic route declarations.
 * Later we will connect them to proper Controllers.
 */

// Home - redirect to services for now
$router-&gt;get('/', function () {
    header('Location: /pages/services.php');
    exit;
});

// Static page fallbacks during early scaffolding
$router-&gt;get('/services', function () {
    require ABS_PATH_PROJECT . '/public/pages/services.php';
});

$router-&gt;get('/portfolio', function () {
    require ABS_PATH_PROJECT . '/public/pages/portfolio.php';
});

$router-&gt;get('/contact', function () {
    require ABS_PATH_PROJECT . '/public/pages/contact.php';
});

$router-&gt;get('/devis', function () {
    require ABS_PATH_PROJECT . '/public/pages/devis.php';
});

$router-&gt;get('/boutique', function () {
    require ABS_PATH_PROJECT . '/public/pages/boutique.php';
});

$router-&gt;get('/product/{slug}', function (array $params) {
    $_GET['slug'] = $params['slug'] ?? null;
    require ABS_PATH_PROJECT . '/public/pages/product.php';
});

$router-&gt;get('/login', function () {
    require ABS_PATH_PROJECT . '/public/pages/login.php';
});

$router-&gt;get('/register', function () {
    require ABS_PATH_PROJECT . '/public/pages/register.php';
});

$router-&gt;get('/verify-email', function () {
    require ABS_PATH_PROJECT . '/public/pages/verify-email.php';
});

$router-&gt;get('/dashboard', function () {
    require ABS_PATH_PROJECT . '/public/pages/dashboard.php';
});

$router-&gt;get('/chat', function () {
    require ABS_PATH_PROJECT . '/public/pages/chat.php';
});

// Admin routes (basic placeholders for now)
$router-&gt;get('/admin', function () {
    require ABS_PATH_PROJECT . '/public/admin/dashboard.php';
});

$router-&gt;get('/admin/dashboard', function () {
    require ABS_PATH_PROJECT . '/public/admin/dashboard.php';
});

$router-&gt;dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', $_SERVER['REQUEST_URI'] ?? '/');