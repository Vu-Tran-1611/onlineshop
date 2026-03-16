<?php
namespace App\Services;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;

class RecommendationApiService
{
    public function getRecommendations($productId,$model,$topK)
    {
        $baseUrl = config('services.python_api.url');
        $response = Http::timeout(10)->post($baseUrl . '/recommend', [
            'product_id' => $productId,
            'model_name' => $model,
            'top_k' => $topK
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        throw new RequestException($response);
    }
}
