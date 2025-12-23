&lt;?php
declare(strict_types=1);

$slug = $_GET['slug'] ?? '';
?&gt;
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Produit &lt;?= htmlspecialchars($slug, ENT_QUOTES, 'UTF-8') ?&gt; - SWBS Plateforme</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<header class="site-header">
    <div class="container">
        <h1>Fiche produit</h1>
    </div>
</header>
<main class="container">
    <p>Produit : &lt;?= htmlspecialchars($slug, ENT_QUOTES, 'UTF-8') ?&gt;</p>
    <p>La fiche détaillée sera connectée à la base de données plus tard.</p>
</main>
</body>
</html>