<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

class RestructureCatalog extends Command
{
    protected $signature = 'catalog:restructure {--dry-run : Show the changes without saving them}';

    protected $description = 'Rename and regroup categories and subcategories, and set product gender';

    // Category renames: old => new
    private const CATEGORY_RENAMES = [
        'Mobile Phones' => 'Phones & Tablets',
        'Computers & Laptops' => 'Computers & Accessories',
        'Electronics' => 'Gaming',
    ];

    // Gender for products by their current subcategory
    private const GENDER_BY_SUBCATEGORY = [
        'Men Watch' => 'men',
        'Women Watch' => 'women',
        'Kid Watch' => 'kids',
        'Smart Watch' => 'unisex',
    ];

    // Products whose gender differs from their category default
    private const GENDER_BY_PRODUCT = [
        'Nike Sportswear Phoenix Fleece Cropped Hoodie' => 'women',
    ];

    // Categories whose untagged products become unisex
    private const UNISEX_CATEGORIES = ['Fashion'];

    // Subcategory moves: subcategory => new category
    private const SUBCATEGORY_MOVES = [
        'Smart Watch' => 'Watches',
    ];

    // Merges: target name => [subcategories merged into it]; the first one is renamed
    private const SUBCATEGORY_MERGES = [
        'Classic Watches' => ['Men Watch', 'Women Watch', 'Kid Watch'],
        'Phone Accessories' => ['Phone Case', 'Battery'],
    ];

    // Subcategory renames: old => new
    private const SUBCATEGORY_RENAMES = [
        'Phone' => 'Phones',
        'Tablet' => 'Tablets',
        'Laptop' => 'Laptops',
        'Screen' => 'Monitors',
        'Keyboard' => 'Keyboards',
        'Mouse' => 'Mice',
        'Speaker' => 'Speakers',
        'Gaming console' => 'Consoles',
        'Game Disc' => 'Video Games',
        'Smart Watch' => 'Smart Watches',
        'Hoodie & Sweater' => 'Hoodies & Sweaters',
        'Jean' => 'Jeans',
        'Shirt' => 'Shirts',
        'T-shirt' => 'T-Shirts',
        'Trouser' => 'Pants & Shorts',
        'Vest & Blazer' => 'Jackets',
        'Model Figures' => 'Figures',
        'Vali' => 'Luggage',
    ];

    // Empty subcategories to remove
    private const SUBCATEGORY_DELETES = ['Sim', 'Supplements'];

    // Categories whose brand links follow their products
    private const BRAND_LINK_CATEGORIES = ['Watches', 'Gaming'];

    private bool $changed = false;

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $this->info($dryRun ? 'DRY RUN: nothing will be saved' : 'Applying catalog changes');

        DB::beginTransaction();
        try {
            $this->renameCategories();
            $this->setGender();
            $this->moveSubcategories();
            $this->mergeSubcategories();
            $this->renameSubcategories();
            $this->deleteSubcategories();
            $this->syncBrandLinks();
            $this->reportUnknown();

            if ($dryRun) {
                DB::rollBack();
                $this->warn('Dry run finished: all changes rolled back.');
            } else {
                DB::commit();
                $this->info($this->changed ? 'Done. Run catalog:verify next.' : 'Nothing to change: already up to date.');
            }
        } catch (Throwable $e) {
            DB::rollBack();
            $this->error('Failed, nothing was saved: ' . $e->getMessage());

            return self::FAILURE;
        }

