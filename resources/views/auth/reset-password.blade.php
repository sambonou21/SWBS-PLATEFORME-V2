@extends('layouts.app')

@section('title', 'Réinitialiser le mot de passe')

@section('content')
<section class="swbs-section">
    <div class="swbs-container swbs-auth-container">
        <div class="swbs-auth-box">
            <h1>Réinitialiser le mot de passe</h1>

            <form method="POST" action="{{ route('password.update') }}" class="swbs-form">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">

                <div class="swbs-form-group">
                    <label for="password">{{ __('auth.password') }}</label>
                    <input id="password" name="password" type="password" required autofocus>
                    @error('password')<p class="swbs-form-error">{{ $message }}</p>@enderror
                </div>
                <div class="swbs-form-group">
                    <label for="password_confirmation">{{ __('auth.password_confirmation') }}</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required>
                </div>

                <button type="submit" class="swbs-btn swbs-btn-primary">{{ __('generic.save') }}</button>
            </form>
        </div>
    </div>
</section>
@endsection