@extends('layouts.app')

@section('title', __('auth.forgot_password'))

@section('content')
<section class="swbs-section">
    <div class="swbs-container swbs-auth-container">
        <div class="swbs-auth-box">
            <h1>{{ __('auth.forgot_password') }}</h1>

            <form method="POST" action="{{ route('password.email') }}" class="swbs-form">
                @csrf
                <div class="swbs-form-group">
                    <label for="email">{{ __('auth.email') }}</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus>
                    @error('email')<p class="swbs-form-error">{{ $message }}</p>@enderror
                </div>
                <button type="submit" class="swbs-btn swbs-btn-primary">{{ __('auth.forgot_password') }}</button>
            </form>
        </div>
    </div>
</section>
@endsection