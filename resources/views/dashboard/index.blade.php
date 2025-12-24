@extends('layouts.app')

@section('title', __('dashboard.title'))

@section('content')
<section class="swbs-section">
    <div class="swbs-container">
        <h1>{{ __('dashboard.title') }}</h1>
        <p class="swbs-lead">{{ __('dashboard.welcome', ['name' => $user->name]) }}</p>

        <div class="swbs-dashboard-grid">
            <section class="swbs-dashboard-card">
                <h2>{{ __('dashboard.section.quotes') }}</h2>
                @if($quotes->isEmpty())
                    <p>Aucune demande de devis pour le moment.</p>
                @else
                    <ul class="swbs-list">
                        @foreach($quotes as $quote)
                            <li>
                                <strong>{{ $quote->project_type ?? 'Projet' }}</strong><br>
                                <small>{{ $quote->created_at?->format('d/m/Y H:i') }} – {{ __('quotes.status.'.$quote->status) }}</small>
                            </li>
                        @endforeach
                    </ul>
                    <a href="{{ route('dashboard.quotes') }}" class="swbs-link">{{ __('generic.more') }}</a>
                @endif
            </section>

            <section class="swbs-dashboard-card">
                <h2>{{ __('dashboard.section.orders') }}</h2>
                @if($orders->isEmpty())
                    <p>Aucune commande pour le moment.</p>
                @else
                    <ul class="swbs-list">
                        @foreach($orders as $order)
                            <li>
                                <strong>#{{ $order->id }}</strong> – {{ $order->status }}<br>
                                <small>{{ $order->created_at?->format('d/m/Y H:i') }}</small>
                            </li>
                        @endforeach
                    </ul>
                    <a href="{{ route('dashboard.orders') }}" class="swbs-link">{{ __('generic.more') }}</a>
                @endif
            </section>

            <section class="swbs-dashboard-card">
                <h2>{{ __('dashboard.section.profile') }}</h2>
                <p>{{ $user->email }}</p>
                <p>{{ $user->company }}</p>
                <a href="{{ route('dashboard.profile') }}" class="swbs-link">{{ __('generic.edit') }}</a>
            </section>
        </div>
    </div>
</section>
@endsection