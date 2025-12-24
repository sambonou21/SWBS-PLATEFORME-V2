<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Services\CurrencyService;

class ServiceController extends Controller
{
    public function __construct(
        protected CurrencyService $currencyService
    ) {
    }

    public function index()
    {
        $services = Service::where('is_active', true)->get();

        return view('services.index', [
            'services' => $services,
            'currencyService' => $this->currencyService,
        ]);
    }

    public function show(string $slug)
    {
        $service = Service::where('slug', $slug)->where('is_active', true)->firstOrFail();

        return view('services.show', [
            'service' => $service,
            'currencyService' => $this->currencyService,
        ]);
    }
}