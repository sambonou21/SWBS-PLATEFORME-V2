<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact.index');
    }

    public function submit(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'min:10'],
        ]);

        $adminEmail = config('mail.from.address');

        Mail::raw(
            "Nouveau message de contact SWBS de {$data['name']} ({$data['email']})\n\nSujet : {$data['subject']}\n\n{$data['message']}",
            function ($message) use ($adminEmail, $data) {
                $message->to($adminEmail)->subject('[SWBS] '.$data['subject']);
            }
        );

        return redirect()->route('contact.index')->with('status', __('messages.contact_submitted'));
    }
}