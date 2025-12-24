@extends('layouts.app')

@section('title', 'Confirmation de paiement')

@section('content')
<section class="swbs-section">
    <div class="swbs-container">
        <h1>Confirmation de paiement</h1>

        @if($order->status === 'paid')
            <p class="swbs-lead">
                Merci, votre paiement a été confirmé pour la commande #{{ $order->id }}.
            </p>
        @elseif($order->status === 'failed')
            <p class="swbs-lead">
                Le paiement pour la commande #{{ $order->id }} a échoué. Merci de réessayer ou de nous contacter.
            </p>
        @else
            <p class="swbs-lead">
                Le statut du paiement pour la commande #{{ $order->id }} est : {{ $order->status }}.
            </p>
        @endif

        <a href="{{ route('dashboard') }}" class="swbs-btn swbs-btn-primary">Retour à mon espace</a>
    </div>
</section>
@endsection