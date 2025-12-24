<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Quote;
use App\Models\Service;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'services_count' => Service::count(),
            'quotes_count' => Quote::count(),
            'orders_count' => Order::count(),
            'clients_count' => User::where('role', 'user')->count(),
        ];

        $latestQuotes = Quote::latest()->take(5)->get();
        $latestOrders = Order::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'latestQuotes', 'latestOrders'));
    }
}