<?php

namespace App\Services;

use App\Events\ChatMessageSent;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ChatService
{
    public function __construct(
        protected AiChatService $aiChatService
    ) {
    }

    public function start(?User $user = null, ?string $prospectName = null, ?string $prospectEmail = null): Conversation
    {
        if ($user) {
            $conversation = Conversation::firstOrCreate(
                ['user_id' => $user->id, 'status' => 'open'],
                [
                    'prospect_name' => $user->name,
                    'prospect_email' => $user->email,
                    'is_prospect' => false,
                ]
            );
        } else {
            $sessionId = session()->getId();

            $conversation = Conversation::firstOrCreate(
                ['prospect_session_id' => $sessionId, 'status' => 'open'],
                [
                    'prospect_name' => $prospectName,
                    'prospect_email' => $prospectEmail,
                    'is_prospect' => true,
                ]
            );
        }

        return $conversation;
    }

    public function sendMessage(Conversation $conversation, string $content, string $senderType = 'user'): Message
    {
        $user = Auth::user();

        $message = $conversation->messages()->create([
            'sender_id' => $user?->id,
            'sender_type' => $senderType,
            'content' => $content,
            'seen_by_admin' => $senderType !== 'admin',
            'seen_by_user' => $senderType === 'admin' || $senderType === 'ai',
        ]);

        $conversation->update([
            'last_message_at' => now(),
        ]);

        ChatMessageSent::dispatch($conversation, $message);

        // Réponse IA si aucun admin connecté et message utilisateur
        if ($senderType === 'user' || $senderType === 'guest') {
            $this->maybeReplyWithAi($conversation, $message);
        }

        return $message;
    }

    protected function maybeReplyWithAi(Conversation $conversation, Message $lastMessage): void
    {
        $summary = $conversation->messages()
            ->latest()
            ->take(10)
            ->get()
            ->reverse()
            ->map(fn (Message $msg) => '['.$msg->sender_type.'] '.$msg->content)
            ->implode("\n");

        $reply = $this->aiChatService->reply($summary, $lastMessage->content, app()->getLocale());

        if (! $reply) {
            return;
        }

        $aiMessage = $conversation->messages()->create([
            'sender_id' => null,
            'sender_type' => 'ai',
            'content' => $reply,
            'seen_by_admin' => false,
            'seen_by_user' => false,
        ]);

        $conversation->update([
            'last_message_at' => now(),
        ]);

        ChatMessageSent::dispatch($conversation, $aiMessage);
    }
}