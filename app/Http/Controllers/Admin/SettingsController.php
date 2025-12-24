<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SettingsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function __construct(
        protected SettingsService $settings
    ) {
    }

    public function index(): View
    {
        return view('admin.settings.index', [
            'general' => $this->settings->allByGroup('general'),
            'currency' => $this->settings->allByGroup('currency'),
            'payment' => $this->settings->allByGroup('payment'),
            'ai' => $this->settings->allByGroup('ai'),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'company_tagline' => ['nullable', 'string', 'max:255'],
            'company_email' => ['required', 'email', 'max:255'],
            'company_phone' => ['nullable', 'string', 'max:255'],
            'company_address' => ['nullable', 'string'],
        ]);

        $this->settings->set('company.name', $data['company_name'], 'general');
        $this->settings->set('company.tagline', $data['company_tagline'] ?? '', 'general');
        $this->settings->set('company.email', $data['company_email'], 'general');
        $this->settings->set('company.phone', $data['company_phone'] ?? '', 'general');
        $this->settings->set('company.address', $data['company_address'] ?? '', 'general');

        return back()->with('status', 'Paramètres généraux mis à jour.');
    }

    public function updateCurrencies(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'default_currency' => ['required', 'string', 'max:10'],
            'rate_FCFA' => ['required', 'numeric'],
            'rate_NGN' => ['required', 'numeric'],
            'rate_USD' => ['required', 'numeric'],
            'rate_EUR' => ['required', 'numeric'],
        ]);

        $this->settings->set('currency.default', strtoupper($data['default_currency']), 'currency');
        $this->settings->set('currency.rate.FCFA', $data['rate_FCFA'], 'currency');
        $this->settings->set('currency.rate.NGN', $data['rate_NGN'], 'currency');
        $this->settings->set('currency.rate.USD', $data['rate_USD'], 'currency');
        $this->settings->set('currency.rate.EUR', $data['rate_EUR'], 'currency');

        return back()->with('status', 'Taux de conversion mis à jour.');
    }

    public function updateAi(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'ai_enabled' => ['nullable', 'boolean'],
            'ai_provider' => ['nullable', 'string', 'max:255'],
            'ai_model' => ['nullable', 'string', 'max:255'],
            'ai_instructions' => ['nullable', 'string'],
        ]);

        $this->settings->set('ai.enabled', $request->boolean('ai_enabled') ? '1' : '0', 'ai');
        $this->settings->set('ai.provider', $data['ai_provider'] ?? '', 'ai');
        $this->settings->set('ai.model', $data['ai_model'] ?? '', 'ai');
        $this->settings->set('ai.instructions', $data['ai_instructions'] ?? '', 'ai');

        return back()->with('status', 'Configuration IA mise à jour.');
    }

    public function updatePayment(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'payment_provider' => ['required', 'string', 'max:255'],
            'fedepay_public' => ['nullable', 'string', 'max:255'],
            'fedepay_secret' => ['nullable', 'string', 'max:255'],
            'fedepay_mode' => ['required', 'in:sandbox,live'],
        ]);

        $this->settings->set('payment.provider', $data['payment_provider'], 'payment');
        $this->settings->set('payment.fedepay.public', $data['fedepay_public'] ?? '', 'payment');
        $this->settings->set('payment.fedepay.secret', $data['fedepay_secret'] ?? '', 'payment');
        $this->settings->set('payment.fedepay.mode', $data['fedepay_mode'], 'payment');

        return back()->with('status', 'Configuration paiement mise à jour.');
    }
}