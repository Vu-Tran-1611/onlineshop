<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class UserProductInteractionSeeder extends Seeder
{
    public function run(): void
    {
        $userIds = range(485, 984); // 500 synthetic users

        $productPools = [
            'apple_ecosystem' => [
                44, 186, 187, 188, 189, 190, 191, 192, 194,
                47, 201, 202, 203, 205,
                49, 56,
                211, 212, 213, 214, 215,
                256, 257, 258, 259,
            ],

            'samsung_ecosystem' => [
                22, 43,
                195, 196, 197, 198, 199, 200,
                9, 46,
                206, 207, 208, 209, 210,
                50,
                57, 64, 73,
                216, 217, 218, 219, 220,
            ],

            'xiaomi_ecosystem' => [
                42, 45,
                178, 179, 180, 181, 182, 183, 184, 185,
                48,
                51, 54,
                10, 58, 63, 72,
                221, 223, 224, 225,
            ],

            'smartwatch_buyers' => [
                10, 58, 63, 72,
                221, 223, 224, 225,
                57, 64, 73,
                216, 217, 218, 219, 220,
                211, 212, 213, 214, 215,
            ],

            'disk_group' => [
                61, 62, 66, 67, 68, 69, 71, 74, 75, 76,
                241, 242, 243, 244,
                248, 249, 250, 252, 253, 254, 255,
            ],

            'gaming_console' => [
                11, 59, 60, 65,
            ],

            'computer_laptop_setup' => [
                12, 13,
                78, 79, 80, 81, 82, 83, 84, 85, 93, 94,
                77, 86, 87, 88,
                89, 90, 91, 92,
                256, 257, 258, 259,
                262, 263, 264, 265, 266, 267,
            ],

            'non_digital_watches' => [
                126, 127, 128, 129, 130, 131, 132, 133, 134, 135, 136, 137, 138,
                139, 140, 141, 142, 144, 145, 146, 147, 148,
                149, 150, 152, 153, 154, 155, 156,
                268,
            ],

            'fashion' => [
                6, 7,
                17, 18, 19, 20, 21,
                24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38,
                39, 40, 41,
                176, 177,
            ],

            'beauty_skincare' => [
                95, 96, 97, 98, 99, 100, 101, 102, 103,
                104, 105,
                106, 107,
                108, 109, 110, 111,
                112, 113,
                114, 115, 116, 117,
                118,
                119,
                120, 121, 122,
                123, 124, 125,
            ],

            'toys_collectors' => [
                278, 279, 280,
                281, 282,
                283, 284, 285, 286,
                287,
                290, 291, 292, 293,
            ],

            'grocery_daily' => [
                5,
                157, 158, 159, 160,
                161, 165,
                162, 166, 167, 168, 169, 170,
                163, 164, 171, 172,
                173, 175,
                174,
            ],
        ];

        $allProducts = array_values(array_unique(array_merge(...array_values($productPools))));

        $groupSizes = [
            'apple_ecosystem' => 70,
            'samsung_ecosystem' => 70,
            'xiaomi_ecosystem' => 60,
            'smartwatch_buyers' => 35,
            'disk_group' => 40,
            'gaming_console' => 30,
            'computer_laptop_setup' => 40,
            'non_digital_watches' => 35,
            'fashion' => 35,
            'beauty_skincare' => 40,
            'toys_collectors' => 25,
            'grocery_daily' => 20,
        ];

        $userGroups = $this->splitUsersIntoGroups($userIds, $groupSizes);

        $rows = [];
        $batchSize = 1000;

        foreach ($userGroups as $groupName => $users) {
            foreach ($users as $userId) {
                $rows = array_merge(
                    $rows,
                    $this->generateUserInteractions($userId, $groupName, $productPools, $allProducts)
                );

                if (count($rows) >= $batchSize) {
                    DB::table('user_product_interactions')->insert($rows);
                    $rows = [];
                }
            }
        }

        if (!empty($rows)) {
            DB::table('user_product_interactions')->insert($rows);
        }
    }

    private function splitUsersIntoGroups(array $userIds, array $groupSizes): array
    {
        $groups = [];
        $offset = 0;

        foreach ($groupSizes as $groupName => $size) {
            $groups[$groupName] = array_slice($userIds, $offset, $size);
            $offset += $size;
        }

        return $groups;
    }

    private function generateUserInteractions(
        int $userId,
        string $groupName,
        array $productPools,
        array $allProducts
    ): array {
        $now = now();

        $mainPool = $productPools[$groupName];
        $relatedPool = array_values(array_unique($this->getRelatedPool($groupName, $productPools)));
        $noisePool = $this->getNoisePool($mainPool, $relatedPool, $allProducts);

        $rows = [];

        // 20 interactions per user
        // main = 16, related = 3, noise = 1
        // click = 2, wishlist = 7, cart = 6, rating = 5

        // MAIN POOL
        $ratingProducts = $this->pickDistinctProducts($mainPool, 5);
        foreach ($ratingProducts as $productId) {
            $rows[] = $this->makeRow(
                $userId,
                $productId,
                $this->generateRatingInteraction()
            );
        }

        $cartBasePool = array_values(array_unique(array_merge($ratingProducts, $mainPool)));
        $cartProducts = $this->pickDistinctProducts($cartBasePool, 6);
        foreach ($cartProducts as $productId) {
            $rows[] = $this->makeRow($userId, $productId, 'cart_add');
        }

        $wishlistMainPool = array_values(array_unique(array_merge($ratingProducts, $cartProducts, $mainPool)));
        $wishlistMainProducts = $this->pickDistinctProducts($wishlistMainPool, 5);
        foreach ($wishlistMainProducts as $productId) {
            $rows[] = $this->makeRow($userId, $productId, 'wishlist_add');
        }

        // RELATED POOL
        // fallback to main pool if related pool is empty
        $effectiveRelatedPool = !empty($relatedPool) ? $relatedPool : $mainPool;

        $relatedWishlistProducts = $this->pickDistinctProducts($effectiveRelatedPool, 2);
        foreach ($relatedWishlistProducts as $productId) {
            $rows[] = $this->makeRow($userId, $productId, 'wishlist_add');
        }

        $relatedClickProduct = $this->pickDistinctProducts($effectiveRelatedPool, 1);
        foreach ($relatedClickProduct as $productId) {
            $rows[] = $this->makeRow($userId, $productId, 'click');
        }

        // NOISE POOL
        $noiseClickProduct = $this->pickDistinctProducts($noisePool, 1);
        foreach ($noiseClickProduct as $productId) {
            $rows[] = $this->makeRow($userId, $productId, 'click');
        }

        shuffle($rows);

        return $rows;
    }

    private function getRelatedPool(string $groupName, array $productPools): array
    {
        return match ($groupName) {
            'apple_ecosystem' => array_merge(
                $productPools['smartwatch_buyers'],
                $productPools['computer_laptop_setup']
            ),

            'samsung_ecosystem' => $productPools['smartwatch_buyers'],

            'xiaomi_ecosystem' => $productPools['smartwatch_buyers'],

            'smartwatch_buyers' => array_merge(
                $productPools['apple_ecosystem'],
                $productPools['samsung_ecosystem'],
                $productPools['xiaomi_ecosystem']
            ),

            'disk_group' => $productPools['gaming_console'],

            'gaming_console' => array_merge(
                $productPools['disk_group'],
                $productPools['computer_laptop_setup']
            ),

            'computer_laptop_setup' => array_merge(
                $productPools['gaming_console'],
                $productPools['apple_ecosystem']
            ),

            'non_digital_watches' => $productPools['fashion'],

            'fashion' => $productPools['non_digital_watches'],

            'beauty_skincare' => $productPools['fashion'],

            'toys_collectors' => [],
            'grocery_daily' => [],

            default => [],
        };
    }

    private function getNoisePool(array $mainPool, array $relatedPool, array $allProducts): array
    {
        $exclude = array_flip(array_unique(array_merge($mainPool, $relatedPool)));

        $noisePool = array_values(array_filter(
            $allProducts,
            fn ($productId) => !isset($exclude[$productId])
        ));

        return !empty($noisePool) ? $noisePool : $allProducts;
    }

    private function pickDistinctProducts(array $pool, int $count): array
    {
        $pool = array_values(array_unique($pool));

        if (empty($pool)) {
            return [];
        }

        shuffle($pool);

        if (count($pool) >= $count) {
            return array_slice($pool, 0, $count);
        }

        $result = $pool;

        while (count($result) < $count) {
            $result[] = $pool[array_rand($pool)];
        }

        return $result;
    }

    private function generateRatingInteraction(): string
    {
        return rand(1, 100) <= 70 ? 'R5' : 'R4';
    }

    private function makeRow(int $userId, int $productId, string $interactionType): array
    {
        return [
            'user_id' => $userId,
            'product_id' => $productId,
            'interaction_type' => $interactionType,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
