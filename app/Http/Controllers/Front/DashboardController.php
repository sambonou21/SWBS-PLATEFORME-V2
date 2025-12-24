<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Quote;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $quotes = $user->quotes()->latest()->take(5)->get();
        $orders = $user->orders()->latest()->take(5)->get();

        return view('dashboard.index', [
            'user' => $user,
            'quotes' => $quotes,
            'orders' => $orders,
        ]);
    }

    public function orders(Request $request)
    {
        $orders = $request->user()->orders()->latest()->paginate(10);

        return view('dashboard.orders', compact('orders'));
    }

    public function quotes(Request $request)
    {
        $quotes = $request->user()->quotes()->latest()->paginate(10);

        return view('dashboard.quotes', compact('quotes'));
    }

    public function profile(Request $request)
    {
        return view('dashboard.profile', ['user' => $request->user()]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'company' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'locale' => ['required', 'in:fr,en'],
            'currency' => ['required', 'string', 'max:10'],
        ]);

        $user->update($data);

        $request->session()->put('locale', $user->locale);
        $request->session()->put('currency', $user->currency);

        return redirect()->route('dashboard.profile')->with('status', __('messages.profile_updated'));
    }
}