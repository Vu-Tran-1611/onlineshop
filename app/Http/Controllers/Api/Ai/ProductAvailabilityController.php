<?php

namespace App\Http\Controllers\Api\Ai;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ai\ProductAvailabilityRequest;
use App\Http\Resources\Ai\ProductAvailabilityResource;
use App\Services\Ai\ProductAvailabilityService;
use Illuminate\Http\JsonResponse;

class ProductAvailabilityController extends Controller
{
    public function __construct(private readonly ProductAvailabilityService $availabilityService)
    {
    }

    /**
     * Return stock quantity and price for products matching a name.
     */
    public function show(ProductAvailabilityRequest $request): JsonResponse
    {
        $name = $request->validated('name');
        $limit = (int) $request->validated('limit', 5);

        $result = $this->availabilityService->findByName($name, $limit);
        $products = $result['products'];

        return response()->json([
            'success' => true,
            'query' => ['name' => $name, 'limit' => $limit],
            'found' => $products->isNotEmpty(),
            'match_type' => $result['match_type'],
            'count' => $products->count(),
            'data' => ProductAvailabilityResource::collection($products)->resolve($request),
        ]);
    }
}
