# SWBS-PLATEFORME-V2 (Sam Web Business Services)

Sam Web Business Services (SWBS) est une plateforme digitale tout‑en‑un permettant aux entreprises de présenter leurs services, gérer leurs clients, automatiser leurs devis, communiquer en temps réel et vendre leurs produits.

Ce projet est une application **Laravel 11** complète avec :

- Front‑office (site public, devis, boutique, chat)
- Dashboard client
- Back‑office administrateur
- API REST (services, portfolio, produits, chat, devis)
- Chat temps réel (Reverb / Pusher‑like)
- Multi‑langue (FR/EN)
- Multi‑devise (FCFA, NGN, USD, EUR)
- Intégration paiement FedePay (pré‑intégrée, à configurer)

---

## 1. Prérequis

- PHP **8.2+**
- Composer **2+**
- MySQL **5.7+ / 8+**
- Node.js **18+** et npm
- Extensions PHP recommandées :
  - `pdo_mysql`
  - `openssl`
  - `mbstring`
  - `curl`
  - `gd` ou `imagick` (pour Intervention Image)
- Serveur web : Apache ou Nginx

---

## 2. Installation locale

Depuis votre terminal :

```bash
git clone &lt;votre-repo&gt; swbs-platform-v2
cd swbs-platform-v2

composer install
npm install
```

Copiez le fichier d’environnement et générez la clé applicative :

```bash
cp .env.example .env
php artisan key:generate
```

Configurez ensuite votre base MySQL dans `.env` si besoin (variables `MYSQL_*`).

Créez la base de données si elle n’existe pas :

```sql
CREATE DATABASE sc4assa9716_swbsbase CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Lancez les migrations + seeders :

```bash
php artisan migrate --seed
```

Cela va notamment créer :

- Les tables fonctionnelles (users, services, portfolio, quotes, conversations, messages, products, orders, settings…)
- Les services, produits et réalisations SWBS
- Le compte administrateur par défaut :
  - **Email** : `admin@swbs.site`
  - **Mot de passe** : `P@rdondieu12+` (modifiez‑le après connexion)

---

## 3. Lancer l’application

En développement :

```bash
php artisan serve
```

Puis ouvrez votre navigateur sur `http://localhost:8000`.

Pour les assets (CSS/JS), il n’est **pas obligatoire** d’utiliser Vite en local car ce projet charge les fichiers statiques depuis `public/assets`. Vous pouvez néanmoins lancer :

```bash
npm run dev
```

si vous souhaitez exploiter Vite ou enrichir le front.

---

## 4. Authentification & rôles

- Inscription / connexion / réinitialisation de mot de passe
- Vérification d’email (obligatoire pour accéder au dashboard client)
- Rôles :
  - `user` : client
  - `admin` : accès au back‑office

Les routes admin sont protégées par le middleware `role:admin`.

---

## 5. Multi‑langue

- Langue par défaut : **FR**
- Langue alternative : **EN**
- Fichiers de traductions :
  - `resources/lang/fr.json`
  - `resources/lang/en.json`
- Un sélecteur de langue est disponible dans le header ; la langue est mémorisée en session.

---

## 6. Multi‑devise

- Devises supportées : **FCFA, NGN, USD, EUR**
- Devise de base : **FCFA**
- Les taux de conversion sont stockés dans la table `settings` (groupe `currency`) et initialisés par le `SettingsSeeder`.
- Un sélecteur de devise est disponible dans le header ; la devise est mémorisée en session.
- La conversion est gérée par `App\Services\CurrencyService`.

---

## 7. Chat temps réel

Le chat est disponible :

- Côté public via un **widget flottant** sur toutes les pages (`&lt;x-chat-widget /&gt;` dans le layout).
- Côté admin via une interface (controllers admin à compléter si nécessaire).

Fonctionnement :

- Si l’utilisateur est connecté, la conversation est liée à son compte.
- Si l’utilisateur est invité, une conversation de type « prospect » est créée (session).
- Les messages sont stockés en BD (`conversations`, `messages`).
- Un service d’IA (`App\Services\AiChatService`) peut répondre automatiquement si la configuration IA est renseignée.

Pour une vraie diffusion temps réel (WebSockets) :

1. Installer et configurer **Laravel Reverb** (ou un équivalent) :

   ```bash
   composer require laravel/reverb
   php artisan reverb:install
   ```

2. Mettre `BROADCAST_CONNECTION=reverb` dans `.env`.
3. Lancer le serveur Reverb :

   ```bash
   php artisan reverb:start
   ```

Le projet expose déjà l’événement `App\Events\ChatMessageSent` diffusé sur le canal privé `conversations.{id}`.

---

## 8. Paiement FedePay

Le service `App\Services\FedepayService` centralise :

- La création d’un paiement FedePay pour une commande (`createPayment`)
- La vérification d’un paiement (`verifyPayment`)

Configurez dans `.env` :

```env
FEDEPAY_PUBLIC=&lt;votre_cle_publique&gt;
FEDEPAY_SECRET=&lt;votre_cle_secrete&gt;
FEDEPAY_MODE=sandbox # ou live
```

Les appels exacts à l’API FedePay pourront être adaptés selon la documentation officielle.

---

## 9. Structure principale

Détails dans `MAP_DU_PROJET.md`. Résumé :

- `app/Models` : modèles (User, Service, Portfolio, Quote, Conversation, Message, Category, Product, Order, OrderItem, Setting)
- `app/Services` : CurrencyService, SettingsService, ImageService, ChatService, AiChatService, FedepayService
- `app/Events` : ChatMessageSent
- `app/Http/Controllers` :
  - Front : Home, Service, Portfolio, Shop, Quote, Contact, Dashboard
  - Auth : Login, Register, Verification, ForgotPassword, ResetPassword
  - Chat : ChatController
  - API : CatalogApiController, QuoteApiController, AuthApiController, ChatApiController
  - (Admin : à étendre pour la gestion fine des contenus)
- `database/migrations` : toutes les tables nécessaires
- `database/seeders` : données SWBS réelles (services, portfolio, produits, settings, admin)
- `resources/views` :
  - Layout public + admin
  - Pages d’accueil, services, portfolio, boutique, contact, devis, dashboard, auth
- `public/assets/css/app.css` : design complet (palette, nav, cartes, chat)
- `public/assets/js/app.js` : burger menu, panier localStorage, widget de chat (fallback HTTP)

---

## 10. Compte administrateur

Par défaut (via seeders) :

- Email : `admin@swbs.site`
- Mot de passe : `P@rdondieu12+`

Vous pouvez modifier ces valeurs via les variables d’environnement :

```env
ADMIN_EMAIL=admin@swbs.site
ADMIN_PASSWORD=P@rdondieu12+
```

Après déploiement, **changez le mot de passe** immédiatement depuis l’espace administrateur.

---

## 11. Notes

- Ce projet vise à être **clé en main** : après `composer install`, `npm install` et `php artisan migrate --seed`, vous obtenez une plateforme exploitable.
- Vous pouvez enrichir le front (Vite, Tailwind…) en utilisant la configuration fournie dans `package.json`, ou rester sur les assets statiques fournis.
- L’intégration fine de Reverb / Pusher et l’interface d’admin complète (CRUD visuels) peuvent être détaillées et personnalisées selon vos besoins.

Pour les détails d’hébergement chez o2switch, voir `README_DEPLOY_O2SWITCH.md`.