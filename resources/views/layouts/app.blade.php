<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>@yield('title', __('app.name')) - SWBS</title>
    <meta name="description" content="Sam Web Business Services (SWBS) est une plateforme digitale tout-en-un permettant aux entreprises de présenter leurs services, gérer leurs clients, automatiser leurs devis, communiquer en temps réel et vendre leurs produits.">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="swbs-body swbs-theme-dark" data-theme="dark">
<header class="swbs-header">
    <div class="swbs-container swbs-header-inner">
        <a href="{{ route('home') }}" class="swbs-logo">
            <span class="swbs-logo-mark">SWBS</span>
            <span class="swbs-logo-text">Sam Web Business Services</span>
        </a>

        <nav class="swbs-nav" id="main-nav">
            <a href="{{ route('home') }}" class="swbs-nav-link {{ request()->routeIs('home') ? 'is-active' : '' }}">{{ __('nav.home') }}</a>
            <a href="{{ route('services.index') }}" class="swbs-nav-link {{ request()->routeIs('services.*') ? 'is-active' : '' }}">{{ __('nav.services') }}</a>
            <a href="{{ route('portfolio.index') }}" class="swbs-nav-link {{ request()->routeIs('portfolio.*') ? 'is-active' : '' }}">{{ __('nav.portfolio') }}</a>
            <a href="{{ route('shop.index') }}" class="swbs-nav-link {{ request()->routeIs('shop.*') ? 'is-active' : '' }}">{{ __('nav.shop') }}</a>
            <a href="{{ route('contact.index') }}" class="swbs-nav-link {{ request()->routeIs('contact.*') ? 'is-active' : '' }}">{{ __('nav.contact') }}</a>
        </nav>

        <div class="swbs-header-actions">
            <button type="button" class="swbs-btn swbs-btn-text" id="swbs-theme-toggle">
                <span data-theme-label>Mode sombre</span>
            </button>

            <form method="POST" action="{{ route('locale.switch') }}" class="swbs-inline-form">
                @csrf
                <select name="lang" class="swbs-select" onchange="this.form.submit()">
                    <option value="fr" @selected(app()->getLocale() === 'fr')>FR</option>
                    <option value="en" @selected(app()->getLocale() === 'en')>EN</option>
                </select>
            </form>

            <form method="POST" action="{{ route('currency.switch') }}" class="swbs-inline-form">
                @csrf
                <select name="currency" class="swbs-select" onchange="this.form.submit()">
                    @php
                        $currencyService = app(\App\Services\CurrencyService::class);
                        $currentCurrency = session('currency', $currencyService->getDefaultCurrency());
                    @endphp
                    @foreach($currencyService->getSupportedCurrencies() as $currency)
                        <option value="{{ $currency }}" @selected($currentCurrency === $currency)>{{ __('currency.'.$currency) }}</option>
                    @endforeach
                </select>
            </form>

            @auth
                <a href="{{ route('dashboard') }}" class="swbs-btn swbs-btn-outline">{{ __('nav.dashboard') }}</a>
                <form method="POST" action="{{ route('logout') }}" class="swbs-inline-form">
                    @csrf
                    <button type="submit" class="swbs-btn swbs-btn-text">{{ __('nav.logout') }}</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="swbs-btn swbs-btn-text">{{ __('nav.login') }}</a>
                <a href="{{ route('register') }}" class="swbs-btn swbs-btn-primary">{{ __('nav.register') }}</a>
            @endauth
        </div>

        <button class="swbs-burger" id="burger-button" aria-label="Menu">
            <span></span><span></span><span></span>
        </button>
    </div>
</header>

<main class="swbs-main">
    @if(session('status'))
        <x-alert type="success" :message="session('status')" />
    @endif

    @yield('content')
</main>

<footer class="swbs-footer">
    <div class="swbs-container swbs-footer-inner">
        <div>
            <strong>SWBS</strong><br>
            <small>Sam Web Business Services</small>
            <p class="swbs-footer-text">
                Sam Web Business Services (SWBS) est une plateforme digitale tout-en-un permettant aux entreprises de présenter leurs services, gérer leurs clients, automatiser leurs devis, communiquer en temps réel et vendre leurs produits.
            </p>
        </div>
        <div>
            <h4>Contacts</h4>
            <p class="swbs-footer-text">
                Email : {{ \App\Models\Setting::get('company.email', 'contact@swbs.site') }}<br>
                Téléphone : {{ \App\Models\Setting::get('company.phone', '+237 600 00 00 00') }}<br>
                Adresse : {{ \App\Models\Setting::get('company.address', 'Douala, Cameroun') }}
            </p>
        </div>
        <div>
            <h4>Navigation</h4>
            <ul class="swbs-footer-links">
                <li><a href="{{ route('home') }}">{{ __('nav.home') }}</a></li>
                <li><a href="{{ route('services.index') }}">{{ __('nav.services') }}</a></li>
                <li><a href="{{ route('portfolio.index') }}">{{ __('nav.portfolio') }}</a></li>
                <li><a href="{{ route('shop.index') }}">{{ __('nav.shop') }}</a></li>
                <li><a href="{{ route('contact.index') }}">{{ __('nav.contact') }}</a></li>
            </ul>
        </div>
    </div>
    <div class="swbs-footer-bottom">
        <div class="swbs-container">
            <small>&copy; {{ date('Y') }} Sam Web Business Services. Tous droits réservés.</small>
        </div>
    </div>
</footer>

<x-chat-widget />

<script src="{{ asset('assets/js/app.js') }}" defer></script>
</body>
</html>