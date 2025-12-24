@extends('layouts.app')

@section('title', __('dashboard.section.profile'))

@section('content')
<section class="swbs-section">
    <div class="swbs-container swbs-two-columns">
        <div>
            <h1>{{ __('dashboard.section.profile') }}</h1>

            <form method="POST" action="{{ route('dashboard.profile.update') }}" class="swbs-form">
                @csrf
                <div class="swbs-form-group">
                    <label for="name">Nom complet</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required>
                    @error('name')<p class="swbs-form-error">{{ $message }}</p>@enderror
                </div>
                <div class="swbs-form-group">
                    <label for="company">Entreprise</label>
                    <input id="company" name="company" type="text" value="{{ old('company', $user->company) }}">
                    @error('company')<p class="swbs-form-error">{{ $message }}</p>@enderror
                </div>
                <div class="swbs-form-group">
                    <label for="phone">Téléphone</label>
                    <input id="phone" name="phone" type="text" value="{{ old('phone', $user->phone) }}">
                    @error('phone')<p class="swbs-form-error">{{ $message }}</p>@enderror
                </div>
                <div class="swbs-form-group swbs-form-group-inline">
                    <div>
                        <label for="locale">{{ __('nav.language') }}</label>
                        <select id="locale" name="locale">
                            <option value="fr" @selected(old('locale', $user->locale) === 'fr')>FR</option>
                            <option value="en" @selected(old('locale', $user->locale) === 'en')>EN</option>
                        </select>
                        @error('locale')<p class="swbs-form-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="currency">{{ __('nav.currency') }}</label>
                        @php
                            $currencyService = app(\App\Services\CurrencyService::class);
                        @endphp
                        <select id="currency" name="currency">
                            @foreach($currencyService->getSupportedCurrencies() as $cur)
                                <option value="{{ $cur }}" @selected(old('currency', $user->currency) === $cur)>{{ __('currency.'.$cur) }}</option>
                            @endforeach
                        </select>
                        @error('currency')<p class="swbs-form-error">{{ $message }}</p>@enderror
                    </div>
                </div>

                <button type="submit" class="swbs-btn swbs-btn-primary">{{ __('generic.save') }}</button>
            </form>
        </div>
        <aside class="swbs-side-box">
            <h2>Compte</h2>
            <p>{{ $user->email }}</p>
            <p>Rôle : {{ $user->role }}</p>
        </aside>
    </div>
</section>
@endsection