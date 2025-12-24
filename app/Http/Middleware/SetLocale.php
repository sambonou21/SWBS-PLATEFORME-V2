<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->get('lang')
            ?? $request->session()->get('locale')
            ?? ($request->user()->locale ?? null)
            ?? config('app.locale', 'fr');

        if (! in_array($locale, ['fr', 'en'], true)) {
            $locale = config('app.locale', 'fr');
        }

        app()->setLocale($locale);
        $request->session()->put('locale', $locale);

        return $next($request);
    }
}