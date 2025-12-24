@extends('layouts.app')

@section('title', __('dashboard.section.orders'))

@section('content')
<section class="swbs-section">
    <div class="swbs-container">
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
</section>
@endsection