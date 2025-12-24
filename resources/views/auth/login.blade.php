@extends('layouts.app')

@section('title', __('auth.login.title'))

@section('content')
<section class="swbs-section">
    <div class="swbs-container swbs-auth-container">
        <div class="swbs-auth-box">
            <h1>{{ __('auth.login.title') }}</h1>

            <form method="POST" action="{{ route('login') }}" class="swbs-form">
                @csrf
                <div class="swbs-form-group">
                    <label for="email">{{ __('auth.email') }}</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus>
                    @error('email')<p class="swbs-form-error">{{ $message }}</p>@enderror
                </div>
                <div class="swbs-form-group">
                    <label for="password">{{ __('auth.password') }}</label>
                    <input id="password" name="password" type="password" required>
                    @error('password')<p class="swbs-form-error">{{ $message }}</p>@enderror
                </div>
                <div class="swbs-form-group swbs-form-group-inline">
                    <label>
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        {{ __('auth.remember') }}
                    </label>
                    <a href="{{ route('password.request') }}" class="swbs-link">{{ __('auth.forgot_password') }}</a>
                </div>
                <button type="submit" class="swbs-btn swbs-btn-primary">{{ __('auth.login_submit') }}</button>
            </form>

            <p class="swbs-auth-alt">
                {{ __('auth.register.title') }} ?
                <a href="{{ route('register') }}">{{ __('auth.register_submit') }}</a>
            </p>
        </div>
    </div>
</section>
@endsection