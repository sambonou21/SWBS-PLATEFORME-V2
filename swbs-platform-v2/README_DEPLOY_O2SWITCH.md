# Déploiement SWBS-PLATEFORME-V2 sur o2switch (Passenger + Node.js + MySQL)

Ce guide explique comment déployer la plateforme SWBS-PLATEFORME-V2 sur un hébergement o2switch avec Passenger (Node.js) et MySQL.

## 1. Pré-requis côté o2switch

- Accès cPanel o2switch.
- Base MySQL créée (nom, user, mot de passe).
- Domaine ou sous-domaine pointant vers le dossier du site (par ex. `swbs.site`).
- Node.js activé via Passenger (Ruby/NodeJS Apps) ou via `.htaccess`.

## 2. Structure recommandée

Exemple pour le domaine `swbs.site` :

```
/home/USER/
  swbs.site/
    public/            # racine publique du site (DocumentRoot)
    swbs-platform-v2/  # projet Node.js
```

Dans ce guide, on suppose :

- `ABS_PATH_PROJECT=/home/USER/swbs.site/swbs-platform-v2`
- `UPLOAD_DIR=/home/USER/swbs.site/public/uploads`

Adaptez `USER` à votre compte cPanel.

## 3. Déploiement des fichiers

1. Uploadez l’archive du projet (zip) dans `/home/USER/swbs.site/`.
2. Décompressez-la pour obtenir `/home/USER/swbs.site/swbs-platform-v2`.
3. Assurez-vous que le dossier `public/` existe à la racine du domaine (sinon créez-le) et que le VirtualHost pointe vers ce dossier (par défaut c’est le cas pour le domaine principal).

## 4. Installation des dépendances Node.js

Connectez-vous en SSH :

```bash
cd /home/USER/swbs.site/swbs-platform-v2

node -v          # vérifier que Node.js est présent
npm install      # installe toutes les dépendances du projet
```

## 5. Configuration `.env`

À partir du modèle :

```bash
cd /home/USER/swbs.site/swbs-platform-v2
cp .env.example .env
```

Éditez `.env` avec vos paramètres :

- **Environnement / URLs :**

```env
NODE_ENV=production
PORT=3000

BASE_URL=https://swbs.site
APP_BASE_URL=https://swbs.site
DOMAIN=https://swbs.site
```

- **MySQL :**

```env
DB_DIALECT=mysql

MYSQL_HOST=localhost
MYSQL_PORT=3306
MYSQL_DATABASE=VOTRE_BASE
MYSQL_USER=VOTRE_USER
MYSQL_PASSWORD=VOTRE_MOTDEPASSE
```

Les alias `DB_HOST`, `DB_NAME`, etc. sont déduits automatiquement si non définis.

- **Uploads (obligatoire) :**

```env
UPLOAD_DIR=/home/USER/swbs.site/public/uploads
```

Créez le dossier si nécessaire :

```bash
mkdir -p /home/USER/swbs.site/public/uploads/services
mkdir -p /home/USER/swbs.site/public/uploads/portfolio
mkdir -p /home/USER/swbs.site/public/uploads/products
```

Vérifiez que ce dossier est accessible en écriture par le process web.

- **Sessions :**

```env
SESSION_SECRET=une_chaine_longue_et_secrete
SESSION_NAME=swbs_sid
SESSION_COOKIE_SECURE=true
SESSION_COOKIE_MAX_AGE_DAYS=7
```

- **SMTP :**

```env
SMTP_HOST=mail.swbs.site
SMTP_PORT=465
SMTP_SECURE=true
SMTP_USER=no-reply@swbs.site
SMTP_PASS=VOTRE_MOTDEPASSE_SMTP
MAIL_FROM=SWBS <no-reply@swbs.site>
```

- **FEDEPAY / IA** (optionnels, peuvent rester vides au départ) :

```env
FEDEPAY_PUBLIC=
FEDEPAY_SECRET=
FEDEPAY_MODE=sandbox

AI_PROVIDER=
AI_API_KEY=
AI_MODEL=gpt-4o-mini
AI_INSTRUCTIONS=
```

## 6. Initialisation de la base de données

Toujours en SSH :

```bash
cd /home/USER/swbs.site/swbs-platform-v2
npm run migrate
```

Cela exécute `src/db/schema.sql` et crée toutes les tables nécessaires.

## 7. Configuration Passenger (Node.js)

### Option 1 : via Application NodeJS (cPanel)

Selon la configuration o2switch, vous pouvez :

- Créer une application NodeJS dans cPanel.
- Indiquer :
  - Dossier de l’application : `/home/USER/swbs.site/swbs-platform-v2`
  - Fichier de démarrage : `src/server.js`
- Définir `PORT=3000` et les autres variables dans `.env`.

### Option 2 : via `.htaccess` + `passenger_app_root`

Dans `/home/USER/swbs.site/public/.htaccess` :

```apacheconf
PassengerEnabled on
PassengerAppRoot /home/USER/swbs.site/swbs-platform-v2
PassengerAppType node
PassengerStartupFile src/server.js

# Optionnel : forcer HTTPS
RewriteEngine On
RewriteCond %{HTTPS} !=on
RewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

Assurez-vous que `PassengerAppRoot` pointe bien vers le dossier du projet Node.

## 8. Démarrage / redémarrage de l’application

Passenger relance automatiquement l’application lorsqu’un fichier est modifié dans le projet, ou lorsque vous touchez le fichier `tmp/restart.txt`.

Pour forcer un redémarrage :

```bash
cd /home/USER/swbs.site/swbs-platform-v2
mkdir -p tmp
touch tmp/restart.txt
```

## 9. Tests de base

1. Accédez à `https://swbs.site/health` : vous devez obtenir `{ "status": "ok" }`.
2. Ouvrez `https://swbs.site/` :
   - vérifier que la page d’accueil s’affiche.
3. Inscription + vérification email :
   - `https://swbs.site/register`
   - vérifiez que l’email de vérification est reçu et fonctionne.
4. Connexion + accès `/dashboard`.

## 10. Gestion des uploads

Les uploads sont gérés par Multer + Sharp :

- Dossier racine : `UPLOAD_DIR` (ex. `/home/USER/swbs.site/public/uploads`)
- Sous-dossiers :
  - `/services`
  - `/portfolio`
  - `/products`
- Fichiers images :
  - acceptés : `jpg`, `png`, `webp`
  - taille max : 5 Mo
  - conversion en `webp` + thumbnail automatique

Vérifiez que les permissions sur ces dossiers sont adaptées (`755` ou `775` selon votre setup).

## 11. Checklist finale

- [ ] `.env` correctement rempli (MySQL, SMTP, SESSION_SECRET, UPLOAD_DIR, DOMAIN).
- [ ] `npm install` exécuté sans erreur.
- [ ] `npm run migrate` exécuté sans erreur.
- [ ] Passenger/App NodeJS configuré avec `src/server.js`.
- [ ] Test `/health` OK.
- [ ] Test d’inscription + email + connexion OK.
- [ ] Accès admin (`/admin/...`) réservé à un compte admin (à créer manuellement dans la base ou via script).
- [ ] Upload produits/services/portfolio fonctionnel (images créées dans `/public/uploads/...`).
- [ ] (optionnel) FEDEPAY configuré, webhook `/api/webhook/fedapay` testé.

Une fois ces étapes validées, la plateforme SWBS-PLATEFORME-V2 est prête pour la production sur o2switch.