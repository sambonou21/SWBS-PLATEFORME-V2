@extends('layouts.app')

@section('title', __('portfolio.title'))

@section('content')
<section class="swbs-section">
    <div class="swbs-container">
        <header class="swbs-section-header">
            <h1>{{ __('portfolio.title') }}</h1>
            <p>{{ __('portfolio.subtitle') }}</p>
        </header>

        <div class="swbs-grid swbs-grid-3">
            @foreach($items as $item)
                <article class="swbs-card swbs-card-portfolio">
                    <h2>{{ $item->title }}</h2>
                    <p>{{ $item->excerpt }}</p>
                    <p class="swbs-card-meta">{{ $item->client_name }}</p>
                    <a href="{{ route('portfolio.show', $item->slug) }}" class="swbs-link">{{ __('generic.more') }}</a>
                </article>
            @endforeach
        </div>

        <div class="swbs-pagination">
            {{ $items->links() }}
        </div>
    </div>
</section>
@endsection