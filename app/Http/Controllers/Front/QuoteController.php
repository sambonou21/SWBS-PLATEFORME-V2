<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\QuoteRequest;
use App\Models\Quote;
use App\Models\Service;
use App\Services\CurrencyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;

class QuoteController extends Controller
{
    public function __construct(
        protected CurrencyService $currencyService
    ) {
    }

    public function create()
    {
        $services = Service::where('is_active', true)->get();

        return view('quotes.create', [
            'services' => $services,
            'currencyService' => $this->currencyService,
        ]);
    }

    public function store(QuoteRequest $request): RedirectResponse
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

        // Notification simple par email vers l'admin
        $adminEmail = config('mail.from.address');
        Mail::raw(
            "Nouvelle demande de devis SWBS (#{$quote->id}) de {$quote->name} ({$quote->email}).",
            function ($message) use ($adminEmail) {
                $message->to($adminEmail)->subject('Nouvelle demande de devis SWBS');
            }
        );

        return redirect()->route('quotes.create')
            ->with('status', __('messages.quote_submitted'));
    }
}