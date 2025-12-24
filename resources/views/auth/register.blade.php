@extends('layouts.app')

@section('title', __('auth.register.title'))

@section('content')
<section class="swbs-section">
    <div class="swbs-container swbs-auth-container">
        <div class="swbs-auth-box">
            <h1>{{ __('auth.register.title') }}</h1>

            <form method="POST" action="{{ route('register') }}" class="swbs-form">
                @csrf
                <div class="swbs-form-group">
                    <label for="name">{{ __('quotes.form.name') }}</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus>
                    @error('name')<p class="swbs-form-error">{{ $message }}</p>@enderror
                </div>
                <div class="swbs-form-group">
                    <label for="company">{{ __('quotes.form.company') }}</label>
                    <input id="company" name="company" type="text" value="{{ old('company') }}">
                    @error('company')<p class="swbs-form-error">{{ $message }}</p>@enderror
                </div>
                <div class="swbs-form-group">
                    <label for="phone">{{ __('quotes.form.phone') }}</label>
                    <input id="phone" name="phone" type="text" value="{{ old('phone') }}">
                    @error('phone')<p class="swbs-form-error">{{ $message }}</p>@enderror
                </div>
                <div class="swbs-form-group">
                    <label for="email">{{ __('auth.email') }}</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required>
                    @error('email')<p class="swbs-form-error">{{ $message }}</p>@enderror
                </div>
                <div class="swbs-form-group">
                    <label for="password">{{ __('auth.password') }}</label>
                    <input id="password" name="password" type="password" required>
                    @error('password')<p class="swbs-form-error">{{ $message }}</p>@enderror
                </div>
                <div class="swbs-form-group">
                    <label for="password_confirmation">{{ __('auth.password_confirmation') }}</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required>
                </div>

                <button type="submit" class="swbs-btn swbs-btn-primary">{{ __('auth.register_submit') }}</button>
            </form>

            <p class="swbs-auth-alt">
                {{ __('auth.login.title') }} ?
                <a href="{{ route('login') }}">{{ __('auth.login_submit') }}</a>
            </p>
        </div>
    </div>
</section>
@endsection