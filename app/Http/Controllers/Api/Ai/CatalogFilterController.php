<?php

namespace App\Http\Controllers\Api\Ai;

use App\Http\Controllers\Controller;
use App\Services\Ai\CatalogFilterService;
use Illuminate\Http\JsonResponse;

class CatalogFilterController extends Controller
{
    public function __construct(private readonly CatalogFilterService $catalogFilterService)
    {
    }

    // Return catalog filter names for the AI agent
    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->catalogFilterService->filters(),
        ]);
    }
}
