# SWBS-PLATEFORME-V2 – Carte du projet

## 1. Routes principales

### Front-office (web.php)

- `GET /` → `Front\HomeController@index` (Accueil)
- `GET /services` → `Front\ServiceController@index`
- `GET /services/{slug}` → `Front\ServiceController@show`
- `GET /portfolio` → `Front\PortfolioController@index`
- `GET /portfolio/{slug}` → `Front\PortfolioController@show`
- `GET /boutique` → `Front\ShopController@index`
- `GET /boutique/{slug}` → `Front\ShopController@show`
- `GET /contact` → `Front\ContactController@index`
- `POST /contact` → `Front\ContactController@submit`
- `GET /devis` → `Front\QuoteController@create`
- `POST /devis` → `Front\QuoteController@store`
- `POST /lang` → `LocaleController@switch`
- `POST /currency` → `CurrencyController@switch`

### Auth & compte client

- `GET /connexion` → `Auth\LoginController@showLoginForm`
- `POST /connexion` → `Auth\LoginController@login`
- `GET /inscription` → `Auth\RegisterController@showRegistrationForm`
- `POST /inscription` → `Auth\RegisterController@register`
- `POST /deconnexion` → `Auth\LoginController@logout`
- `GET /mot-de-passe/oubli` → `Auth\ForgotPasswordController@showLinkRequestForm`
- `POST /mot-de-passe/email` → `Auth\ForgotPasswordController@sendResetLinkEmail`
- `GET /mot-de-passe/reinitialisation/{token}` → `Auth\ResetPasswordController@showResetForm`
- `POST /mot-de-passe/reinitialisation` → `Auth\ResetPasswordController@reset`
- `GET /email/verification` → `Auth\VerificationController@notice`
- `GET /email/verification/{id}/{hash}` → `Auth\VerificationController@verify`
- `POST /email/verification/notification` → `Auth\VerificationController@send`
- `GET /dashboard` → `Front\DashboardController@index`
- `GET /dashboard/commandes` → `Front\DashboardController@orders`
- `GET /dashboard/devis` → `Front\DashboardController@quotes`
- `GET /dashboard/profil` → `Front\DashboardController@profile`
- `POST /dashboard/profil` → `Front\DashboardController@updateProfile`

### Chat temps réel (web)

- `POST /chat/start` → `Chat\ChatController@start`
- `POST /chat/message` → `Chat\ChatController@send`
- `GET /chat/conversation/{conversation}` → `Chat\ChatController@fetch`

### Back-office admin (`/admin`)

- `GET /admin` → `Admin\AdminDashboardController@index`
- Ressources :
  - `Admin\ServiceController` (CRUD services)
  - `Admin\PortfolioController` (CRUD portfolio)
  - `Admin\CategoryController` (CRUD catégories)
  - `Admin\ProductController` (CRUD produits)
  - `Admin\OrderController` (index, show, update)
  - `Admin\QuoteController` (index, show, update, destroy)
  - `Admin\ClientController` (index, show)
  - `Admin\AdminUserController` (CRUD comptes admin)
- Chat admin :
  - `GET /admin/chat` → `Admin\ChatController@index`
  - `GET /admin/chat/{conversation}` → `Admin\ChatController@show`
  - `POST /admin/chat/{conversation}/reply` → `Admin\ChatController@reply`
- Paramètres :
  - `GET /admin/settings` → `Admin\SettingsController@index`
  - `POST /admin/settings` → `Admin\SettingsController@update`
  - `POST /admin/settings/currencies` → `Admin\SettingsController@updateCurrencies`
  - `POST /admin/settings/ai` → `Admin\SettingsController@updateAi`
  - `POST /admin/settings/payment` → `Admin\SettingsController@updatePayment`

### API (api.php – préfixe `/api/v1`)

- Catalogue :
  - `GET /services` → `Api\CatalogApiController@services`
  - `GET /portfolio` → `Api\CatalogApiController@portfolio`
  - `GET /products` → `Api\CatalogApiController@products`
  - `GET /products/{slug}` → `Api\CatalogApiController@product`
- Devis :
  - `POST /quotes` → `Api\QuoteApiController@store`
