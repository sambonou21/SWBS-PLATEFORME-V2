# SWBS-PLATEFORME-V2

Plateforme complète SWBS basée sur Node.js, Express, MySQL, Socket.IO, avec modules devis, chat temps réel, boutique, paiements FEDEPAY (intégration prévue) et assistant IA.

## 1. Prérequis

- Node.js 18+ (recommandé)
- MySQL 5.7+ / 8+
- Un accès SMTP (o2switch ou autre)
- Git / SSH pour déployer sur o2switch (optionnel)

## 2. Installation locale

```bash
git clone &lt;repo&gt; swbs-platform-v2
cd swbs-platform-v2

npm install

cp .env.example .env
# Éditez .env avec vos valeurs (MySQL, SMTP, FEDEPAY, IA, etc.)

npm run migrate   # applique src/db/schema.sql
npm run dev       # lance le serveur en mode développement
```

Le serveur écoute par défaut sur `http://localhost:3000`.

## 3. Scripts npm

- `npm run dev` : démarrage avec `nodemon` (rechargement auto).
- `npm run start` : démarrage classique `node src/server.js`.
- `npm run migrate` : exécute `src/db/migrate.js` (création / mise à jour du schéma).

## 4. Structure principale

- `src/server.js` : point d’entrée serveur HTTP + Socket.IO.
- `src/app.js` : configuration Express, middlewares, routes.
- `src/config/` : DB, sessions, mailer, sécurité, FEDEPAY, IA, i18n.
- `src/routes/` : routes API (auth, devis, chat, produits, commandes, admin, client, webhook).
- `src/services/` : logique métier (users, devis, boutique, settings, IA, etc.).
- `src/db/schema.sql` : schéma SQL complet.
- `public/` : assets front (HTML, CSS, JS, images, uploads).

## 5. Routes principales

### Public (pages)

- `/` : accueil
- `/services`
- `/portfolio`
- `/contact`
- `/devis`
- `/boutique`
- `/product.html?slug=...`
- `/login`
- `/register`
- `/verify-email`
- `/dashboard`
- `/chat` (popup chat)

### Auth API

- `POST /api/auth/register`
- `POST /api/auth/login`
- `POST /api/auth/logout`
- `GET /api/auth/verify-email?token=...`

### Devis

- `POST /api/quotes` (auth requis)
- `GET /api/quotes/client` (client)
- `GET /api/quotes/admin` (admin)
- `PATCH /api/quotes/admin/:id/status` (admin)

### Chat

- `POST /api/chat/lead`
- `POST /api/chat/start`
- `POST /api/chat/message`
- `GET /api/chat/:conversationId/messages`
- `GET /api/chat/admin/conversations` (admin)

Events Socket.IO principaux :

- `join` (room conversation / admins)
- `chat:message` (avec ACK)
- `chat:conversation:update`
- `quote:new` (pour notifier les admins – émis côté serveur après création de devis)

### Boutique / Commandes

- `GET /api/products`
- `GET /api/products/:slug`
- `GET /api/products/admin/list` (admin)
- `POST /api/products/admin` (admin, upload image)
- `PUT /api/products/admin/:id` (admin)
- `DELETE /api/products/admin/:id` (admin)
- `POST /api/products/admin/categories` (admin)
- `POST /api/orders` (client, créer commande)

### Admin

- `GET /api/admin/settings`
- `PATCH /api/admin/settings`
- `GET /api/admin/services` (CRUD via POST/PUT/DELETE, upload image)
- `GET /api/admin/portfolio` (CRUD via POST/PUT/DELETE, upload image)
- `GET /api/admin/clients`
- `GET /api/admin/orders`

### Webhook paiement

- `POST /api/webhook/fedapay`

## 6. Sécurité

- `helmet` pour les headers de sécurité.
- `csurf` pour CSRF (token exposé côté front).
- `express-rate-limit` pour limiter le nombre de requêtes.
- Sessions avec `express-session` + `express-mysql-session`.
- Uploads filtrés (mime types images seulement, taille max 5 Mo) + traitement Sharp.
- Rôles `user` / `admin` avec middlewares `requireAuth` et `requireAdmin`.

## 7. Multi-langue / Multi-devise

- i18n front : `/public/i18n/fr.json` et `en.json`, usage de `data-i18n` + `assets/js/i18n.js`.
- Sélecteur de devise (FCFA, NGN, USD, EUR) : `assets/js/currency.js`, et taux de change stockés dans `settings`.

## 8. Assistant IA

- Paramètres IA dans `settings` (admin).
- `aiService.shouldUseAi()` + `generateAiReply()` : réponse IA uniquement si admin absent et IA activée.
- Messages IA marqués `senderType = 'ai'` dans le chat.

## 9. Paiements FEDEPAY

- Config FEDEPAY dans `.env` + `settings`.
- Webhook `/api/webhook/fedapay` pour mettre à jour l’état des commandes.
- L’initiation de paiement (création du paiement côté FEDEPAY + redirection client) doit être branchée selon la version de l’API FEDEPAY utilisée (clé publique/privée, endpoints, etc.).

## 10. Tests rapides

1. Lancer MySQL et créer la base.
2. `npm run migrate`.
3. `npm run dev`.
4. Créer un compte via `/register`, vérifier l’email, puis se connecter.
5. Tester :
   - `/devis` (création devis)
   - `/chat` (chat user + admin)
   - `/boutique` (produits, panier, commandes)
   - `/admin/...` (dashboard, devis, chat, produits, commandes, services, portfolio, clients, settings).

Pour le déploiement détaillé sur o2switch, voir `README_DEPLOY_O2SWITCH.md`.