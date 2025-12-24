@extends('layouts.admin')

@section('title', __('admin.menu.dashboard'))

@section('content')
    <h1>{{ __('admin.menu.dashboard') }}</h1>

    <div class="swbs-dashboard-grid">
        <section class="swbs-dashboard-card">
            <h2>Services</h2>
            <p>{{ $stats['services_count'] }} services actifs ou configurés.</p>
            <a href="{{ route('admin.services.index') }}" class="swbs-link">{{ __('generic.view') }}</a>
        </section>

        <section class="swbs-dashboard-card">
            <h2>Devis</h2>
            <p>{{ $stats['quotes_count'] }} demandes de devis enregistrées.</p>
            <a href="{{ route('admin.quotes.index') }}" class="swbs-link">{{ __('generic.view') }}</a>
        </section>

        <section class="swbs-dashboard-card">
            <h2>Commandes</h2>
            <p>{{ $stats['orders_count'] }} commandes dans la boutique.</p>
            <a href="{{ route('admin.orders.index') }}" class="swbs-link">{{ __('generic.view') }}</a>
        </section>

        <section class="swbs-dashboard-card">
            <h2>Clients</h2>
            <p>{{ $stats['clients_count'] }} comptes clients enregistrés.</p>
            <a href="{{ route('admin.clients.index') }}" class="swbs-link">{{ __('generic.view') }}</a>
        </section>
    </div>

    <div class="swbs-section" style="padding-top: 2rem;">
        <h2>Derniers devis</h2>
        <table class="swbs-table">
            <thead>
            <tr>
                <th>#</th>
                <th>Client</th>
                <th>Service</th>
                <th>{{ __('generic.status') }}</th>
                <th>Date</th>
            </tr>
            </thead>
            <tbody>
            @foreach($latestQuotes as $quote)
                <tr>
                    <td>#{{ $quote->id }}</td>
                    <td>{{ $quote->name }}</td>
                    <td>{{ $quote->service?->title ?? $quote->project_type }}</td>
                    <td>{{ __('quotes.status.'.$quote->status) }}</td>
                    <td>{{ $quote->created_at?->format('d/m/Y H:i') }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="swbs-section" style="padding-top: 1rem;">
        <h2>Dernières commandes</h2>
        <table class="swbs-table">
            <thead>
            <tr>
                <th>#</th>
                <th>Client</th>
                <th>Total FCFA</th>
                <th>{{ __('generic.status') }}</th>
                <th>Date</th>
            </tr>
            </thead>
            <tbody>
            @foreach($latestOrders as $order)
                <tr>
                    <td>#{{ $order->id }}</td>
                    <td>{{ $order->customer_name }}</td>
                    <td>{{ number_format($order->total_amount_fcfa, 0, ',', ' ') }}</td>
                    <td>{{ $order->status }}</td>
                    <td>{{ $order->created_at?->format('d/m/Y H:i') }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection