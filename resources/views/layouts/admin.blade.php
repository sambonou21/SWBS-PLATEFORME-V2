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
<div class="swbs-admin-layout">
    <aside class="swbs-admin-sidebar">
        <div class="swbs-admin-sidebar-header">
            <a href="{{ route('admin.dashboard') }}" class="swbs-logo">
                <span class="swbs-logo-mark">SWBS</span>
                <span class="swbs-logo-text">Admin</span>
            </a>
        </div>
        <nav class="swbs-admin-nav">
            <a href="{{ route('admin.dashboard') }}" class="swbs-admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}">{{ __('admin.menu.dashboard') }}</a>
            <a href="{{ route('admin.services.index') }}" class="swbs-admin-nav-link {{ request()->routeIs('admin.services.*') ? 'is-active' : '' }}">{{ __('admin.menu.services') }}</a>
            <a href="{{ route('admin.portfolio.index') }}" class="swbs-admin-nav-link {{ request()->routeIs('admin.portfolio.*') ? 'is-active' : '' }}">{{ __('admin.menu.portfolio') }}</a>
            <a href="{{ route('admin.products.index') }}" class="swbs-admin-nav-link {{ request()->routeIs('admin.products.*') ? 'is-active' : '' }}">{{ __('admin.menu.products') }}</a>
            <a href="{{ route('admin.orders.index') }}" class="swbs-admin-nav-link {{ request()->routeIs('admin.orders.*') ? 'is-active' : '' }}">{{ __('admin.menu.orders') }}</a>
            <a href="{{ route('admin.quotes.index') }}" class="swbs-admin-nav-link {{ request()->routeIs('admin.quotes.*') ? 'is-active' : '' }}">{{ __('admin.menu.quotes') }}</a>
            <a href="{{ route('admin.clients.index') }}" class="swbs-admin-nav-link {{ request()->routeIs('admin.clients.*') ? 'is-active' : '' }}">{{ __('admin.menu.clients') }}</a>
            <a href="{{ route('admin.chat.index') }}" class="swbs-admin-nav-link {{ request()->routeIs('admin.chat.*') ? 'is-active' : '' }}">{{ __('admin.menu.chat') }}</a>
            <a href="{{ route('admin.settings.index') }}" class="swbs-admin-nav-link {{ request()->routeIs('admin.settings.*') ? 'is-active' : '' }}">{{ __('admin.menu.settings') }}</a>
        </nav>
        <div class="swbs-admin-sidebar-footer">
            <span class="swbs-admin-user">{{ auth()->user()->name ?? '' }}</span>
            <form method="POST" action="{{ route('logout') }}" class="swbs-inline-form">
                @csrf
                <button type="submit" class="swbs-btn swbs-btn-text">{{ __('nav.logout') }}</button>
            </form>
        </div>
    </aside>

    <div class="swbs-admin-main">
        <header class="swbs-admin-topbar">
            <div class="swbs-admin-topbar-inner">
                <h1 class="swbs-admin-topbar-title">@yield('title', 'Admin SWBS')</h1>
                <span class="swbs-admin-topbar-subtitle">Console SWBS</span>
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
    </div>
</div>

<script src="{{ asset('assets/js/app.js') }}" defer></script>
</body>
</html>