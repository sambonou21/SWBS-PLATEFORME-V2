@extends('layouts.app')

@section('title', __('nav.home'))

@section('content')
<section class="swbs-hero">
    <div class="swbs-container swbs-hero-inner">
        <div class="swbs-hero-text">
            <h1>{{ __('hero.title') }}</h1>
            <p class="swbs-hero-subtitle">
                {{ __('hero.subtitle') }}
            </p>
            <div class="swbs-hero-actions">
                <a href="{{ route('services.index') }}" class="swbs-btn swbs-btn-primary">{{ __('hero.cta.services') }}</a>
                <a href="{{ route('quotes.create') }}" class="swbs-btn swbs-btn-outline">{{ __('quotes.title') }}</a>
            </div>
            <ul class="swbs-hero-benefits">
                <li>Image professionnelle et cohérente</li>
                <li>Automatisation des devis et des relances</li>
                <li>Expérience client moderne (chat, espace client, boutique)</li>
            </ul>
        </div>
        <div class="swbs-hero-visual">
            <div class="swbs-hero-card">
                <h3>Sam Web Business Services</h3>
                <p>Votre plateforme digitale pour présenter, automatiser et vendre.</p>
                <ul>
                    <li>Sites web professionnels</li>
                    <li>Applications métiers</li>
                    <li>Branding &amp; marketing digital</li>
                    <li>E-commerce &amp; paiement en ligne</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="swbs-section">
    <div class="swbs-container">
        <header class="swbs-section-header">
            <h2>{{ __('services.title') }}</h2>
            <p>{{ __('services.subtitle') }}</p>
        </header>

        <div class="swbs-grid swbs-grid-3">
            @foreach($services as $service)
                <article class="swbs-card">
                    <h3>{{ $service->title }}</h3>
                    <p>{{ $service->short_description }}</p>
                    @if($service->base_price_fcfa)
                        @php
                            $currency = session('currency', $currencyService->getDefaultCurrency());
                            $amount = $currencyService->fromFcfa($service->base_price_fcfa, $currency);
                        @endphp
                        <p class="swbs-card-price">
                            {{ __('services.from_price', ['price' => number_format($amount, 0, ',', ' ').' '.$currency]) }}
                        </p>
                    @endif
                    <a href="{{ route('services.show', $service->slug) }}" class="swbs-link">{{ __('generic.more') }}</a>
                </article>
            @endforeach
        </div>

        <div class="swbs-section-cta">
            <a href="{{ route('services.index') }}" class="swbs-btn swbs-btn-outline">{{ __('services.title') }}</a>
        </div>
    </div>
</section>

<section class="swbs-section swbs-section-alt">
    <div class="swbs-container">
        <header class="swbs-section-header">
            <h2>{{ __('home.pricing.title') }}</h2>
            <p>{{ __('home.pricing.subtitle') }}</p>
        </header>

        <div class="swbs-grid swbs-grid-3">
            <article class="swbs-card">
                <h3>{{ __('home.pricing.starter.title') }}</h3>
                <p>{{ __('home.pricing.starter.desc') }}</p>
                <p class="swbs-card-price">{{ __('home.pricing.starter.price') }}</p>
                <ul class="swbs-side-list">
                    <li>Site vitrine 3 à 5 pages</li>
                    <li>Formulaire de contact</li>
                    <li>Design responsive</li>
                </ul>
            </article>
            <article class="swbs-card">
                <h3>{{ __('home.pricing.business.title') }}</h3>
                <p>{{ __('home.pricing.business.desc') }}</p>
                <p class="swbs-card-price">{{ __('home.pricing.business.price') }}</p>
                <ul class="swbs-side-list">
                    <li>Site complet (jusqu&apos;à 10 pages)</li>
                    <li>Blog ou actualités</li>
                    <li>Automatisation de devis</li>
                </ul>
            </article>
            <article class="swbs-card">
                <h3>{{ __('home.pricing.ecommerce.title') }}</h3>
                <p>{{ __('home.pricing.ecommerce.desc') }}</p>
                <p class="swbs-card-price">{{ __('home.pricing.ecommerce.price') }}</p>
                <ul class="swbs-side-list">
                    <li>Boutique en ligne</li>
                    <li>Gestion des commandes</li>
                    <li>Intégration FedePay</li>
                </ul>
            </article>
        </div>
    </div>
