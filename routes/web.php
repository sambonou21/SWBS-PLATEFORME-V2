<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Front\ContactController;
use App\Http\Controllers\Front\DashboardController;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\PortfolioController;
use App\Http\Controllers\Front\QuoteController;
use App\Http\Controllers\Front\ServiceController;
use App\Http\Controllers\Front\ShopController;
use App\Http\Controllers\Front\PaymentController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\Chat\ChatController;
use App\Http\Controllers\Admin\AdminDashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes Web SWBS
|--------------------------------------------------------------------------
|
| Routes publiques (front-office) et privées (dashboard client + admin).
| Tout le contenu public est disponible en français par défaut, avec
| possibilité de basculer en anglais.
|
*/

// Locale & devise
Route::post('/lang', [LocaleController::class, 'switch'])->name('locale.switch');
Route::post('/currency', [CurrencyController::class, 'switch'])->name('currency.switch');

// Accueil & pages publiques
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{slug}', [ServiceController::class, 'show'])->name('services.show');

Route::get('/portfolio', [PortfolioController::class, 'index'])->name('portfolio.index');
Route::get('/portfolio/{slug}', [PortfolioController::class, 'show'])->name('portfolio.show');

Route::get('/boutique', [ShopController::class, 'index'])->name('shop.index');
Route::get('/boutique/{slug}', [ShopController::class, 'show'])->name('shop.show');
Route::post('/boutique/{slug}/commander', [ShopController::class, 'order'])->name('shop.order');

Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

// Devis
Route::get('/devis', [QuoteController::class, 'create'])->name('quotes.create');
Route::post('/devis', [QuoteController::class, 'store'])->name('quotes.store');

// Paiement (FedePay callbacks)
Route::post('/paiement/fedepay/callback/{order}', [PaymentController::class, 'callback'])->name('shop.payment.callback');
Route::get('/paiement/fedepay/retour/{order}', [PaymentController::class, 'return'])->name('shop.payment.return');

// Authentification
Route::middleware('guest')->group(function () {
    Route::get('/connexion', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/connexion', [LoginController::class, 'login']);
    Route::get('/inscription', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/inscription', [RegisterController::class, 'register']);

    Route::get('/mot-de-passe/oubli', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/mot-de-passe/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

    Route::get('/mot-de-passe/reinitialisation/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/mot-de-passe/reinitialisation', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// Vérification email
Route::middleware('auth')->group(function () {
    Route::get('/email/verification', [VerificationController::class, 'notice'])->name('verification.notice');
    Route::get('/email/verification/{id}/{hash}', [VerificationController::class, 'verify'])->middleware('signed')->name('verification.verify');
    Route::post('/email/verification/notification', [VerificationController::class, 'send'])->middleware('throttle:6,1')->name('verification.send');
});

// Déconnexion
Route::post('/deconnexion', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

// Dashboard client
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/commandes', [DashboardController::class, 'orders'])->name('dashboard.orders');
    Route::get('/dashboard/devis', [DashboardController::class, 'quotes'])->name('dashboard.quotes');
    Route::get('/dashboard/profil', [DashboardController::class, 'profile'])->name('dashboard.profile');
    Route::post('/dashboard/profil', [DashboardController::class, 'updateProfile'])->name('dashboard.profile.update');
});

// Chat temps réel (widget)
Route::middleware('web')->group(function () {
    Route::post('/chat/start', [ChatController::class, 'start'])->name('chat.start');
    Route::post('/chat/message', [ChatController::class, 'send'])->name('chat.send');
    Route::get('/chat/conversation/{conversation}', [ChatController::class, 'fetch'])->name('chat.fetch');
});

// Admin
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::resource('services', \App\Http\Controllers\Admin\ServiceController::class);
        Route::resource('portfolio', \App\Http\Controllers\Admin\PortfolioController::class);
        Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
        Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
        Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class)->only(['index', 'show', 'update']);
        Route::resource('quotes', \App\Http\Controllers\Admin\QuoteController::class)->only(['index', 'show', 'update', 'destroy']);
        Route::resource('clients', \App\Http\Controllers\Admin\ClientController::class)->only(['index', 'show']);
        Route::resource('admins', \App\Http\Controllers\Admin\AdminUserController::class);

        Route::get('chat', [\App\Http\Controllers\Admin\ChatController::class, 'index'])->name('chat.index');
        Route::get('chat/{conversation}', [\App\Http\Controllers\Admin\ChatController::class, 'show'])->name('chat.show');
        Route::post('chat/{conversation}/reply', [\App\Http\Controllers\Admin\ChatController::class, 'reply'])->name('chat.reply');

        Route::get('settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
        Route::post('settings', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');
        Route::post('settings/currencies', [\App\Http\Controllers\Admin\SettingsController::class, 'updateCurrencies'])->name('settings.currencies');
        Route::post('settings/ai', [\App\Http\Controllers\Admin\SettingsController::class, 'updateAi'])->name('settings.ai');
        Route::post('settings/payment', [\App\Http\Controllers\Admin\SettingsController::class, 'updatePayment'])->name('settings.payment');
    });