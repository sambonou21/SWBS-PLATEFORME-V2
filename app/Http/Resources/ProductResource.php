<?php

namespace App\Http\Resources;

use App\Services\CurrencyService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * @param  \App\Models\Product  $this
     */
    public function toArray(Request $request): array
    {
        $currencyService = app(CurrencyService::class);
        $currency = $request->get('currency', session('currency', $currencyService->getDefaultCurrency()));

        $priceCurrency = $currencyService->fromFcfa($this->price_fcfa, $currency);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'short_description' => $this->short_description,
            'description' => $this->description,
            'price_fcfa' => (float) $this->price_fcfa,
            'price' => $priceCurrency,
            'currency' => $currency,
            'main_image_url' => $this->main_image_path,
        ];
    }
}