</section>

<section class="swbs-section swbs-section-alt">
    <div class="swbs-container">
        <header class="swbs-section-header">
            <h2>{{ __('portfolio.title') }}</h2>
            <p>{{ __('portfolio.subtitle') }}</p>
        </header>

        <div class="swbs-grid swbs-grid-3">
            @foreach($portfolio as $item)
                <article class="swbs-card swbs-card-portfolio">
                    <h3>{{ $item->title }}</h3>
                    <p>{{ $item->excerpt }}</p>
                    <p class="swbs-card-meta">{{ $item->client_name }}</p>
                    <a href="{{ route('portfolio.show', $item->slug) }}" class="swbs-link">{{ __('generic.more') }}</a>
                </article>
            @endforeach
        </div>

        <div class="swbs-section-cta">
            <a href="{{ route('portfolio.index') }}" class="swbs-btn swbs-btn-outline">{{ __('portfolio.more') }}</a>
        </div>
    </div>
</section>

<section class="swbs-section">
    <div class="swbs-container">
        <header class="swbs-section-header">
            <h2>{{ __('shop.title') }}</h2>
            <p>{{ __('shop.subtitle') }}</p>
        </header>

        <div class="swbs-grid swbs-grid-3">
            @foreach($products as $product)
                @php
                    $currency = session('currency', $currencyService->getDefaultCurrency());
                    $price = $currencyService->fromFcfa($product->price_fcfa, $currency);
                @endphp
                <article class="swbs-card swbs-card-product" data-product-id="{{ $product->id }}">
                    <h3>{{ $product->name }}</h3>
                    <p>{{ $product->short_description }}</p>
                    <p class="swbs-card-price">{{ number_format($price, 0, ',', ' ') }} {{ $currency }}</p>
                    <div class="swbs-card-actions">
                        <button class="swbs-btn swbs-btn-outline js-add-to-cart" data-id="{{ $product->id }}" data-name="{{ $product->name }}" data-price="{{ $price }}" data-currency="{{ $currency }}">
                            {{ __('shop.add_to_cart') }}
                        </button>
                        <a href="{{ route('shop.show', $product->slug) }}" class="swbs-btn swbs-btn-text">
                            {{ __('shop.view_product') }}
                        </a>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="swbs-section-cta">
            <a href="{{ route('shop.index') }}" class="swbs-btn swbs-btn-outline">{{ __('shop.title') }}</a>
        </div>
    </div>
</section>

<section class="swbs-section swbs-section-alt">
    <div class="swbs-container">
        <header class="swbs-section-header">
            <h2>{{ __('home.testimonials.title') }}</h2>
            <p>{{ __('home.testimonials.subtitle') }}</p>
        </header>

        <div class="swbs-grid swbs-grid-3">
            <article class="swbs-card">
                <p>{{ __('home.testimonials.1.text') }}</p>
                <p class="swbs-card-meta">{{ __('home.testimonials.1.meta') }}</p>
            </article>
            <article class="swbs-card">
                <p>{{ __('home.testimonials.2.text') }}</p>
                <p class="swbs-card-meta">{{ __('home.testimonials.2.meta') }}</p>
            </article>
            <article class="swbs-card">
                <p>{{ __('home.testimonials.3.text') }}</p>
                <p class="swbs-card-meta">{{ __('home.testimonials.3.meta') }}</p>
            </article>
        </div>
    </div>
</section>
@endsection