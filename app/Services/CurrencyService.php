<?php

namespace App\Services;

use App\Models\Setting;

class CurrencyService
{
    protected array $supported = ['FCFA', 'NGN', 'USD', 'EUR'];

    public function getDefaultCurrency(): string
    {
        return Setting::get('currency.default', 'FCFA');
    }

    public function getSupportedCurrencies(): array
    {
        $fromDb = Setting::get('currency.available');

        if ($fromDb) {
            $decoded = json_decode($fromDb, true);
            if (is_array($decoded) && $decoded !== []) {
                return $decoded;
            }
        }

        return $this->supported;
    }

    public function isSupported(string $currency): bool
    {
        return in_array(strtoupper($currency), $this->getSupportedCurrencies(), true);
    }

    public function rate(string $currency): float
    {
        $currency = strtoupper($currency);
        $key = 'currency.rate.'.$currency;

        $value = Setting::get($key);

        if (! $value) {
            return 1.0;
        }

        return (float) $value;
    }

    /**
     * Convertit un montant depuis le FCFA vers une autre devise.
     */
    public function fromFcfa(float $amountFcfa, string $toCurrency): float
    {
        $rate = $this->rate($toCurrency);

        if ($rate <= 0) {
            return $amountFcfa;
        }

        // Les taux sont définis comme 1 unité de devise = X FCFA.
        return round($amountFcfa / $rate, 2);
    }

    /**
     * Convertit un montant vers le FCFA depuis une autre devise.
     */
    public function toFcfa(float $amount, string $fromCurrency): float
    {
        $rate = $this->rate($fromCurrency);

        if ($rate <= 0) {
            return $amount;
        }

        return round($amount * $rate, 2);
    }
}