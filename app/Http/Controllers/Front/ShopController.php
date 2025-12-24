<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Services\CurrencyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function __construct(
        protected CurrencyService $currencyService
    ) {
    }

    public function index()
    {
        $products = Product::where('is_active', true)->paginate(12);

        return view('shop.index', [
            'products' => $products,
            'currencyService' => $this->currencyService,
        ]);
    }

    public function show(string $slug)
    {
        $product = Product::where('slug', $slug)->where('is_active', true)->firstOrFail();

        return view('shop.show', [
            'product' => $product,
            'currencyService' => $this->currencyService,
        ]);
    }

    /**
     * Création rapide d'une commande pour un produit (achat direct).
     */
    public function order(Request $request, string $slug, \App\Services\FedepayService $fedepayService): RedirectResponse
    {
        $product = Product::where('slug', $slug)->where('is_active', true)->firstOrFail();

        $user = $request->user();

        $quantity = max(1, (int) $request->input('quantity', 1));
        $totalFcfa = $product->price_fcfa * $quantity;

        $order = Order::create([
            'user_id' => $user?->id,
            'status' => 'pending',
            'total_amount_fcfa' => $totalFcfa,
            'currency' => 'FCFA',
            'exchange_rate' => 1,
            'total_amount_currency' => $totalFcfa,
            'payment_provider' => 'fedepay',
            'customer_name' => $user?->name ?? $request->input('name', 'Client SWBS'),
            'customer_email' => $user?->email ?? $request->input('email'),
            'customer_phone' => $user?->phone ?? $request->input('phone'),
            'customer_address' => $request->input('address'),
        ]);

        $order->items()->create([
            'product_id' => $product->id,
            'quantity' => $quantity,
            'unit_price_fcfa' => $product->price_fcfa,
            'total_price_fcfa' => $totalFcfa,
        ]);

        try {
            $payment = $fedepayService->createPayment($order);
        } catch (\Throwable $e) {
            return redirect()->back()->with('status', 'Erreur lors de la création du paiement : '.$e->getMessage());
        }

        $redirectUrl = $payment['payment_url'] ?? $payment['redirect_url'] ?? null;

        if (! $redirectUrl) {
            return redirect()->back()->with('status', 'Paiement FedePay créé, mais aucune URL de redirection n’a été fournie.');
        }

        return redirect()->away($redirectUrl);
    }
}