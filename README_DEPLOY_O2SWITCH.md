# Déploiement SWBS-PLATEFORME-V2 sur o2switch

Ce guide explique comment déployer la plateforme **Sam Web Business Services (SWBS)** sur un hébergement mutualisé **o2switch** (cPanel).

---

## 1. Pré-requis côté o2switch

- Un hébergement actif avec accès **cPanel**
- Un domaine ou sous-domaine pointant sur votre hébergement (ex. `swbs.site`)
- PHP **8.2** disponible (géré par o2switch)
- Accès SSH recommandé (mais possible sans)

---

## 2. Préparation locale (optionnel mais recommandé)

En local :

```bash
git clone &lt;votre-repo&gt; swbs-platform-v2
cd swbs-platform-v2

composer install --no-dev
npm install
npm run build
```

Les assets compilés seront générés (si vous utilisez Vite).  
Sinon, les fichiers `public/assets/css/app.css` et `public/assets/js/app.js` fournis suffisent.

Vous pouvez ensuite :

- Versionner le projet
- Ou générer une archive `.zip` prête à être uploadée.

---

## 3. Création de la base MySQL sur o2switch

1. Connectez-vous à **cPanel**.
2. Allez dans **MySQL® Databases**.
3. Créez :
   - Une base de données : `sc4assa9716_swbsbase`
   - Un utilisateur MySQL : `sc4assa9716_swbsbase`
   - Mot de passe : `P@rdondieu12+` (ou un nouveau mot de passe plus robuste)
4. Associez l’utilisateur à la base avec **tous les privilèges**.

---

## 4. Déploiement des fichiers

### Option A – Upload via FTP / FileManager

1. Dans cPanel, ouvrez **Gestionnaire de fichiers**.
2. Rendez-vous dans le dossier du site (ex. `public_html` ou `public_html/swbs` suivant votre configuration).
3. Uploadez l’archive `.zip` du projet (par ex. `swbs-platform-v2.zip`).
4. Extrayez le contenu dans un dossier, par ex. `swbs-platform-v2`.
5. Organisation recommandée :

   ```
   /home/USER/
       swbs-platform-v2/     (code Laravel)
       public_html/          (racine web)
           index.php         (proxy vers Laravel public/)
   ```

6. Déplacez le contenu du dossier `public` de Laravel ( `swbs-platform-v2/public` ) dans `public_html` ou créez un lien symbolique via SSH.

### Option B – Git + SSH (si disponible)

1. Activez l’accès **SSH** depuis cPanel.
2. Depuis votre machine, ajoutez la clé SSH dans cPanel.
3. Connectez-vous en SSH :

   ```bash
   ssh user@votre-domaine
   ```

4. Dans `/home/USER` :

   ```bash
   git clone &lt;votre-repo&gt; swbs-platform-v2
   cd swbs-platform-v2
   composer install --no-dev --optimize-autoloader
   ```

5. Configurez le dossier `public` pour qu’il corresponde à `public_html` (voir plus bas).

---

## 5. Configuration du DocumentRoot

### Cas simple : Laravel directement dans `public_html`

- Copiez **tout le contenu** de `swbs-platform-v2/public` dans `public_html`.
- Dans `public_html/index.php`, mettez à jour les chemins si besoin :

```php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
```

Assurez-vous que le chemin relatif vers `vendor` et `bootstrap` est correct (`../` depuis `public_html`).

### Cas plus propre : dossier app séparé

1. Laissez `swbs-platform-v2` à la racine utilisateur.
2. Dans `public_html/index.php` :

```php
require __DIR__.'/../swbs-platform-v2/vendor/autoload.php';
$app = require_once __DIR__.'/../swbs-platform-v2/bootstrap/app.php';
```

3. Vérifiez également les chemins des assets si nécessaire (`APP_URL` dans `.env`).

---

## 6. Configuration de l’environnement (.env)

Sur le serveur :

1. Copiez `.env.example` en `.env` dans `swbs-platform-v2`.
2. Éditez `.env` (via SSH, cPanel ou FTP) :

