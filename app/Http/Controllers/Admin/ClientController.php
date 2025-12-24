<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\View\View;

class ClientController extends Controller
{
    public function index(): View
    {
        $clients = User::where('role', 'user')->orderBy('name')->paginate(25);

        return view('admin.clients.index', compact('clients'));
    }

    public function show(User $client): View
    {
        $client->load('quotes', 'orders');

        return view('admin.clients.show', compact('client'));
    }
}