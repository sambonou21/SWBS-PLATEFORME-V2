<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function switch(Request $request): RedirectResponse
    {
        $request->validate([
            'lang' => 'required|in:fr,en',
        ]);

        $request->session()->put('locale', $request->string('lang')->toString());

        $redirect = $request->headers->get('referer') ?: route('home');

        return redirect()->to($redirect);
    }
}