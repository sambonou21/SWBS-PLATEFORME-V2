@extends('layouts.admin')

@section('title', 'Conversation #'.$conversation->id)

@section('content')
    <h1>Conversation #{{ $conversation->id }}</h1>

    <div class="swbs-two-columns">
        <section class="swbs-side-box">
            <h2>Client / prospect</h2>
            <p><strong>Nom :</strong> {{ $conversation->user?->name ?? $conversation->prospect_name ?? 'Invité' }}</p>
            <p><strong>Email :</strong> {{ $conversation->user?->email ?? $conversation->prospect_email }}</p>
            <p><strong>Téléphone :</strong> {{ $conversation->user?->phone ?? $conversation->prospect_phone ?? '-' }}</p>
            <p><strong>Type :</strong> {{ $conversation->is_prospect ? 'Prospect' : 'Client' }}</p>
            <p><strong>IP :</strong> {{ $conversation->ip_address ?? '-' }}</p>
            <p><strong>Pays :</strong> {{ $conversation->country ?? '-' }}</p>
        </section>

        <section>
            <h2>Messages</h2>
            <div class="swbs-chat-messages" style="max-height: 340px;">
                @foreach($conversation->messages as $message)
                    @php
                        $cls = 'swbs-chat-message-admin';
                        if (in_array($message->sender_type, ['user', 'guest'], true)) {
                            $cls = 'swbs-chat-message-user';
                        } elseif ($message->sender_type === 'ai') {
                            $cls = 'swbs-chat-message-ai';
                        }
                    @endphp
                    <div class="swbs-chat-message {{ $cls }}">
                        <small>{{ $message->created_at?->format('d/m/Y H:i') }} – {{ $message->sender_type }}</small><br>
                        {{ $message->content }}
                    </div>
                @endforeach
            </div>

            <h3>Répondre</h3>
            <form method="POST" action="{{ route('admin.chat.reply', $conversation) }}" class="swbs-form">
                @csrf
                <div class="swbs-form-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" rows="3" required></textarea>
                    @error('message')<p class="swbs-form-error">{{ $message }}</p>@enderror
                </div>
                <button type="submit" class="swbs-btn swbs-btn-primary">{{ __('chat.reply_button', [], 'fr') ?? __('generic.save') }}</button>
            </form>
        </section>
    </div>
@endsection