# MAP DU PROJET – SWBS-PLATEFORME-V2

## 1. Arborescence principale

```text
swbs-platform-v2/
  src/
    server.js
    app.js
    config/
      db.js
      sessionStore.js
      mailer.js
      fedapay.js
      ai.js
      i18n.js
      security.js
    middlewares/
      requireAuth.js
      requireAdmin.js
      csrf.js
      validate.js
      upload.js
      errorHandler.js
    routes/
      public.routes.js
      auth.routes.js
      quotes.routes.js
      chat.routes.js
      products.routes.js
      orders.routes.js
      admin.routes.js
      webhook.routes.js
      client.routes.js
    services/
      quoteService.js
      chatService.js
      productService.js
      orderService.js
      userService.js
      settingsService.js
      i18nService.js (placeholder possible)
      currencyService.js
      aiService.js
      serviceService.js
      portfolioService.js
    db/
      schema.sql
      migrate.js
  public/
    index.html
    pages/
      services.html
      portfolio.html
      contact.html
      devis.html
      boutique.html
      product.html
      login.html
      register.html
      verify-email.html
      dashboard.html
      chat.html
      admin/
        dashboard.html
        quotes.html
        chat.html
        products.html
        orders.html
        services.html
        portfolio.html
        clients.html
        settings.html
    assets/
      css/
        style.css
        admin.css
      js/
        app.js
        auth.js
        devis.js
        chat-widget.js
        chat-client.js
        admin.js
        admin-chat.js
        admin-products.js
        admin-quotes.js
        admin-orders.js
        admin-services.js (squelette)
        admin-portfolio.js (squelette)
        admin-clients.js (squelette)
        admin-settings.js
        boutique.js
        dashboard.js
        i18n.js
        currency.js
      img/
        (à fournir)
    uploads/
      services/
      portfolio/
      products/
  .env.example
  package.json
  README.md
  README_DEPLOY_O2SWITCH.md
  MAP_DU_PROJET.md
```

## 2. Fichiers créés (liste synthétique)

- **Backend**
  - `src/server.js`, `src/app.js`
  - `src/config/*.js` (db, sessions, mailer, fedapay, ai, i18n, security)
  - `src/middlewares/*.js` (auth, admin, csrf, validate, upload, error)
  - `src/routes/*.js` (public, auth, quotes, chat, products, orders, admin, webhook, client)
  - `src/services/*.js` (users, devis, chat, produits, commandes, settings, currency, ai, services, portfolio)
  - `src/db/schema.sql`, `src/db/migrate.js`

- **Frontend**
  - HTML : `public/index.html` + toutes les pages public/admin.
  - CSS : `public/assets/css/style.css`, `admin.css`.
  - JS : `public/assets/js/*.js` (navigation, auth, devis, chat, admin, boutique, dashboard, i18n, currency).

- **Docs**
  - `.env.example`
  - `README.md`
  - `README_DEPLOY_O2SWITCH.md`
  - `MAP_DU_PROJET.md`

## 3. Endpoints API

### Auth

- `POST /api/auth/register`
- `POST /api/auth/login`
- `POST /api/auth/logout`
- `GET /api/auth/verify-email?token=...`

### Quotes (Devis)

- `POST /api/quotes`  
  - Auth user
  - Body : `{ serviceId?, details, budget?, deadline?, extra? }`
- `GET /api/quotes/client`  
  - Auth user
- `GET /api/quotes/admin`  
  - Auth admin
- `PATCH /api/quotes/admin/:id/status`  
  - Auth admin
  - Body : `{ status: 'recu'|'en_cours'|'valide'|'refuse' }`

### Chat

- `POST /api/chat/lead`
  - Body : `{ name, email, phone? }`
  - Crée une conversation pour un lead anonyme.
- `POST /api/chat/start`
  - Auth user
  - Retourne une conversation existante ou en crée une nouvelle.
- `POST /api/chat/message`
  - Body : `{ conversationId, content }`
  - Enregistre un message (fallback HTTP).
- `GET /api/chat/:conversationId/messages`
- `GET /api/chat/admin/conversations`
  - Auth admin

### Client (dashboard)

