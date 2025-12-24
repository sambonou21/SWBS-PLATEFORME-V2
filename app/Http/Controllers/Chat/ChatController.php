<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Services\ChatService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function __construct(
        protected ChatService $chatService
    ) {
    }

    public function start(Request $request): JsonResponse
    {
        $conversation = $this->chatService->start(
            $request->user(),
            $request->string('name')->toString() ?: null,
            $request->string('email')->toString() ?: null,
        );

        return response()->json([
            'conversation_id' => $conversation->id,
        ]);
    }

    public function send(Request $request): JsonResponse
    {
        $data = $request->validate([
            'conversation_id' => ['required', 'integer', 'exists:conversations,id'],
            'message' => ['required', 'string', 'min:1'],
        ]);

        $conversation = Conversation::findOrFail($data['conversation_id']);

        $message = $this->chatService->sendMessage(
            $conversation,
            $data['message'],
            $request->user() ? 'user' : 'guest'
        );

        return response()->json([
            'message' => [
                'id' => $message->id,
                'sender_type' => $message->sender_type,
                'content' => $message->content,
                'created_at' => $message->created_at?->toIso8601String(),
            ],
        ]);
    }

    public function fetch(Conversation $conversation): JsonResponse
    {
        $messages = $conversation->messages()
            ->orderBy('created_at')
            ->get()
            ->map(fn ($msg) => [
                'id' => $msg->id,
                'sender_type' => $msg->sender_type,
                'content' => $msg->content,
                'created_at' => $msg->created_at?->toIso8601String(),
            ]);

        return response()->json([
            'conversation_id' => $conversation->id,
            'messages' => $messages,
        ]);
    }
}