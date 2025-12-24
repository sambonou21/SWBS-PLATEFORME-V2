<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Admin SWBS')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="swbs-body swbs-body-admin">
<header class="swbs-header swbs-header-admin">
    <div class="swbs-container swbs-header-inner">
        <a href="{{ route('admin.dashboard') }}" class="swbs-logo">
            <span class="swbs-logo-mark">SWBS</span>
            <span class="swbs-logo-text">Admin</span>
        </a>

        <nav class="swbs-nav swbs-nav-admin">
            <a href="{{ route('admin.dashboard') }}" class="swbs-nav-link {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}">{{ __('admin.menu.dashboard') }}</a>
            <a href="{{ route('admin.services.index') }}" class="swbs-nav-link {{ request()->routeIs('admin.services.*') ? 'is-active' : '' }}">{{ __('admin.menu.services') }}</a>
            <a href="{{ route('admin.portfolio.index') }}" class="swbs-nav-link {{ request()->routeIs('admin.portfolio.*') ? 'is-active' : '' }}">{{ __('admin.menu.portfolio') }}</a>
            <a href="{{ route('admin.products.index') }}" class="swbs-nav-link {{ request()->routeIs('admin.products.*') ? 'is-active' : '' }}">{{ __('admin.menu.products') }}</a>
            <a href="{{ route('admin.orders.index') }}" class="swbs-nav-link {{ request()->routeIs('admin.orders.*') ? 'is-active' : '' }}">{{ __('admin.menu.orders') }}</a>
            <a href="{{ route('admin.quotes.index') }}" class="swbs-nav-link {{ request()->routeIs('admin.quotes.*') ? 'is-active' : '' }}">{{ __('admin.menu.quotes') }}</a>
            <a href="{{ route('admin.chat.index') }}" class="swbs-nav-link {{ request()->routeIs('admin.chat.*') ? 'is-active' : '' }}">{{ __('admin.menu.chat') }}</a>
            <a href="{{ route('admin.settings.index') }}" class="swbs-nav-link {{ request()->routeIs('admin.settings.*') ? 'is-active' : '' }}">{{ __('admin.menu.settings') }}</a>
        </nav>

        <div class="swbs-header-actions">
            <span class="swbs-admin-user">{{ auth()->user()->name ?? '' }}</span>
            <form method="POST" action="{{ route('logout') }}" class="swbs-inline-form">
                @csrf
                <button type="submit" class="swbs-btn swbs-btn-text">{{ __('nav.logout') }}</button>
            </form>
        </div>
    </div>
</header>

<main class="swbs-main swbs-main-admin">
    <div class="swbs-container">
        @if(session('status'))
            <x-alert type="success" :message="session('status')" />
        @endif

        @yield('content')
    </div>
</main>

<script src="{{ asset('assets/js/app.js') }}" defer></script>
</body>
</html>