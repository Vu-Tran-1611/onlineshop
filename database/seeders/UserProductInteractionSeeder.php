<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class UserProductInteractionSeeder extends Seeder
{
    public function run(): void
    {
        $userIds = range(23, 422); // 400 synthetic users

        $productPools = [
            'phones' => [
                9, 22,
                42, 43, 44, 45,
                46, 47, 48,
                49, 50, 51,
                54, 56,
                178, 179, 180, 181, 182, 183, 184, 185, 186, 187,
                188, 189, 190, 191, 192, 194, 195, 196, 197, 198,
                199, 200,
                201, 202, 203, 205, 206, 207, 208, 209, 210
            ],

            'electronics' => [
                10, 11,
                57, 58, 59, 60, 61, 62, 63, 64, 65, 66, 67, 68, 69,
                71, 72, 73, 74, 75, 76,
                211, 212, 213, 214, 215, 216, 217, 218, 219, 220, 221,
                223, 224, 225,
                241, 242, 243, 244,
                248, 249, 250,
                252, 253, 254, 255
            ],

            'computers' => [
                12, 13,
                77, 78, 79, 80, 81,
                82, 83, 84, 85,
                86, 87, 88,
                89, 90, 91, 92,
                93, 94,
                256, 257, 258, 259,
                262, 263, 264, 265, 266, 267
            ],

            'watches' => [
                126, 127, 128, 129, 130, 131, 132, 133, 134, 135, 136, 137,
                138, 139, 140, 141, 142,
                144, 145, 146, 147, 148,
                149, 150,
                152, 153, 154, 155, 156,
                268
            ],

            'health_beauty' => [
                95, 96, 97, 98, 99, 100, 101, 102, 103, 104, 105, 106,
                107, 108, 109, 110, 111, 112, 113,
                114, 115, 116, 117, 118, 119,
                120, 121, 122,
                123, 124, 125
            ],

            'fashion' => [
                6, 7,
                17, 18, 19, 20, 21,
                24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38,
                39, 40, 41,
                176, 177
            ],

            'grocery' => [
                157, 158, 159, 160, 161, 162, 163, 164, 165, 166,
                167, 168, 169, 170, 171, 172, 173, 174, 175
            ],

            'toys' => [
                278, 279, 280, 281, 282, 283, 284, 285, 286, 287,
                290, 291, 292, 293
            ],
        ];

        $allProducts = array_values(array_unique(array_merge(...array_values($productPools))));

        // 400 users split into 9 groups
        $groupSizes = [
            'phones' => 70,
            'electronics' => 65,
            'computers' => 55,
            'watches' => 55,
            'health_beauty' => 45,
            'fashion' => 45,
            'grocery' => 35,
            'toys' => 20,
            'mixed' => 10,
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

        // target per user ≈ 37-38 interactions
        $clickCount = rand(20, 27);
        $wishlistCount = rand(7, 10);
        $cartCount = rand(4, 6);

        $clickedProducts = [];
        $wishlistedProducts = [];
        $cartedProducts = [];

        // Pick weighted product sets
        $clickCandidates = $this->pickWeightedProducts($groupName, $productPools, $allProducts, $clickCount);
        $wishlistCandidates = $this->pickWeightedProducts($groupName, $productPools, $allProducts, $wishlistCount + 4);
        $cartCandidates = $this->pickWeightedProducts($groupName, $productPools, $allProducts, $cartCount + 4);

        $rows = [];

        // CLICK
        foreach ($clickCandidates as $productId) {
            if (count($clickedProducts) >= $clickCount) {
                break;
            }

            if (in_array($productId, $clickedProducts, true)) {
                continue;
            }

            $clickedProducts[] = $productId;

            $rows[] = $this->makeRow($userId, $productId, 'click', $this->randomTimestamp($now));
        }

        // WISHLIST: mostly from clicked products, sometimes extra related products
        $wishlistPool = array_values(array_unique(array_merge(
            $clickedProducts,
            $wishlistCandidates
        )));

        shuffle($wishlistPool);

        foreach ($wishlistPool as $productId) {
            if (count($wishlistedProducts) >= $wishlistCount) {
                break;
            }

            if (in_array($productId, $wishlistedProducts, true)) {
                continue;
            }

            $wishlistedProducts[] = $productId;

            // ensure click exists before wishlist in most cases
            if (!in_array($productId, $clickedProducts, true)) {
                $rows[] = $this->makeRow($userId, $productId, 'click', $this->randomTimestamp($now));
                $clickedProducts[] = $productId;
            }

            $rows[] = $this->makeRow($userId, $productId, 'wishlist_add', $this->randomTimestamp($now));
        }

        // CART: mostly from wishlist/click
        $cartPool = array_values(array_unique(array_merge(
            $wishlistedProducts,
            $clickedProducts,
            $cartCandidates
        )));

        shuffle($cartPool);

        foreach ($cartPool as $productId) {
            if (count($cartedProducts) >= $cartCount) {
                break;
            }

            if (in_array($productId, $cartedProducts, true)) {
                continue;
            }

            $cartedProducts[] = $productId;

            if (!in_array($productId, $clickedProducts, true)) {
                $rows[] = $this->makeRow($userId, $productId, 'click', $this->randomTimestamp($now));
                $clickedProducts[] = $productId;
            }

            $rows[] = $this->makeRow($userId, $productId, 'cart_add', $this->randomTimestamp($now));
        }

        return $rows;
    }

    private function pickWeightedProducts(
        string $groupName,
        array $productPools,
        array $allProducts,
        int $count
    ): array {
        $result = [];

        for ($i = 0; $i < $count * 3; $i++) {
            $productId = $this->pickOneWeightedProduct($groupName, $productPools, $allProducts);

            if (!in_array($productId, $result, true)) {
                $result[] = $productId;
            }

            if (count($result) >= $count) {
                break;
            }
        }

        return $result;
    }

    private function pickOneWeightedProduct(
        string $groupName,
        array $productPools,
        array $allProducts
    ): int {
        $rand = rand(1, 100);

        if ($groupName === 'mixed') {
            return $allProducts[array_rand($allProducts)];
        }

        // Main / related / random distribution
        // mostly 70 / 20 / 10
        // grocery & toys slightly stronger focus
        if (in_array($groupName, ['grocery', 'toys'], true)) {
            if ($rand <= 80) {
                return $this->randomFromPool($productPools[$groupName]);
            }
            if ($rand <= 90) {
                $relatedPool = $this->getRelatedPool($groupName, $productPools);
                return $this->randomFromPool($relatedPool);
            }
            return $allProducts[array_rand($allProducts)];
        }

        if ($rand <= 70) {
            return $this->randomFromPool($productPools[$groupName]);
        }

        if ($rand <= 90) {
            $relatedPool = $this->getRelatedPool($groupName, $productPools);
            return $this->randomFromPool($relatedPool);
        }

        return $allProducts[array_rand($allProducts)];
    }

    private function getRelatedPool(string $groupName, array $productPools): array
    {
        return match ($groupName) {
            'phones' => $productPools['electronics'],
            'electronics' => array_merge($productPools['phones'], $productPools['computers']),
            'computers' => $productPools['electronics'],
            'watches' => array_merge($productPools['fashion'], $productPools['health_beauty']),
            'health_beauty' => $productPools['fashion'],
            'fashion' => array_merge($productPools['watches'], $productPools['health_beauty']),
            'grocery' => $productPools['health_beauty'],
            'toys' => $productPools['electronics'],
            default => $productPools['phones'],
        };
    }

    private function randomFromPool(array $pool): int
    {
        return $pool[array_rand($pool)];
    }

    private function makeRow(int $userId, int $productId, string $interactionType, Carbon $timestamp): array
    {
        return [
            'user_id' => $userId,
            'product_id' => $productId,
            'interaction_type' => $interactionType,
        ];
    }


    private function randomTimestamp(Carbon $now): Carbon
    {
        return $now->copy()->subDays(rand(0, 90))->subMinutes(rand(0, 1440));
    }
};
