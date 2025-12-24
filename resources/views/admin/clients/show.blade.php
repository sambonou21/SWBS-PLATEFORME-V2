@extends('layouts.admin')

@section('title', 'Client #'.$client->id)

@section('content')
    <h1>{{ $client->name }}</h1>

    <div class="swbs-two-columns">
        <section class="swbs-side-box">
            <h2>Profil</h2>
            <p><strong>Email :</strong> {{ $client->email }}</p>
            <p><strong>Entreprise :</strong> {{ $client->company }}</p>
            <p><strong>Téléphone :</strong> {{ $client->phone }}</p>
            <p><strong>Langue :</strong> {{ $client->locale }}</p>
            <p><strong>Devise :</strong> {{ $client->currency }}</p>
        </section>

        <section>
            <h2>Devis</h2>
            <table class="swbs-table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Service</th>
                    <th>Statut</th>
                    <th>Date</th>
                </tr>
                </thead>
                <tbody>
                @foreach($client->quotes as $quote)
                    <tr>
                        <td>#{{ $quote->id }}</td>
                        <td>{{ $quote->service?->title ?? $quote->project_type }}</td>
                        <td>{{ __('quotes.status.'.$quote->status) }}</td>
                        <td>{{ $quote->created_at?->format('d/m/Y H:i') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <h2>Commandes</h2>
            <table class="swbs-table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Total FCFA</th>
                    <th>Statut</th>
                    <th>Date</th>
                </tr>
                </thead>
                <tbody>
                @foreach($client->orders as $order)
                    <tr>
                        <td>#{{ $order->id }}</td>
                        <td>{{ number_format($order->total_amount_fcfa, 0, ',', ' ') }}</td>
                        <td>{{ $order->status }}</td>
                        <td>{{ $order->created_at?->format('d/m/Y H:i') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </section>
    </div>
@endsection