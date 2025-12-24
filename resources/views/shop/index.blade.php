@extends('layouts.app')

@section('title', __('shop.title'))

@section('content')
<section class="swbs-section">
    <div class="swbs-container">
        <header class="swbs-section-header">
            <h1>{{ __('shop.title') }}</h1>
            <p>{{ __('shop.subtitle') }}</p>
        </header>

        @if($products->isEmpty())
            <p>{{ __('shop.empty') }}</p>
        @else
            <div class="swbs-grid swbs-grid-3">
                @foreach($products as $product)
                    @php
                        $currency = session('currency', $currencyService->getDefaultCurrency());
                        $price = $currencyService->fromFcfa($product->price_fcfa, $currency);
                    @endphp
                    <article class="swbs-card swbs-card-product" data-product-id="{{ $product->id }}">
                        <h2>{{ $product->name }}</h2>
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

            <div class="swbs-pagination">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</section>
@endsection