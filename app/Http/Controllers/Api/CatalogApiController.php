<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Resources\PortfolioResource;
use App\Http\Resources\ServiceResource;
use App\Models\Product;
use App\Models\Portfolio;
use App\Models\Service;

class CatalogApiController extends Controller
{
    public function services()
    {
        return ServiceResource::collection(
            Service::where('is_active', true)->get()
        );
    }

    public function portfolio()
    {
        return PortfolioResource::collection(
            Portfolio::orderByDesc('created_at')->get()
        );
    }

    public function products()
    {
        return ProductResource::collection(
            Product::where('is_active', true)->get()
        );
    }

    public function product(string $slug)
    {
        $product = Product::where('slug', $slug)->where('is_active', true)->firstOrFail();

        return new ProductResource($product);
    }
}