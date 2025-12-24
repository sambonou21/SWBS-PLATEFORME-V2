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
            <h2>Offres &amp; tarifs</h2>
            <p>Des packs adaptés aux besoins des entrepreneurs, TPE et PME.</p>
        </header>

        <div class="swbs-grid swbs-grid-3">
            <article class="swbs-card">
                <h3>Pack Starter</h3>
                <p>Idéal pour lancer votre présence en ligne avec un site vitrine simple.</p>
                <p class="swbs-card-price">À partir de 150 000 FCFA</p>
                <ul class="swbs-side-list">
                    <li>Site vitrine 3 à 5 pages</li>
                    <li>Formulaire de contact</li>
                    <li>Design responsive</li>
                </ul>
            </article>
            <article class="swbs-card">
                <h3>Pack Business</h3>
                <p>Pour structurer votre activité avec plus de pages et des fonctionnalités avancées.</p>
                <p class="swbs-card-price">À partir de 250 000 FCFA</p>
                <ul class="swbs-side-list">
                    <li>Site complet (jusqu&apos;à 10 pages)</li>
                    <li>Blog ou actualités</li>
                    <li>Automatisation de devis</li>
                </ul>
            </article>
            <article class="swbs-card">
                <h3>Pack E-commerce</h3>
                <p>Pour vendre vos produits et services en ligne avec paiement intégré.</p>
                <p class="swbs-card-price">À partir de 350 000 FCFA</p>
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
            <h2>Avis clients</h2>
            <p>Ils ont confié leur présence digitale à SWBS.</p>
        </header>

        <div class="swbs-grid swbs-grid-3">
            <article class="swbs-card">
                <p>« SWBS a totalement modernisé notre image et notre site. Nous recevons plus de demandes de devis qu&apos;avant. »</p>
                <p class="swbs-card-meta">— Agence immobilière à Douala</p>
            </article>
            <article class="swbs-card">
                <p>« Grâce à la boutique en ligne mise en place par SWBS, nous vendons nos produits 24h/24. »</p>
                <p class="swbs-card-meta">— Boutique de vêtements à Cotonou</p>
            </article>
            <article class="swbs-card">
                <p>« Le chat et l&apos;espace client nous permettent de mieux suivre nos projets avec nos clients. »</p>
                <p class="swbs-card-meta">— Consultant indépendant</p>
            </article>
        </div>
    </div>
</section>
@endsection