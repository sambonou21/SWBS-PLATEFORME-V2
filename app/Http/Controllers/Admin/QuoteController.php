<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quote;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QuoteController extends Controller
{
    public function index(): View
    {
        $quotes = Quote::with('user', 'service')->latest()->paginate(25);

        return view('admin.quotes.index', compact('quotes'));
    }

    public function show(Quote $quote): View
    {
        $quote->load('user', 'service');

        return view('admin.quotes.show', compact('quote'));
    }

    public function update(Request $request, Quote $quote): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:recu,en_cours,valide,refuse'],
            'admin_notes' => ['nullable', 'string'],
        ]);

        $quote->status = $data['status'];
        $quote->admin_notes = $data['admin_notes'] ?? null;
        $quote->responded_at = now();
        $quote->save();

        return redirect()->route('admin.quotes.show', $quote)->with('status', 'Devis mis à jour.');
    }

    public function destroy(Quote $quote): RedirectResponse
    {
        $quote->delete();

        return redirect()->route('admin.quotes.index')->with('status', 'Devis supprimé.');
    }
}