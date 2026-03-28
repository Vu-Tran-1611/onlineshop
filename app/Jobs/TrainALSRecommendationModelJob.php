<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TrainALSRecommendationModelJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public $userID;
    public function __construct($userID)
    {
        $this->userID = $userID;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $recommendationService = new RecommendationApiService();
        $response = $recommendationService->getUserPersonalizedRecommendations($this->userID,"matrix_factorization", 10);
        $recommendedProductIDs = $response["recommendations"];
        Cache::put("user:{$this->userID}:mf_recommendations", $recommendedProductIDs, now()->addMinutes(10));
    }
}
