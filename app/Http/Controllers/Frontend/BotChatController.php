<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Throwable;

class BotChatController extends Controller
{
    public function chat(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $baseUrl = config('services.fastapi_ai.url') ?: config('services.python_api.url');
        if (empty($baseUrl)) {
            return response()->json([
                'message' => 'FastAPI URL is not configured.',
            ], 500);
        }

        $endpoint = rtrim($baseUrl, '/') . '/chat';

        try {
            $response = Http::timeout(45)
                ->acceptJson()
                ->withQueryParameters([
                    'questions' => $validated['message'],
                ])
                ->post($endpoint, [
                    'message' => $validated['message'],
                    'questions' => $validated['message'],
                ])
            ;

            if ($response->successful()) {
                $answer = $response->json('answer');
                if (!is_string($answer) || trim($answer) === '') {
                    $answer = $response->json('response');
                }

                if (!is_string($answer) || trim($answer) === '') {
                    $answer = $response->json('message');
                }

                if (!is_string($answer) || trim($answer) === '') {
                    $answer = $response->body() ?? '';
                    $decoded = json_decode((string) $answer, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                        $answer = (string) ($decoded['answer'] ?? $decoded['response'] ?? $decoded['message'] ?? '');
                    }
                }

                $answer = is_string($answer) ? trim($answer) : '';

                return response()->json([
                    'answer' => $answer,
                    'answer_html' => Str::markdown($answer),
                ], $response->status());
            }

            $details = $response->json();
            if ($details === null) {
                $details = $response->body();
            }

            return response()->json([
                'message' => 'Bot service returned an error.',
                'details' => $details,
            ], $response->status());
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Bot service is temporarily unavailable.',
                'error' => $e->getMessage(),
            ], 502);
        }
    }
}
