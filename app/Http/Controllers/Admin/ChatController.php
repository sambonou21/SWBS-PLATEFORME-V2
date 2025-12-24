<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Services\ChatService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChatController extends Controller
{
    public function __construct(
        protected ChatService $chatService
    ) {
    }

    public function index(): View
    {
        $conversations = Conversation::latest('last_message_at')
            ->with('user')
            ->paginate(25);

        return view('admin.chat.index', compact('conversations'));
    }

    public function show(Conversation $conversation): View
    {
        $conversation->load('messages', 'user');

        return view('admin.chat.show', compact('conversation'));
    }

    public function reply(Request $request, Conversation $conversation): RedirectResponse
    {
        $data = $request->validate([
            'message' => ['required', 'string', 'min:1'],
        ]);

        $this->chatService->sendMessage($conversation, $data['message'], 'admin');

        return redirect()->route('admin.chat.show', $conversation)->with('status', 'Message envoyé.');
    }
}