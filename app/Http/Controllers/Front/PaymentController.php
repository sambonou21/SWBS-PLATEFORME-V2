<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\FedepayService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function callback(Request $request, Order $order, FedepayService $fedepayService)
    {
        $reference = $order->payment_reference;
        if (! $reference) {
            return response()->json(['status' => 'error', 'message' => 'Référence manquante'], 400);
        }

        $result = $fedepayService->verifyPayment($reference);

        if ($result && ($result['status'] ?? null) === 'success') {
            $order->status = 'paid';
            $order->paid_at = now();
            $order->save();
        } elseif ($result && ($result['status'] ?? null) === 'failed') {
            $order->status = 'failed';
            $order->save();
        }

        return response()->json(['status' => 'ok']);
    }

    public function return(Request $request, Order $order): View|RedirectResponse
    {
        if (! $order->customer_email) {
            return redirect()->route('dashboard')->with('status', __('messages.order_created'));
        }

        return view('shop.payment-return', compact('order'));
    }
}