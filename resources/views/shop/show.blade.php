@extends('layouts.app')

@section('title', $product->name)

@section('content')
<section class="swbs-section">
    <div class="swbs-container swbs-two-columns">
        <div>
            <h1>{{ $product->name }}</h1>
            <p class="swbs-lead">{{ $product->short_description }}</p>
            <div class="swbs-content">
                {!! nl2br(e($product->description)) !!}
            </div>
        </div>
        <aside class="swbs-side-box">
            @php
                $currency = session('currency', $currencyService->getDefaultCurrency());
                $price = $currencyService->fromFcfa($product->price_fcfa, $currency);
            @endphp
            <p class="swbs-side-price">{{ number_format($price, 0, ',', ' ') }} {{ $currency }}</p>
            <form method="POST" action="{{ route('shop.order', $product->slug) }}" class="swbs-form">
                @csrf
                <input type="hidden" name="quantity" value="1">
                <button type="submit" class="swbs-btn swbs-btn-primary" style="width: 100%; margin-bottom: 0.5rem;">
                    Commander maintenant
                </button>
            </form>
            <button class="swbs-btn swbs-btn-outline js-add-to-cart"
                    data-id="{{ $product->id }}"
                    data-name="{{ $product->name }}"
                    data-price="{{ $price }}"
                    data-currency="{{ $currency }}">
                {{ __('shop.add_to_cart') }}
            </button>
        </aside>
    </div>
</section>
@endsection