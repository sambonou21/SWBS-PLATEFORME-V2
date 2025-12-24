@extends('layouts.app')

@section('title', $service->title)

@section('content')
<section class="swbs-section">
    <div class="swbs-container swbs-two-columns">
        <div>
            <h1>{{ $service->title }}</h1>
            <p class="swbs-lead">{{ $service->short_description }}</p>
            <div class="swbs-content">
                {!! nl2br(e($service->description)) !!}
            </div>
            <div class="swbs-spaced">
                <a href="{{ route('quotes.create', ['service' => $service->id]) }}" class="swbs-btn swbs-btn-primary">
                    {{ __('quotes.title') }}
                </a>
            </div>
        </div>
        <aside class="swbs-side-box">
            <h2>Informations rapides</h2>
            @if($service->base_price_fcfa)
                @php
                    $currency = session('currency', $currencyService->getDefaultCurrency());
                    $price = $currencyService->fromFcfa($service->base_price_fcfa, $currency);
                @endphp
                <p class="swbs-side-price">
                    {{ __('services.from_price', ['price' => number_format($price, 0, ',', ' ').' '.$currency]) }}
                </p>
            @else
                <p class="swbs-side-price">Sur devis</p>
            @endif
            <ul class="swbs-side-list">
                <li>Site responsive et optimisé</li>
                <li>Accompagnement personnalisé</li>
                <li>Support technique et maintenance</li>
            </ul>
        </aside>
    </div>
</section>
@endsection