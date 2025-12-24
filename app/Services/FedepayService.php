<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class FedepayService
{
    public function createPayment(Order $order): array
    {
        $publicKey = config('services.fedepay.public');
        $secretKey = config('services.fedepay.secret');
        $mode = config('services.fedepay.mode', 'sandbox');

        if (! $publicKey || ! $secretKey) {
            throw new \RuntimeException('Clés FedePay manquantes dans la configuration.');
        }

        $amount = (int) round($order->total_amount_fcfa);
        $currency = $order->currency;
        $reference = $order->payment_reference ?: Str::uuid()->toString();

        $payload = [
            'amount' => $amount,
            'currency' => $currency,
            'reference' => $reference,
            'customer' => [
                'name' => $order->customer_name,
                'email' => $order->customer_email,
                'phone' => $order->customer_phone,
            ],
            'callback_url' => route('shop.payment.callback', ['order' => $order->id]),
            'return_url' => route('shop.payment.return', ['order' => $order->id]),
            'metadata' => [
                'order_id' => $order->id,
                'platform' => 'SWBS-PLATEFORME-V2',
            ],
        ];

        $baseUrl = $mode === 'live'
            ? 'https://api.fedepay.com'
            : 'https://sandbox-api.fedepay.com';

        $response = Http::withToken($secretKey)
            ->acceptJson()
            ->post($baseUrl.'/payments', $payload);

        if (! $response->successful()) {
            throw new \RuntimeException('Erreur lors de la création du paiement FedePay.');
        }

        $data = $response->json();

        $order->update([
            'payment_reference' => $reference,
            'payment_payload' => $data,
        ]);

        return $data;
    }

    public function verifyPayment(string $reference): ?array
    {
        $secretKey = config('services.fedepay.secret');
        $mode = config('services.fedepay.mode', 'sandbox');

        $baseUrl = $mode === 'live'
            ? 'https://api.fedepay.com'
            : 'https://sandbox-api.fedepay.com';

        $response = Http::withToken($secretKey)
            ->acceptJson()
            ->get($baseUrl.'/payments/'.$reference);

        if (! $response->successful()) {
            return null;
        }

        return $response->json();
    }
}