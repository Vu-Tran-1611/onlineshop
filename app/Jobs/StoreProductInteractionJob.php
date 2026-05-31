<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\UserProductInteraction;
use Illuminate\Support\Facades\Cache;

class StoreProductInteractionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public $userId;
    public $productId;
    public $interactionType;
    public function __construct($userId, $productId, $interactionType)
    {
        $this->userId = $userId;
        $this->productId = $productId;
        $this->interactionType = $interactionType;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        UserProductInteraction::create([
            "user_id" => $this->userId,
            "product_id" => $this->productId,
            "interaction_type" => $this->interactionType
        ]);
    }
}