- Auth :
  - `POST /auth/login` → `Api\AuthApiController@login`
  - `POST /auth/register` → `Api\AuthApiController@register`
- Chat (fallback HTTP) :
  - `POST /chat/start` → `Api\ChatApiController@start`
  - `POST /chat/message` → `Api\ChatApiController@send`
  - `GET /chat/conversation/{conversation}` → `Api\ChatApiController@fetch`

---

## 2. Schéma de base de données (résumé)

Tables principales :

- `users` : utilisateurs (clients + admins)
  - `role` (user/admin), `locale`, `currency`
- `password_resets` : jetons de réinitialisation
- `services` : catalogue de services SWBS
- `portfolio` : réalisations / projets
- `quotes` : demandes de devis
- `conversations` : conversations de chat
- `messages` : messages de chat
- `categories` : catégories de produits
- `products` : produits de la boutique
- `orders` : commandes clients
- `order_items` : lignes de commande
- `settings` : paramètres généraux (général, devises, paiements, IA)
- `sessions`, `cache`, `jobs`, `failed_jobs` : infrastructure Laravel

Voir `database/schema.sql` pour le détail des colonnes et contraintes.

---

## 3. Modèles Eloquent

- `App\Models\User`
- `App\Models\Service`
- `App\Models\Portfolio`
- `App\Models\Quote`
- `App\Models\Conversation`
- `App\Models\Message`
- `App\Models\Category`
- `App\Models\Product`
- `App\Models\Order`
- `App\Models\OrderItem`
- `App\Models\Setting`

Relations principales :

- `User` → `quotes`, `orders`, `conversations`
- `Service` → `portfolio`, `quotes`
- `Portfolio` → `service` (via `service_type` / slug)
- `Quote` → `user`, `service`
- `Conversation` → `user`, `messages`
- `Message` → `conversation`, `sender`
- `Category` → `products`, `parent`, `children`
- `Product` → `category`, `orderItems`
- `Order` → `user`, `items`
- `OrderItem` → `order`, `product`
- `Setting` → utilitaire static `get()` / `set()`

---

## 4. Internationalisation (i18n)

- `resources/lang/fr.json` : libellés principaux en français
- `resources/lang/en.json` : libellés principaux en anglais
- Middleware `SetLocale` (à venir) pour lire la langue depuis la session et l’URL.
- Switch de langue via route `POST /lang`.

---

## 5. Devises

Gestion via :

- Table `settings` (clés `currency.*`)
- Service/Helper de conversion (à venir) :
  - Devise par défaut : FCFA
  - Devises disponibles : FCFA, NGN, USD, EUR
  - Taux initialisés dans `SettingsSeeder`

---

## 6. Modules à implémenter ensuite

La base Laravel + base de données + seeds + modèles sont maintenant en place.  
Les étapes suivantes (dans les prochains commits/messages) :

1. **Middleware et services**
   - `SetLocale`, `SetCurrency`, middleware `role:admin`
   - Services : `CurrencyService`, `SettingsService`, `ImageService`, `ChatService`, `AiChatService`, `FedepayService`

2. **Contrôleurs**
   - Front (`HomeController`, `ServiceController`, etc.)
   - Auth (login, register, password reset, email verification)
   - Admin (services, portfolio, boutique, commandes, devis, clients, paramètres)
   - API (catalogue, devis, chat, auth)

3. **Vues Blade**
   - Layout public + layout admin
   - Pages Accueil, Services, Portfolio, Boutique, Contact, Devis
   - Dashboard client
   - Écrans d’administration
   - Composants (header, footer, alertes, cartes, widget de chat)

4. **Chat temps réel**
   - Événements broadcast
   - Configuration Reverb / Pusher
   - Widget JS (Laravel Echo + Pusher protocol)
   - IA fallback si aucun admin en ligne

5. **Boutique & paiement**
   - Panier en localStorage (JS)
   - Création des commandes
   - Intégration FedePay via `FedepayService`

6. **Documentation**
   - `README.md` usage local
   - `README_DEPLOY_O2SWITCH.md` (déploiement mutualisé)
   - Complétion de ce MAP si besoin

Ce fichier sert de **vue d’ensemble** pour naviguer rapidement dans la plateforme SWBS.