@extends('layouts.app')

@section('title', __('contact.title'))

@section('content')
<section class="swbs-section">
    <div class="swbs-container swbs-two-columns">
        <div>
            <h1>{{ __('contact.title') }}</h1>
            <p class="swbs-lead">{{ __('contact.subtitle') }}</p>

            <form method="POST" action="{{ route('contact.submit') }}" class="swbs-form">
                @csrf
                <div class="swbs-form-group">
                    <label for="name">Nom complet</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required>
                    @error('name')<p class="swbs-form-error">{{ $message }}</p>@enderror
                </div>
                <div class="swbs-form-group">
                    <label for="email">Adresse email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required>
                    @error('email')<p class="swbs-form-error">{{ $message }}</p>@enderror
                </div>
                <div class="swbs-form-group">
                    <label for="subject">{{ __('contact.form.subject') }}</label>
                    <input id="subject" name="subject" type="text" value="{{ old('subject') }}" required>
                    @error('subject')<p class="swbs-form-error">{{ $message }}</p>@enderror
                </div>
                <div class="swbs-form-group">
                    <label for="message">{{ __('contact.form.message') }}</label>
                    <textarea id="message" name="message" rows="5" required>{{ old('message') }}</textarea>
                    @error('message')<p class="swbs-form-error">{{ $message }}</p>@enderror
                </div>
                <button type="submit" class="swbs-btn swbs-btn-primary">{{ __('contact.form.submit') }}</button>
            </form>
        </div>
        <aside class="swbs-side-box">
            <h2>Coordonnées SWBS</h2>
            <p>
                Email : {{ \App\Models\Setting::get('company.email', 'contact@swbs.site') }}<br>
                Téléphone : {{ \App\Models\Setting::get('company.phone', '+237 600 00 00 00') }}<br>
                Adresse : {{ \App\Models\Setting::get('company.address', 'Douala, Cameroun') }}
            </p>
        </aside>
    </div>
</section>
@endsection