<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;

class PortfolioController extends Controller
{
    public function index()
    {
        $items = Portfolio::orderByDesc('created_at')->paginate(9);

        return view('portfolio.index', [
            'items' => $items,
        ]);
    }

    public function show(string $slug)
    {
        $item = Portfolio::where('slug', $slug)->firstOrFail();

        return view('portfolio.show', [
            'item' => $item,
        ]);
    }
}