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
                'Excellent product overall. The quality feels very good, it performs exactly as expected, and I am very satisfied with this purchase.',
                'I am very happy with this product. The design looks great, the quality is impressive, and it has been a very worthwhile purchase for me.',
                'This is one of the better products I have bought recently. It works very well, feels reliable, and I would definitely recommend it to others.',
                'The product exceeded my expectations in both quality and usability. Everything looks as described, and I am extremely satisfied with it.',
                'Very pleased with this item. The materials feel solid, the performance is excellent, and it offers really good value for the money.',
            ],
            4 => [
                'Good product overall. The quality is nice, it works well for my needs, and I am satisfied with this purchase.',
                'I like this product quite a lot. It performs well, the design is good, and it mostly met my expectations.',
                'This is a solid product with good quality and a reasonable price. There are small things that could be improved, but overall I am happy with it.',
                'The product works well and looks good in person. It may not be perfect, but it still offers very good value and a positive experience.',
                'Pretty satisfied with this item. The overall quality is good, it does what I expected, and I would consider buying similar products again.',
            ],
        ];

        // Delete old reviews from synthetic users before reseeding
        $syntheticUserIds = DB::table('users')
            ->where('name', 'like', 'synthetic%')
            ->pluck('id')
            ->toArray();


        // Only take actual rating interactions from user_product_interactions
        $ratingInteractions = DB::table('user_product_interactions')
            ->select('user_id', 'product_id', 'interaction_type', 'created_at')
            ->whereIn('interaction_type', ['R4', 'R5'])
            ->whereIn('user_id', $syntheticUserIds)
            ->get();

        $rows = [];
        $batchSize = 500;

        foreach ($ratingInteractions as $interaction) {
            $rating = match ($interaction->interaction_type) {
                'R4' => 4,
                'R5' => 5,
            };

            $review = $reviewTemplates[$rating][array_rand($reviewTemplates[$rating])];

            $rows[] = [
                'user_id' => $interaction->user_id,
                'product_id' => $interaction->product_id,
                'rating' => $rating,
                'images' => null,
                'review' => $review,
                'created_at' => $interaction->created_at ?? now(),
                'updated_at' => $interaction->created_at ?? now(),
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
}