- `GET /api/client/quotes`
- `GET /api/client/messages`
- `GET /api/client/orders`

### Produits / Boutique

- `GET /api/products`
- `GET /api/products/:slug`
- `GET /api/products/admin/list`
  - Auth admin
- `POST /api/products/admin`
  - Auth admin
  - FormData + champ `image`
- `PUT /api/products/admin/:id`
  - Auth admin
- `DELETE /api/products/admin/:id`
  - Auth admin
- `POST /api/products/admin/categories`
  - Auth admin

### Commandes

- `POST /api/orders`
  - Auth user
  - Body : `{ currency, items: [{ productId, qty }, ...] }`

### Admin

- `GET /api/admin/settings`
- `PATCH /api/admin/settings`
- `GET /api/admin/services`
- `POST /api/admin/services` (image upload)
- `PUT /api/admin/services/:id` (image upload possible)
- `DELETE /api/admin/services/:id`
- `GET /api/admin/portfolio`
- `POST /api/admin/portfolio` (image upload)
- `PUT /api/admin/portfolio/:id` (image upload possible)
- `DELETE /api/admin/portfolio/:id`
- `GET /api/admin/clients`
- `GET /api/admin/orders`

### Webhook paiement

- `POST /api/webhook/fedapay`
  - Body : `{ orderId, status, paymentRef, signature? }`
  - Met à jour `orders.status` (`paid` ou `cancelled`).

## 4. Events Socket.IO

- `connection`
- `join`
  - Payload : `{ conversationId?, role? }`
  - Rejoint :
    - `conv:{conversationId}`
    - room `admins` si `role === 'admin'`.
- `chat:message`
  - Payload : `{ conversationId, senderType, content }`
  - ACK : `{ ok: boolean, messageId?, error? }`
  - Diffusion :
    - `io.to('conv:{conversationId}').emit('chat:message', msg)`
    - `io.to('admins').emit('chat:conversation:update', { conversationId })`
  - Si `senderType === 'user'` et IA activée & admin absent :
    - Génère aussi un message `senderType='ai'`.

- `quote:new`
  - Émis côté serveur après création d’un devis (si `io` accessible).
  - Payload : `{ id, userId }`
  - Destinataires : room `admins`.

## 5. ASSETS

### 5.1. Noms de fichiers images (suggestion)

À placer dans `public/assets/img/` :

- `logo-swbs.svg`
- `logo-swbs-light.svg`
- `hero-dashboard.webp`
- `hero-chat.webp`
- `hero-devices.webp`
- `icon-webdev.svg`
- `icon-ecommerce.svg`
- `icon-branding.svg`
- `icon-analytics.svg`
- `avatar-admin.webp`
- `avatar-user.webp`

Ces assets sont optionnels pour la V1, mais recommandés pour un rendu professionnel.

### 5.2. Prompts pour générer les images

**1. logo-swbs.svg**

> Logo minimaliste pour une agence digitale nommée "SWBS", style moderne, lettres capitales géométriques, couleur principale bleu #1E66FF, fond transparent, version horizontale.  

**2. logo-swbs-light.svg**

> Variante du logo SWBS adaptée sur fond sombre, lettres blanches avec accent bleu #1E66FF, style moderne et épuré, fond transparent.  

**3. hero-dashboard.webp**

> Illustration isométrique d’un tableau de bord analytics moderne, avec cartes de statistiques, graphiques et notifications, palette sombre avec accents bleu #1E66FF, vert #18B26B et orange #FF8A00, style flat 3D.  

**4. hero-chat.webp**

> Illustration d’un chat en temps réel sur ordinateur et mobile, bulles de conversation, icône de notification en temps réel, fond sombre avec reflets lumineux, couleur principale bleu #1E66FF.  

**5. hero-devices.webp**