        return self::SUCCESS;
    }

    // Rename categories and their slugs
    private function renameCategories(): void
    {
        $this->line(PHP_EOL . '<comment>Categories</comment>');
        foreach (self::CATEGORY_RENAMES as $old => $new) {
            $this->renameRow('categories', $old, $new);
        }
    }

    // Set gender where it is still empty
    private function setGender(): void
    {
        $this->line(PHP_EOL . '<comment>Gender</comment>');

        foreach (self::GENDER_BY_PRODUCT as $name => $gender) {
            $count = DB::table('products')->where('name', $name)->whereNull('gender')->update(['gender' => $gender]);
            $this->change($count, "\"{$name}\" -> {$gender}");
        }

        foreach (self::GENDER_BY_SUBCATEGORY as $subcategory => $gender) {
            $ids = DB::table('sub_categories')->where('name', $subcategory)->pluck('id');
            $count = DB::table('products')->whereIn('sub_category_id', $ids)->whereNull('gender')->update(['gender' => $gender]);
            $this->change($count, "{$count} products in \"{$subcategory}\" -> {$gender}");
        }

        foreach (self::UNISEX_CATEGORIES as $category) {
            $ids = DB::table('categories')->where('name', $category)->pluck('id');
            $count = DB::table('products')->whereIn('category_id', $ids)->whereNull('gender')->update(['gender' => 'unisex']);
            $this->change($count, "{$count} products in \"{$category}\" -> unisex");
        }
    }

    // Move subcategories and their products to another category
    private function moveSubcategories(): void
    {
        $this->line(PHP_EOL . '<comment>Moves</comment>');
        foreach (self::SUBCATEGORY_MOVES as $subcategory => $category) {
            $target = DB::table('categories')->where('name', $category)->first();
            $sub = DB::table('sub_categories')->where('name', $subcategory)->first();
            if (!$target || !$sub) {
                $this->line("  skip: \"{$subcategory}\" or \"{$category}\" not found");
                continue;
            }
            if ((int) $sub->category_id === (int) $target->id) {
                $this->line("  already done: \"{$subcategory}\" is in \"{$category}\"");
                continue;
            }

            DB::table('sub_categories')->where('id', $sub->id)->update(['category_id' => $target->id]);
            $count = DB::table('products')->where('sub_category_id', $sub->id)->update(['category_id' => $target->id]);
            $this->change(1, "\"{$subcategory}\" -> \"{$category}\" with {$count} products");
        }
    }

    // Merge subcategories into one, keeping the first one's ID
    private function mergeSubcategories(): void
    {
        $this->line(PHP_EOL . '<comment>Merges</comment>');
        foreach (self::SUBCATEGORY_MERGES as $targetName => $sources) {
            $target = DB::table('sub_categories')->where('name', $targetName)->first();
            if (!$target) {
                $first = DB::table('sub_categories')->where('name', $sources[0])->first();
                if (!$first) {
                    $this->line("  skip: \"{$targetName}\" and \"{$sources[0]}\" not found");
                    continue;
                }
                DB::table('sub_categories')->where('id', $first->id)
                    ->update(['name' => $targetName, 'slug' => Str::slug($targetName)]);
                $target = DB::table('sub_categories')->where('id', $first->id)->first();
                $this->change(1, "\"{$sources[0]}\" renamed to \"{$targetName}\"");
            }

            foreach (array_slice($sources, 1) as $sourceName) {
                $source = DB::table('sub_categories')->where('name', $sourceName)->first();
                if (!$source) {
                    $this->line("  already done: \"{$sourceName}\" merged");
                    continue;
                }
                $count = DB::table('products')->where('sub_category_id', $source->id)
                    ->update(['sub_category_id' => $target->id, 'category_id' => $target->category_id]);
                DB::table('sub_categories')->where('id', $source->id)->delete();
                $this->change(1, "\"{$sourceName}\" merged into \"{$targetName}\" ({$count} products)");
            }
        }
    }

    // Rename subcategories and their slugs
    private function renameSubcategories(): void
    {
        $this->line(PHP_EOL . '<comment>Subcategory renames</comment>');
        foreach (self::SUBCATEGORY_RENAMES as $old => $new) {
            $this->renameRow('sub_categories', $old, $new);
        }
    }

    // Delete subcategories only when they have no products
    private function deleteSubcategories(): void
    {
        $this->line(PHP_EOL . '<comment>Deletes</comment>');
        foreach (self::SUBCATEGORY_DELETES as $name) {
            $sub = DB::table('sub_categories')->where('name', $name)->first();
            if (!$sub) {
                $this->line("  already done: \"{$name}\" removed");
                continue;
            }
            $count = DB::table('products')->where('sub_category_id', $sub->id)->count();
            if ($count > 0) {
                $this->warn("  kept: \"{$name}\" still has {$count} products");
                continue;
            }
            DB::table('sub_categories')->where('id', $sub->id)->delete();
            $this->change(1, "\"{$name}\" removed");
        }
    }

    // Link brands to a category only when they have products in it
    private function syncBrandLinks(): void
    {
        $this->line(PHP_EOL . '<comment>Brand links</comment>');
        foreach (self::BRAND_LINK_CATEGORIES as $name) {
            $category = DB::table('categories')->where('name', $name)->first();
            if (!$category) {
                $this->line("  skip: \"{$name}\" not found");
                continue;
            }

            $brandIds = DB::table('products')->where('category_id', $category->id)
                ->whereNotNull('brand_id')->distinct()->pluck('brand_id')->all();
            $linked = DB::table('brand_category')->where('category_id', $category->id)->pluck('brand_id')->all();

            $add = array_diff($brandIds, $linked);
            $remove = array_diff($linked, $brandIds);

            foreach ($add as $brandId) {
                DB::table('brand_category')->insert(['brand_id' => $brandId, 'category_id' => $category->id]);
            }
            DB::table('brand_category')->where('category_id', $category->id)->whereIn('brand_id', $remove)->delete();

            $this->change(count($add) + count($remove), "\"{$name}\": +" . count($add) . ' / -' . count($remove) . ' brand links');
        }
    }

    // List names this command does not know, without changing them
    private function reportUnknown(): void
    {
        $known = array_merge(
            array_values(self::CATEGORY_RENAMES),
            ['Fashion', 'Grocery & Food', 'Health & Beauty', 'Toys & Collectibles', 'Watches'],
        );
        $unknown = DB::table('categories')->where('status', 1)->whereNotIn('name', $known)->pluck('name')->all();
        if ($unknown) {
            $this->warn(PHP_EOL . 'Active categories not covered by this command (left unchanged): ' . implode(', ', $unknown));
        }
    }

    // Rename one row by name, updating its slug
    private function renameRow(string $table, string $old, string $new): void
    {
        $count = DB::table($table)->where('name', $old)->update(['name' => $new, 'slug' => Str::slug($new)]);
        if ($count === 0) {
            $exists = DB::table($table)->where('name', $new)->exists();
            $this->line($exists ? "  already done: \"{$new}\"" : "  skip: \"{$old}\" not found");
            return;
        }
        $this->change($count, "\"{$old}\" -> \"{$new}\"");
    }

    // Print a change line and remember that something changed
    private function change(int $count, string $message): void
    {
        if ($count > 0) {
            $this->changed = true;
            $this->line("  <info>change</info>: {$message}");
        } else {
            $this->line("  no change: {$message}");
        }
    }
}
