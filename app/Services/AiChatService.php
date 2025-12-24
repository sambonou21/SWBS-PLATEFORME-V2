<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;

class AiChatService
{
    public function isEnabled(): bool
    {
        return (bool) Setting::get('ai.enabled', '1');
    }

    public function reply(string $conversationSummary, string $lastMessage, string $locale = 'fr'): ?string
    {
        if (! $this->isEnabled()) {
            return null;
        }

        $provider = Setting::get('ai.provider', config('services.ai.provider'));
        $apiKey = Setting::get('ai.api_key', config('services.ai.api_key'));
        $model = Setting::get('ai.model', config('services.ai.model', 'gpt-4o-mini'));
        $instructions = Setting::get('ai.instructions', config('services.ai.instructions', ''));

        if (! $provider || ! $apiKey) {
            return null;
        }

        $prompt = $instructions ?: "Tu es l'assistant de Sam Web Business Services (SWBS). Tu réponds de manière claire, professionnelle et concise aux prospects qui s'intéressent aux services digitaux de SWBS.";

        $content = "Contexte conversationnel :\n".$conversationSummary."\n\nDernier message du prospect :\n".$lastMessage;

        if ($provider === 'openai') {
            $response = Http::withToken($apiKey)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => $model,
                    'messages' => [
                        ['role' => 'system', 'content' => $prompt],
                        ['role' => 'user', 'content' => $content],
                    ],
                ]);

            if (! $response->successful()) {
                return null;
            }

            return trim($response->json('choices.0.message.content', ''));
        }

        // Fallback générique compatible OpenAI-like
        $endpoint = config('services.ai.endpoint', '');
        if ($endpoint) {
            $response = Http::withToken($apiKey)->post($endpoint, [
                'model' => $model,
                'messages' => [
                    ['role' => 'system', 'content' => $prompt],
                    ['role' => 'user', 'content' => $content],
                ],
            ]);

            if (! $response->successful()) {
                return null;
            }

            return trim($response->json('choices.0.message.content', ''));
        }

        return null;
    }
}