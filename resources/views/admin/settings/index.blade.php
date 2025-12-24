@extends('layouts.admin')

@section('title', __('admin.menu.settings'))

@section('content')
    <h1>{{ __('admin.menu.settings') }}</h1>

    <div class="swbs-two-columns">
        <section>
            <h2>Général</h2>
            <form method="POST" action="{{ route('admin.settings.update') }}" class="swbs-form">
                @csrf
                <div class="swbs-form-group">
                    <label for="company_name">Nom de la structure</label>
                    <input id="company_name" name="company_name" type="text" value="{{ old('company_name', $general['company.name'] ?? '') }}" required>
                </div>
                <div class="swbs-form-group">
                    <label for="company_tagline">Baseline</label>
                    <input id="company_tagline" name="company_tagline" type="text" value="{{ old('company_tagline', $general['company.tagline'] ?? '') }}">
                </div>
                <div class="swbs-form-group">
                    <label for="company_email">Email</label>
                    <input id="company_email" name="company_email" type="email" value="{{ old('company_email', $general['company.email'] ?? '') }}" required>
                </div>
                <div class="swbs-form-group">
                    <label for="company_phone">Téléphone</label>
                    <input id="company_phone" name="company_phone" type="text" value="{{ old('company_phone', $general['company.phone'] ?? '') }}">
                </div>
                <div class="swbs-form-group">
                    <label for="company_address">Adresse</label>
                    <textarea id="company_address" name="company_address" rows="2">{{ old('company_address', $general['company.address'] ?? '') }}</textarea>
                </div>
                <button type="submit" class="swbs-btn swbs-btn-primary">{{ __('generic.save') }}</button>
            </form>
        </section>

        <section>
            <h2>Devises</h2>
            <form method="POST" action="{{ route('admin.settings.currencies') }}" class="swbs-form">
                @csrf
                <div class="swbs-form-group">
                    <label for="default_currency">Devise par défaut</label>
                    <select id="default_currency" name="default_currency">
                        @foreach(['FCFA','NGN','USD','EUR'] as $cur)
                            <option value="{{ $cur }}" @selected(($currency['currency.default'] ?? 'FCFA') === $cur)>{{ $cur }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="swbs-form-group swbs-form-group-inline">
                    <div>
                        <label for="rate_FCFA">1 FCFA en FCFA</label>
                        <input id="rate_FCFA" name="rate_FCFA" type="number" step="0.000001" value="{{ $currency['currency.rate.FCFA'] ?? 1 }}" required>
                    </div>
                    <div>
                        <label for="rate_NGN">1 NGN en FCFA</label>
                        <input id="rate_NGN" name="rate_NGN" type="number" step="0.000001" value="{{ $currency['currency.rate.NGN'] ?? 0.95 }}" required>
                    </div>
                    <div>
                        <label for="rate_USD">1 USD en FCFA</label>
                        <input id="rate_USD" name="rate_USD" type="number" step="0.000001" value="{{ $currency['currency.rate.USD'] ?? 600 }}" required>
                    </div>
                    <div>
                        <label for="rate_EUR">1 EUR en FCFA</label>
                        <input id="rate_EUR" name="rate_EUR" type="number" step="0.000001" value="{{ $currency['currency.rate.EUR'] ?? 650 }}" required>
                    </div>
                </div>
                <button type="submit" class="swbs-btn swbs-btn-primary">{{ __('generic.save') }}</button>
            </form>
        </section>
    </div>

    <div class="swbs-two-columns" style="margin-top: 2rem;">
        <section>
            <h2>IA (assistant chat)</h2>
            <form method="POST" action="{{ route('admin.settings.ai') }}" class="swbs-form">
                @csrf
                <div class="swbs-form-group">
                    <label>
                        <input type="checkbox" name="ai_enabled" value="1" {{ ($ai['ai.enabled'] ?? '1') === '1' ? 'checked' : '' }}>
                        Activer l’assistant IA
                    </label>
                </div>
                <div class="swbs-form-group">
                    <label for="ai_provider">Provider</label>
                    <input id="ai_provider" name="ai_provider" type="text" value="{{ old('ai_provider', $ai['ai.provider'] ?? '') }}" placeholder="openai, autre...">
                </div>
                <div class="swbs-form-group">
                    <label for="ai_model">Modèle</label>
                    <input id="ai_model" name="ai_model" type="text" value="{{ old('ai_model', $ai['ai.model'] ?? '') }}" placeholder="gpt-4o-mini, ...">
                </div>
                <div class="swbs-form-group">
                    <label for="ai_instructions">Instructions système</label>
                    <textarea id="ai_instructions" name="ai_instructions" rows="4">{{ old('ai_instructions', $ai['ai.instructions'] ?? '') }}</textarea>
                </div>
                <button type="submit" class="swbs-btn swbs-btn-primary">{{ __('generic.save') }}</button>
            </form>
        </section>

        <section>
            <h2>Paiement (FedePay)</h2>
            <form method="POST" action="{{ route('admin.settings.payment') }}" class="swbs-form">
                @csrf
                <div class="swbs-form-group">
                    <label for="payment_provider">Provider</label>
                    <input id="payment_provider" name="payment_provider" type="text" value="{{ old('payment_provider', $payment['payment.provider'] ?? 'fedepay') }}">
                </div>
                <div class="swbs-form-group">
                    <label for="fedepay_public">FedePay Public Key</label>
                    <input id="fedepay_public" name="fedepay_public" type="text" value="{{ old('fedepay_public', $payment['payment.fedepay.public'] ?? '') }}">
                </div>
                <div class="swbs-form-group">
                    <label for="fedepay_secret">FedePay Secret Key</label>
                    <input id="fedepay_secret" name="fedepay_secret" type="text" value="{{ old('fedepay_secret', $payment['payment.fedepay.secret'] ?? '') }}">
                </div>
                <div class="swbs-form-group">
                    <label for="fedepay_mode">Mode</label>
                    <select id="fedepay_mode" name="fedepay_mode">
                        <option value="sandbox" @selected(($payment['payment.fedepay.mode'] ?? 'sandbox') === 'sandbox')>Sandbox</option>
                        <option value="live" @selected(($payment['payment.fedepay.mode'] ?? 'sandbox') === 'live')>Live</option>
                    </select>
                </div>
                <button type="submit" class="swbs-btn swbs-btn-primary">{{ __('generic.save') }}</button>
            </form>
        </section>
    </div>
@endsection