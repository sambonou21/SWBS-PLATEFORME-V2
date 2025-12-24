@extends('layouts.admin')

@section('title', 'Commande #'.$order->id)

@section('content')
    <h1>Commande #{{ $order->id }}</h1>

    <div class="swbs-two-columns">
        <section class="swbs-side-box">
            <h2>Détails commande</h2>
            <p><strong>Client :</strong> {{ $order->customer_name }} ({{ $order->customer_email }})</p>
            <p><strong>Téléphone :</strong> {{ $order->customer_phone }}</p>
            <p><strong>Adresse :</strong><br>{{ $order->customer_address }}</p>
            <p><strong>Total :</strong> {{ number_format($order->total_amount_fcfa, 0, ',', ' ') }} FCFA</p>
            <p><strong>Statut :</strong> {{ $order->status }}</p>

            <form method="POST" action="{{ route('admin.orders.update', $order) }}" class="swbs-form" style="margin-top: 1rem;">
                @csrf
                @method('PUT')
                <div class="swbs-form-group">
                    <label for="status">Statut</label>
                    <select id="status" name="status">
                        @foreach(['pending','paid','failed','cancelled'] as $status)
                            <option value="{{ $status }}" @selected($order->status === $status)>{{ $status }}</option>
                        @endforeach
                    </select>
                    @error('status')<p class="swbs-form-error">{{ $message }}</p>@enderror
                </div>
                <button type="submit" class="swbs-btn swbs-btn-primary">{{ __('generic.save') }}</button>
            </form>
        </section>

        <section>
            <h2>Produits</h2>
            <table class="swbs-table">
                <thead>
                <tr>
                    <th>Produit</th>
                    <th>Qté</th>
                    <th>PU (FCFA)</th>
                    <th>Total (FCFA)</th>
                </tr>
                </thead>
                <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->product?->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->unit_price_fcfa, 0, ',', ' ') }}</td>
                        <td>{{ number_format($item->total_price_fcfa, 0, ',', ' ') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </section>
    </div>
@endsection