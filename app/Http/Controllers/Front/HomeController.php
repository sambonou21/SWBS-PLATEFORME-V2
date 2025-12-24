<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Service;
use App\Models\Portfolio;
use App\Services\CurrencyService;

class HomeController extends Controller
{
    public function __construct(
        protected CurrencyService $currencyService
    ) {
    }

    public function index()
    {
        $services = Service::where('is_active', true)->take(4)->get();
        $portfolio = Portfolio::where('is_featured', true)->take(6)->get();
        $products = Product::where('is_active', true)->take(3)->get();

        return view('home', [
            'services' => $services,
            'portfolio' => $portfolio,
            'products' => $products,
            'currencyService' => $this->currencyService,
        ]);
    }
}