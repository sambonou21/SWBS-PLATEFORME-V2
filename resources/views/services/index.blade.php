@extends('layouts.app')

@section('title', __('services.title'))

@section('content')
<section class="swbs-section">
    <div class="swbs-container">
        <header class="swbs-section-header">
            <h1>{{ __('services.title') }}</h1>
            <p>{{ __('services.subtitle') }}</p>
        </header>

        <div class="swbs-grid swbs-grid-3">
            @foreach($services as $service)
                @php
                    $currency = session('currency', $currencyService->getDefaultCurrency());
                    $price = $service->base_price_fcfa ? $currencyService->fromFcfa($service->base_price_fcfa, $currency) : null;
                @endphp
                <article class="swbs-card">
                    <h2>{{ $service->title }}</h2>
                    <p>{{ $service->short_description }}</p>
                    @if($price)
                        <p class="swbs-card-price">
                            {{ __('services.from_price', ['price' => number_format($price, 0, ',', ' ').' '.$currency]) }}
                        </p>
                    @endif
                    <a href="{{ route('services.show', $service->slug) }}" class="swbs-link">{{ __('generic.more') }}</a>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endsection