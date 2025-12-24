<div id="swbs-chat-widget"
     class="swbs-chat-widget"
     data-start-url="{{ route('chat.start') }}"
     data-send-url="{{ route('chat.send') }}"
     data-fetch-base-url="{{ route('chat.fetch', ['conversation' => '__ID__']) }}">
    <button id="swbs-chat-toggle" class="swbs-chat-toggle">
        <span class="swbs-chat-toggle-label">Chat</span>
    </button>

    <div id="swbs-chat-window" class="swbs-chat-window">
        <div class="swbs-chat-header">
            <div>
                <strong>SWBS</strong><br>
                <small>{{ __('chat.subtitle') }}</small>
            </div>
            <button id="swbs-chat-close" class="swbs-chat-close" aria-label="Fermer">×</button>
        </div>
        <div id="swbs-chat-messages" class="swbs-chat-messages"></div>
        <form id="swbs-chat-form" class="swbs-chat-form">
            @csrf
            <input type="hidden" id="swbs-chat-conversation-id" value="">
            <textarea id="swbs-chat-input" class="swbs-chat-input" placeholder="{{ __('chat.placeholder') }}" rows="2"></textarea>
            <button type="submit" class="swbs-btn swbs-btn-primary swbs-chat-submit">{{ __('chat.start') }}</button>
        </form>
    </div>
</div>