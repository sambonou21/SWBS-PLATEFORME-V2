@extends('layouts.app')

@section('title', 'Vérification de l’email')

@section('content')
<section class="swbs-section">
    <div class="swbs-container swbs-auth-container">
        <div class="swbs-auth-box">
            <h1>Vérification de votre adresse email</h1>
            <p class="swbs-lead">
                Merci pour votre inscription. Avant de continuer, veuillez vérifier votre adresse email en cliquant sur le lien que nous venons de vous envoyer.
            </p>

            @if (session('status') === 'verification-link-sent')
                <x-alert type="success" message="Un nouveau lien de vérification vient d’être envoyé à votre adresse email." />
            @endif

            <form method="POST" action="{{ route('verification.send') }}" class="swbs-form">
                @csrf
                <button type="submit" class="swbs-btn swbs-btn-primary">
                    Renvoyer le lien de vérification
                </button>
            </form>
        </div>
    </div>
</section>
@endsection