<?php

namespace App\Http\Middleware;

use App\Services\CurrencyService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetCurrency
{
    public function __construct(
        protected CurrencyService $currencyService
    ) {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $currency = $request->get('currency')
            ?? $request->session()->get('currency')
            ?? ($request->user()->currency ?? null)
            ?? $this->currencyService->getDefaultCurrency();

        if (! $this->currencyService->isSupported($currency)) {
            $currency = $this->currencyService->getDefaultCurrency();
        }

        $request->session()->put('currency', $currency);

        return $next($request);
    }
}