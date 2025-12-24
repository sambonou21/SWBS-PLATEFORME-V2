@extends('layouts.app')

@section('title', __('dashboard.section.orders'))

@section('content')
<section class="swbs-section">
    <div class="swbs-container swbs-account-layout">
        <aside class="swbs-account-sidebar">
            <h2>{{ __('dashboard.account') }}</h2>
            <ul class="swbs-account-menu">
                <li><a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'is-active' : '' }}">{{ __('dashboard.overview') }}</a></li>
                <li><a href="{{ route('dashboard.orders') }}" class="{{ request()->routeIs('dashboard.orders') ? 'is-active' : '' }}">{{ __('dashboard.section.orders') }}</a></li>
                <li><a href="{{ route('dashboard.quotes') }}" class="{{ request()->routeIs('dashboard.quotes') ? 'is-active' : '' }}">{{ __('dashboard.section.quotes') }}</a></li>
                <li><a href="{{ route('dashboard.profile') }}" class="{{ request()->routeIs('dashboard.profile') ? 'is-active' : '' }}">{{ __('dashboard.section.profile') }}</a></li>
            </ul>
        </aside>

        <div class="swbs-account-main-section">
            <h1>{{ __('dashboard.section.orders') }}</h1>

            @if($orders->isEmpty())
                <p>Aucune commande pour le moment.</p>
            @else
                <table class="swbs-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('generic.status') }}</th>
                            <th>Total</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>{{ $order->status }}</td>
                            <td>{{ number_format($order->total_amount_fcfa, 0, ',', ' ') }} FCFA</td>
                            <td>{{ $order->created_at?->format('d/m/Y H:i') }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <div class="swbs-pagination">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>
</section>
@endsection