```env
APP_NAME="Sam Web Business Services"
APP_ENV=production
APP_KEY=  # généré via artisan
APP_DEBUG=false
APP_URL=https://swbs.site
BASE_URL=https://swbs.site

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=sc4assa9716_swbsbase
DB_USERNAME=sc4assa9716_swbsbase
DB_PASSWORD=P@rdondieu12+

SESSION_DRIVER=database
QUEUE_CONNECTION=database

# SMTP / EMAIL
MAIL_MAILER=smtp
MAIL_HOST=mail.swbs.site
MAIL_PORT=465
MAIL_USERNAME=no-reply@swbs.site
MAIL_PASSWORD=P@rdondieu12+BSS
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS="no-reply@swbs.site"
MAIL_FROM_NAME="SWBS"

# FEDEPAY
FEDEPAY_PUBLIC=&lt;votre_cle_publique&gt;
FEDEPAY_SECRET=&lt;votre_cle_secrete&gt;
FEDEPAY_MODE=live # ou sandbox

# IA
AI_PROVIDER=openai    # ou un autre provider compatible
AI_API_KEY=&lt;votre_cle_ia&gt;
AI_MODEL=gpt-4o-mini
AI_INSTRUCTIONS="Tu es l'assistant conversationnel de la plateforme SWBS..."

# Admin
ADMIN_EMAIL=admin@swbs.site
ADMIN_PASSWORD=P@rdondieu12+
```

3. Générez la clé applicative :

   ```bash
   php artisan key:generate --ansi
   ```

4. Lancez les migrations et seeders sur le serveur :

   ```bash
   php artisan migrate --force
   php artisan db:seed --force
   ```

---

## 7. Permissions

Assurez-vous que les dossiers suivants sont inscriptibles :

- `storage/`
- `bootstrap/cache/`
- `public/uploads/` (et sous-dossiers)

Via SSH :

```bash
cd ~/swbs-platform-v2
chmod -R 755 storage bootstrap/cache
chmod -R 755 public/uploads
```

(o2switch interdit généralement les `777`, restez sur `755`)

---

## 8. Cron / Queue (facultatif mais recommandé)

Pour le traitement des mails, jobs et éventuels traitements différés, configurez un cron job dans cPanel :

```bash
* * * * * /usr/local/bin/php /home/USER/swbs-platform-v2/artisan schedule:run >> /dev/null 2>&1
```

Pour les jobs de queue (si vous utilisez `QUEUE_CONNECTION=database`), vous pouvez lancer un worker via `screen` ou `Supervisor` si disponible. Sur mutualisé, vous pouvez aussi utiliser `php artisan queue:work --stop-when-empty` dans un cron.

---

## 9. WebSockets / Reverb sur o2switch

L’hébergement mutualisé limite fortement l’usage de WebSockets persistants.

Recommandation :

- Utiliser la **version HTTP fallback** du chat (déjà opérationnelle avec le widget).
- Pour une vraie diffusion temps réel (Reverb / Pusher), privilégiez :
  - un petit VPS externe dédié au serveur Reverb, ou
  - un service Pusher‑like managé.

Dans tous les cas, le code de la plateforme SWBS est prêt à fonctionner avec un driver de broadcast type Reverb/Pusher grâce à l’événement `ChatMessageSent`.

---

## 10. Vérifications après déploiement

1. Accéder à `https://swbs.site`
2. Tester :
   - La page d’accueil
   - Inscription / connexion
   - Vérification email (si emails configurés)
   - Envoi d’une demande de devis
   - Chat (ouverture du widget, envoi d’un message)
   - Accès admin : `/admin` avec `admin@swbs.site` / mot de passe configuré
3. Vérifier les logs en cas d’erreur :

   ```bash
   tail -f storage/logs/laravel.log
   ```

---

## 11. Sécurité & bonnes pratiques

- Désactivez `APP_DEBUG` en production (`APP_DEBUG=false`).
- Changez le mot de passe admin dès la première connexion.
- Gardez votre dépôt à jour, appliquez les mises à jour Laravel régulièrement (`composer update` sur un environnement de pré‑production avant la prod).
- Limitez l’exposition des commandes Artisan sur le web (utiliser SSH).

---

Avec ces étapes, votre plateforme **SWBS-PLATEFORME-V2** est prête à fonctionner en production sur o2switch.  
Pour tout ajustement (personnalisation design, nouveaux modules, intégration d’outils tiers), le code est structuré pour être facilement extensible.