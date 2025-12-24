<?php

namespace App\Http\Controllers;

use App\Services\CurrencyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function __construct(
        protected CurrencyService $currencyService
    ) {
    }

    public function switch(Request $request): RedirectResponse
    {
        $request->validate([
            'currency' => 'required|string',
        ]);

        $currency = strtoupper($request->string('currency')->toString());

        if (! $this->currencyService->isSupported($currency)) {
            $currency = $this->currencyService->getDefaultCurrency();
        }

        $request->session()->put('currency', $currency);

        $redirect = $request->headers->get('referer') ?: route('home');

        return redirect()->to($redirect);
    }
}