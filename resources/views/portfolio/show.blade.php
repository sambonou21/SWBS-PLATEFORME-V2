@extends('layouts.app')

@section('title', $item->title)

@section('content')
<section class="swbs-section">
    <div class="swbs-container swbs-two-columns">
        <div>
            <h1>{{ $item->title }}</h1>
            <p class="swbs-lead">{{ $item->excerpt }}</p>
            <div class="swbs-content">
                {!! nl2br(e($item->description)) !!}
            </div>
            @if($item->url)
                <p class="swbs-spaced">
                    <a href="{{ $item->url }}" target="_blank" rel="noopener" class="swbs-link">
                        Voir le projet en ligne
                    </a>
                </p>
            @endif
        </div>
        <aside class="swbs-side-box">
            <h2>Informations projet</h2>
            <p><strong>Client :</strong> {{ $item->client_name ?? 'Non communiqué' }}</p>
            <p><strong>Type de service :</strong> {{ $item->service_type }}</p>
        </aside>
    </div>
</section>
@endsection