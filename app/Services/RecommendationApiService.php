<?php
namespace App\Services;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;

class RecommendationApiService
{
    public function getRecommendations($productId,$model,$topK,$version = "v1")
    {
        $baseUrl = config('services.python_api.url');
        $response = Http::timeout(10)->post($baseUrl . '/recommend', [
            'product_id' => $productId,
            'model_name' => $model,
            'top_k' => $topK,
            'version' => $version
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        throw new RequestException($response);
    }

    // For user-based recommendations
    // 1 Recent interactions of the user (clicks, purchases, etc.) with product IDs and interaction types
    public function getUserRecentRecommendations($userId,$interactions,$model,$topK)
    {
        $baseUrl = config('services.python_api.url');
        $response = Http::timeout(10)->post($baseUrl . '/recommend/recent', [
            'user_id' => $userId,
            'interactions' => $interactions,
            'model_name' => $model,
            'top_k' => $topK,

        ]);

        if ($response->successful()) {
            return $response->json();
        }

        throw new RequestException($response);
    }

    // // 2 Personalized recommendations after training model with user interactions
    // public function getUserPersonalizedRecommendations($userId,$model,$topK)
    // {
    //     $baseUrl = config('services.python_api.url');
    //     $response = Http::timeout(10)->post($baseUrl . '/recommend/retrain', [
    //         'user_id' => $userId,
    //         'model_name' => $model,
    //         'top_k' => $topK
    //     ]);

    //     if ($response->successful()) {
    //         return $response->json();
    //     }

    //     throw new RequestException($response);
    // }
}
