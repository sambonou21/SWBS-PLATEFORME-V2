@extends('layouts.app')

@section('title', __('quotes.title'))

@section('content')
<section class="swbs-section">
    <div class="swbs-container swbs-two-columns">
        <div>
            <h1>{{ __('quotes.title') }}</h1>
            <p class="swbs-lead">{{ __('quotes.subtitle') }}</p>

            <form method="POST" action="{{ route('quotes.store') }}" class="swbs-form">
                @csrf
                <div class="swbs-form-group">
                    <label for="name">{{ __('quotes.form.name') }}</label>
                    <input id="name" name="name" type="text" value="{{ old('name', auth()->user()->name ?? '') }}" required>
                    @error('name')<p class="swbs-form-error">{{ $message }}</p>@enderror
                </div>
                <div class="swbs-form-group">
                    <label for="email">{{ __('quotes.form.email') }}</label>
                    <input id="email" name="email" type="email" value="{{ old('email', auth()->user()->email ?? '') }}" required>
                    @error('email')<p class="swbs-form-error">{{ $message }}</p>@enderror
                </div>
                <div class="swbs-form-group">
                    <label for="phone">{{ __('quotes.form.phone') }}</label>
                    <input id="phone" name="phone" type="text" value="{{ old('phone', auth()->user()->phone ?? '') }}">
                    @error('phone')<p class="swbs-form-error">{{ $message }}</p>@enderror
                </div>
                <div class="swbs-form-group">
                    <label for="company">{{ __('quotes.form.company') }}</label>
                    <input id="company" name="company" type="text" value="{{ old('company', auth()->user()->company ?? '') }}">
                    @error('company')<p class="swbs-form-error">{{ $message }}</p>@enderror
                </div>
                <div class="swbs-form-group">
                    <label for="service_id">{{ __('quotes.form.service') }}</label>
                    <select id="service_id" name="service_id">
                        <option value="">--</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}" @selected(old('service_id') == $service->id)>{{ $service->title }}</option>
                        @endforeach
                    </select>
                    @error('service_id')<p class="swbs-form-error">{{ $message }}</p>@enderror
                </div>
                <div class="swbs-form-group">
                    <label for="project_type">Type de projet</label>
                    <input id="project_type" name="project_type" type="text" value="{{ old('project_type') }}">
                    @error('project_type')<p class="swbs-form-error">{{ $message }}</p>@enderror
                </div>

                @php
                    $currencyService = app(\App\Services\CurrencyService::class);
                    $currency = session('currency', $currencyService->getDefaultCurrency());
                @endphp

                <div class="swbs-form-group swbs-form-group-inline">
                    <div>
                        <label for="budget_min">{{ __('quotes.form.budget_min') }}</label>
                        <input id="budget_min" name="budget_min" type="number" min="0" step="1000" value="{{ old('budget_min') }}">
                        @error('budget_min')<p class="swbs-form-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="budget_max">{{ __('quotes.form.budget_max') }}</label>
                        <input id="budget_max" name="budget_max" type="number" min="0" step="1000" value="{{ old('budget_max') }}">
                        @error('budget_max')<p class="swbs-form-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="currency">{{ __('quotes.form.currency') }}</label>
                        <select id="currency" name="currency">
                            @foreach($currencyService->getSupportedCurrencies() as $cur)
                                <option value="{{ $cur }}" @selected(old('currency', $currency) === $cur)>{{ __('currency.'.$cur) }}</option>
                            @endforeach
                        </select>
                        @error('currency')<p class="swbs-form-error">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="swbs-form-group">
                    <label for="message">{{ __('quotes.form.message') }}</label>
                    <textarea id="message" name="message" rows="6" required>{{ old('message') }}</textarea>
                    @error('message')<p class="swbs-form-error">{{ $message }}</p>@enderror
                </div>

                <button type="submit" class="swbs-btn swbs-btn-primary">{{ __('quotes.form.submit') }}</button>
            </form>
        </div>
        <aside class="swbs-side-box">
            <h2>Tarifs indicatifs</h2>
            <ul class="swbs-side-list">
                <li>Site vitrine : à partir de 150 000 FCFA</li>
                <li>Boutique en ligne : à partir de 250 000 FCFA</li>
                <li>Branding complet : à partir de 80 000 FCFA</li>
                <li>Community management : à partir de 40 000 FCFA / mois</li>
                <li>Application web : devis sur mesure</li>
            </ul>
        </aside>
    </div>
</section>
@endsection