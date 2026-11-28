<?php

namespace App\Services\Chat;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use Throwable;

// Stores chatbot conversations in Redis, one JSON value per conversation
class ChatMemoryService
{
    // Cookie that holds the conversation ID (encrypted by Laravel)
    public const COOKIE = 'bot_conversation';

    // Idle conversations expire after 7 days; each message refreshes it
    public const TTL_MINUTES = 60 * 24 * 7;

    // Keep at most this many messages per conversation
    private const MAX_STORED_MESSAGES = 100;

    // Send the AI only the most recent messages
    private const HISTORY_FOR_AI = 20;

    // Create a new random conversation ID
    public function newId(): string
    {
        return (string) Str::uuid();
    }

    // Check that a cookie value is a conversation ID we could have issued
    public function isValidId(?string $id): bool
    {
        return is_string($id) && Str::isUuid($id);
    }

    // Load a conversation the current user may use, or null
    public function load(string $id, ?int $userId): ?array
    {
        try {
            $raw = Redis::get($this->key($id));
        } catch (Throwable $e) {
            Log::warning('Chat memory unavailable', ['error' => $e->getMessage()]);
            return null;
        }

        $conversation = $raw ? json_decode($raw, true) : null;
        if (!is_array($conversation)) {
            return null;
        }

        // A conversation linked to an account belongs only to that account
        $owner = $conversation['user_id'] ?? null;
        if ($owner !== null && $owner !== $userId) {
            return null;
        }

        return $conversation;
    }

    // Add messages to a conversation and refresh its expiry
    public function append(string $id, ?int $userId, ?array $conversation, array $messages): void
    {
        $conversation ??= ['user_id' => null, 'messages' => []];
        // Link the conversation to the account once the user is logged in
        $conversation['user_id'] ??= $userId;
        $conversation['messages'] = array_slice(
            array_merge($conversation['messages'], $messages),
            -self::MAX_STORED_MESSAGES
        );

        try {
            Redis::set($this->key($id), json_encode($conversation), 'EX', self::TTL_MINUTES * 60);
        } catch (Throwable $e) {
            Log::warning('Chat memory not saved', ['error' => $e->getMessage()]);
        }
    }

    // Return the last messages as role/content pairs for the AI, with shown products and badges
    public function historyForAi(?array $conversation): array
    {
        $messages = array_slice($conversation['messages'] ?? [], -self::HISTORY_FOR_AI);

        return array_map(function (array $message) {
            $content = $message['content'];
            if (!empty($message['products_note'])) {
                $content .= "\n[Shown products: {$message['products_note']}]";
            }
            if (!empty($message['badges_note'])) {
                $content .= "\n[Offered subcategories: {$message['badges_note']}]";
            }

            return ['role' => $message['role'], 'content' => $content];
        }, $messages);
    }

    // Delete a conversation
    public function clear(string $id): void
    {
        try {
            Redis::del($this->key($id));
        } catch (Throwable $e) {
            Log::warning('Chat memory not cleared', ['error' => $e->getMessage()]);
        }
    }

    // Build the Redis key for a conversation
    private function key(string $id): string
    {
        return "bot:conversation:{$id}";
    }
}
