<?php

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('conversations.{conversation}', function (User $user, Conversation $conversation) {
    if ($user->isAdmin()) {
        return true;
    }

    return $conversation->user_id === $user->id;
});