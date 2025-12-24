@extends('layouts.admin')

@section('title', __('admin.menu.orders'))

@section('content')
    <h1>{{ __('admin.menu.orders') }}</h1>

    <table class="swbs-table">
        <thead>
        <tr>
            <th>#</th>
            <th>Client</th>
            <th>Total FCFA</th>
            <th>Statut</th>
            <th>Date</th>
            <th>{{ __('generic.actions') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($orders as $order)
            <tr>
                <td>#{{ $order->id }}</td>
                <td>{{ $order->customer_name }}</td>
                <td>{{ number_format($order->total_amount_fcfa, 0, ',', ' ') }}</td>
                <td>{{ $order->status }}</td>
                <td>{{ $order->created_at?->format('d/m/Y H:i') }}</td>
                <td>
                    <a href="{{ route('admin.orders.show', $order) }}" class="swbs-link">{{ __('generic.view') }}</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="swbs-pagination">
        {{ $orders->links() }}
    </div>
@endsection