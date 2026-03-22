<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserReviewSeeder extends Seeder
{
    public function run(): void
    {
        $reviewTemplates = [
            5 => [
                'Excellent product, highly recommended.',
                'Very satisfied with the quality.',
                'Amazing product, worth the money.',
                'Works great and looks exactly as expected.',
                'One of the best purchases I have made.',
            ],
            4 => [
                'Good product overall.',
                'Pretty satisfied with this purchase.',
                'Nice quality and reasonable price.',
                'Works well and meets my expectations.',
                'A solid product with good value.',
            ],
            3 => [
                'It is okay for the price.',
                'Average experience, nothing special.',
                'The product is decent but has some minor issues.',
                'Acceptable quality overall.',
                'Not bad, but could be better.',
            ],
            2 => [
                'Below expectations.',
                'The product quality is not very good.',
                'Not very satisfied with this purchase.',
                'There are several things that could be improved.',
                'It works, but I expected more.',
            ],
            1 => [
                'Very disappointed with this product.',
                'Poor quality and not worth the price.',
                'I would not recommend this item.',
                'Bad experience overall.',
                'The product did not meet expectations at all.',
            ],
        ];

        // only review products user has already shown stronger interest in
        $eligiblePairs = DB::table('user_product_interactions')
            ->select('user_id', 'product_id')
            ->whereIn('interaction_type', ['wishlist_add', 'cart_add'])
            ->groupBy('user_id', 'product_id')
            ->get()
            ->map(fn ($row) => [
                'user_id' => $row->user_id,
                'product_id' => $row->product_id,
            ])
            ->toArray();

        shuffle($eligiblePairs);

        $targetCount = min(1500, count($eligiblePairs));
        $selectedPairs = array_slice($eligiblePairs, 0, $targetCount);

        $rows = [];
        $batchSize = 500;

        foreach ($selectedPairs as $pair) {
            $rating = $this->generateRating();
            $review = $reviewTemplates[$rating][array_rand($reviewTemplates[$rating])];
            $timestamp = $this->randomTimestamp();

            $rows[] = [
                'user_id' => $pair['user_id'],
                'product_id' => $pair['product_id'],
                'rating' => $rating,
                'images' => null,
                'review' => $review,
            ];

            if (count($rows) >= $batchSize) {
                DB::table('user_reviews')->insert($rows);
                $rows = [];
            }
        }

        if (!empty($rows)) {
            DB::table('user_reviews')->insert($rows);
        }
    }

    private function generateRating(): int
    {
        $rand = rand(1, 100);

        return match (true) {
            $rand <= 30 => 5,
            $rand <= 65 => 4,
            $rand <= 85 => 3,
            $rand <= 95 => 2,
            default => 1,
        };
    }

    private function randomTimestamp(): string
    {
        return now()
            ->subDays(rand(0, 90))
            ->subHours(rand(0, 23))
            ->subMinutes(rand(0, 59))
            ->subSeconds(rand(0, 59))
            ->format('Y-m-d H:i:s');
    }
}
