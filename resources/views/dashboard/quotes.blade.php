@extends('layouts.app')

@section('title', __('dashboard.section.quotes'))

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
            <h1>{{ __('dashboard.section.quotes') }}</h1>

            @if($quotes->isEmpty())
                <p>Aucune demande de devis pour le moment.</p>
            @else
                <table class="swbs-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('quotes.form.service') }}</th>
                            <th>{{ __('generic.status') }}</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($quotes as $quote)
                        <tr>
                            <td>#{{ $quote->id }}</td>
                            <td>{{ $quote->service?->title ?? $quote->project_type ?? '-' }}</td>
                            <td>{{ __('quotes.status.'.$quote->status) }}</td>
                            <td>{{ $quote->created_at?->format('d/m/Y H:i') }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <div class="swbs-pagination">
                    {{ $quotes->links() }}
                </div>
            @endif
        </div>
    </div>
</section>
@endsection