> Illustration d’un site web responsive affiché sur un smartphone, une tablette et un laptop, alignés en diagonale, style semi-flat, palette SWBS (#1E66FF, #18B26B, #FF8A00).  

**6. icon-webdev.svg**

> Icône vectorielle simple représentant un moniteur avec des chevrons `&lt;/&gt;`, style ligne claire, couleur bleu #1E66FF, fond transparent.  

**7. icon-ecommerce.svg**

> Icône vectorielle d’un panier d’achat stylisé, outline blanc avec accents orange #FF8A00, fond transparent, style moderne.  

**8. icon-branding.svg**

> Icône vectorielle représentant un crayon et une carte de visite, style minimal, couleur principale vert #18B26B.  

**9. icon-analytics.svg**

> Icône vectorielle d’un graphique en barres et d’une loupe, style flat, couleurs SWBS, fond transparent.  

**10. avatar-admin.webp / avatar-user.webp**

> Illustration d’avatars minimalistes (buste), style flat, un avatar pour administrateur (chemise bleu foncé, fond discret), et un avatar pour utilisateur (t-shirt clair), sur fond transparent ou cercle dégradé sombre.

## 6. Checklist de tests

### 6.1. Installation / backend

- [ ] `npm install` se termine sans erreur.
- [ ] `.env` rempli (MySQL, SMTP, SESSION_SECRET, UPLOAD_DIR, DOMAIN).
- [ ] `npm run migrate` s’exécute sans erreur.
- [ ] `GET /health` retourne `{ "status": "ok" }`.

### 6.2. Auth / comptes

- [ ] Inscription via `/register` fonctionne (user créé en DB).
- [ ] Email de vérification reçu (SMTP OK).
- [ ] Lien `/verify-email?token=...` vérifie l’email.
- [ ] Login refuse un compte non vérifié (`EMAIL_NOT_VERIFIED`).
- [ ] Login accepte après vérification.

### 6.3. Devis

- [ ] Sur `/devis`, si non connecté, le brouillon est sauvegardé et on est redirigé vers `/register?next=/devis`.
- [ ] Une fois connecté, envoi d’un devis crée une ligne en DB.
- [ ] Admin voit les devis dans `/admin/quotes`.
- [ ] Changement de statut sur `/admin/quotes` fonctionne.

### 6.4. Chat

- [ ] Le widget chat s’ouvre correctement en popup.
- [ ] Un user connecté peut envoyer des messages et les voir en temps réel.
- [ ] L’admin dans `/admin/chat` voit les conversations et messages.
- [ ] Si IA activée et admin absent, une réponse IA est ajoutée après le message user.
- [ ] Historique messages consultable dans `/dashboard` (section messages).

### 6.5. Boutique / commandes

- [ ] Un admin peut créer un produit avec image depuis `/admin/products`.
- [ ] Le produit apparaît dans `/boutique` (liste) et `product.html?slug=...`.
- [ ] Le panier localStorage fonctionne (ajout de produits).
- [ ] Création de commande via `POST /api/orders` (depuis front) fonctionne.
- [ ] Les commandes apparaissent pour le client (`/dashboard`) et pour l’admin (`/admin/orders`).

### 6.6. Uploads

- [ ] Upload produit crée bien des fichiers dans `/public/uploads/products`.
- [ ] Idem pour services (`/admin/services`) et portfolio (`/admin/portfolio`) lorsque les endpoints sont utilisés.
- [ ] Seules les images (jpg/png/webp) sont acceptées, taille max 5 Mo.
- [ ] Les miniatures `.webp` sont générées et le fichier originel est supprimé.

### 6.7. Settings / FEDEPAY / IA

- [ ] Les paramètres dans `/admin/settings` se chargent correctement.
- [ ] Modification des taux de change, FEDEPAY keys, IA instructions → sauvegardées et visibles en DB.
- [ ] Webhook `/api/webhook/fedapay` met à jour `orders.status` quand appelé avec un `orderId` valide.

### 6.8. Multi-langue / multi-devise

- [ ] Le sélecteur FR/EN change les textes traduits sur les pages supportées.
- [ ] Le choix de langue est mémorisé (localStorage + cookie).
- [ ] Le sélecteur de devise change la devise globale (évènement `swbs:currency-change` émis).
- [ ] Les taux de change peuvent être ajustés dans `/admin/settings`.

Une fois l’ensemble de cette checklist validé, la plateforme SWBS-PLATEFORME-V2 est considérée comme prête pour la mise en production.