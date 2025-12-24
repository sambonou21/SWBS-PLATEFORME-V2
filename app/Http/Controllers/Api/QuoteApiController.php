<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\QuoteRequest;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;

class QuoteApiController extends Controller
{
    public function store(QuoteRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = $request->user();

        $quote = Quote::create([
            'user_id' => $user?->id,
            'service_id' => $data['service_id'] ?? null,
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'company' => $data['company'] ?? null,
            'project_type' => $data['project_type'] ?? null,
            'budget_min' => $data['budget_min'] ?? null,
            'budget_max' => $data['budget_max'] ?? null,
            'currency' => $data['currency'],
            'message' => $data['message'],
        ]);

        $adminEmail = config('mail.from.address');
        Mail::raw(
            "Nouvelle demande de devis SWBS (#{$quote->id}) de {$quote->name} ({$quote->email}).",
            function ($message) use ($adminEmail) {
                $message->to($adminEmail)->subject('Nouvelle demande de devis SWBS (API)');
            }
        );

        return response()->json([
            'status' => 'ok',
            'quote_id' => $quote->id,
        ]);
    }
}