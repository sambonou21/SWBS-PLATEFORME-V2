<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        // Informations générales SWBS
        Setting::set('company.name', 'Sam Web Business Services (SWBS)', 'general');
        Setting::set('company.tagline', 'Plateforme digitale tout-en-un pour les entreprises', 'general');
        Setting::set('company.description', 'Sam Web Business Services (SWBS) est une plateforme digitale tout-en-un permettant aux entreprises de présenter leurs services, gérer leurs clients, automatiser leurs devis, communiquer en temps réel et vendre leurs produits.', 'general');
        Setting::set('company.website', config('app.url'), 'general');
        Setting::set('company.email', env('MAIL_FROM_ADDRESS', 'no-reply@swbs.site'), 'general');
        Setting::set('company.phone', '+237 600 00 00 00', 'general');
        Setting::set('company.address', 'Douala, Cameroun', 'general');

        // Devis
        Setting::set('quotes.auto_assign_admin', '1', 'quotes');
        Setting::set('quotes.notify_email', env('ADMIN_EMAIL', 'admin@swbs.site'), 'quotes');

        // Devises
        Setting::set('currency.default', 'FCFA', 'currency');
        Setting::set('currency.available', json_encode(['FCFA', 'NGN', 'USD', 'EUR']), 'currency', 'json');
        Setting::set('currency.rate.FCFA', '1', 'currency');
        Setting::set('currency.rate.NGN', '0.95', 'currency'); // 1 NGN ~ 0,95 FCFA (approximatif)
        Setting::set('currency.rate.USD', '600', 'currency'); // 1 USD ~ 600 FCFA (approximatif)
        Setting::set('currency.rate.EUR', '650', 'currency'); // 1 EUR ~ 650 FCFA (approximatif)

        // Paiement FedePay
        Setting::set('payment.provider', 'fedepay', 'payment');
        Setting::set('payment.fedepay.public', env('FEDEPAY_PUBLIC', ''), 'payment');
        Setting::set('payment.fedepay.secret', env('FEDEPAY_SECRET', ''), 'payment');
        Setting::set('payment.fedepay.mode', env('FEDEPAY_MODE', 'sandbox'), 'payment');

        // IA (assistant chat)
        Setting::set('ai.enabled', '1', 'ai');
        Setting::set('ai.provider', env('AI_PROVIDER', ''), 'ai');
        Setting::set('ai.model', env('AI_MODEL', 'gpt-4o-mini'), 'ai');
        Setting::set('ai.instructions', env('AI_INSTRUCTIONS', ''), 'ai');
    }
}