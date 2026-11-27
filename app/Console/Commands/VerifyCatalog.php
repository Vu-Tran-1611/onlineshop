<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class VerifyCatalog extends Command
{
    protected $signature = 'catalog:verify {--after : Also check the restructured names and gender}';

    protected $description = 'Check that products, categories and subcategories are consistent';

    // Active structure expected after catalog:restructure
    private const EXPECTED = [
        'Phones & Tablets' => ['Phones', 'Tablets', 'Phone Accessories'],
        'Computers & Accessories' => ['Laptops', 'Monitors', 'Keyboards', 'Mice', 'Speakers'],
        'Gaming' => ['Consoles', 'Video Games'],
        'Watches' => ['Smart Watches', 'Classic Watches'],
        'Fashion' => ['Hoodies & Sweaters', 'Jeans', 'Shirts', 'T-Shirts', 'Pants & Shorts', 'Jackets'],
        'Health & Beauty' => ['Makeup', 'Skincare', 'Hair Care', 'Personal Care'],
        'Grocery & Food' => ['Snacks', 'Beverages', 'Instant Food'],
        'Toys & Collectibles' => ['Plush Toys', 'Figures'],
    ];

    private array $failures = [];

    public function handle(): int
    {
        $this->checkConsistency();
        if ($this->option('after')) {
            $this->checkStructure();
            $this->checkGender();
        }
        $this->printSummary();

        if ($this->failures) {
            $this->error(PHP_EOL . count($this->failures) . ' problem(s) found:');
            foreach ($this->failures as $failure) {
                $this->line("  - {$failure}");
            }
            return self::FAILURE;
        }

        $this->info(PHP_EOL . 'All checks passed.');
        return self::SUCCESS;
    }

    // Check product links to categories and subcategories
    private function checkConsistency(): void
    {
        $missingCategory = DB::table('products')->leftJoin('categories', 'categories.id', '=', 'products.category_id')
            ->whereNull('categories.id')->count();
        $this->expectZero($missingCategory, 'products whose category does not exist');

        $missingSub = DB::table('products')->whereNotNull('products.sub_category_id')
            ->leftJoin('sub_categories', 'sub_categories.id', '=', 'products.sub_category_id')
            ->whereNull('sub_categories.id')->count();
        $this->expectZero($missingSub, 'products whose subcategory does not exist');

        $mismatch = DB::table('products')->join('sub_categories', 'sub_categories.id', '=', 'products.sub_category_id')
            ->whereColumn('sub_categories.category_id', '!=', 'products.category_id')->count();
        $this->expectZero($mismatch, "products whose category differs from their subcategory's category");

        $orphanSubs = DB::table('sub_categories')->leftJoin('categories', 'categories.id', '=', 'sub_categories.category_id')
            ->whereNull('categories.id')->count();
        $this->expectZero($orphanSubs, 'subcategories without a category');

        $orphanLinks = DB::table('brand_category')->leftJoin('categories', 'categories.id', '=', 'brand_category.category_id')
            ->whereNull('categories.id')->count();
        $this->expectZero($orphanLinks, 'brand links to missing categories');
    }

    // Check the expected active categories and subcategories exist
    private function checkStructure(): void
    {
        foreach (self::EXPECTED as $category => $subcategories) {
            $row = DB::table('categories')->where('name', $category)->first();
            if (!$row) {
                $this->failures[] = "missing category \"{$category}\"";
                continue;
            }
            $actual = DB::table('sub_categories')->where('category_id', $row->id)->where('status', 1)->pluck('name')->all();
            $missing = array_diff($subcategories, $actual);
            $extra = array_diff($actual, $subcategories);
            if ($missing) {
                $this->failures[] = "\"{$category}\" is missing: " . implode(', ', $missing);
            }
            if ($extra) {
                $this->failures[] = "\"{$category}\" has unexpected: " . implode(', ', $extra);
            }
        }
    }

    // Check gender values and that every watch has one
    private function checkGender(): void
    {
        $invalid = DB::table('products')->whereNotNull('gender')->whereNotIn('gender', Product::GENDERS)->count();
        $this->expectZero($invalid, 'products with an invalid gender');

        $watches = DB::table('categories')->where('name', 'Watches')->value('id');
        $untagged = DB::table('products')->where('category_id', $watches)->whereNull('gender')->count();
        $this->expectZero($untagged, 'watches without a gender');
    }

    // Print product counts per active category and subcategory
    private function printSummary(): void
    {
        $this->line(PHP_EOL . '<comment>Active catalog (active, approved products)</comment>');
        $rows = DB::table('categories')->where('status', 1)->orderBy('name')->get();
        foreach ($rows as $category) {
            $count = DB::table('products')->where('category_id', $category->id)->where('status', 1)->where('is_approved', 1)->count();
            $this->line("  {$category->name} ({$count})");
            $subs = DB::table('sub_categories')->where('category_id', $category->id)->where('status', 1)->orderBy('name')->get();
            foreach ($subs as $sub) {
                $subCount = DB::table('products')->where('sub_category_id', $sub->id)->where('status', 1)->where('is_approved', 1)->count();
                $this->line("    - {$sub->name} ({$subCount})");
            }
        }

        // Skip gender before the column is added
        if (!Schema::hasColumn('products', 'gender')) {
            $this->line(PHP_EOL . '<comment>Gender</comment>: column not added yet');
            return;
        }
        $genders = DB::table('products')->select('gender', DB::raw('count(*) as total'))->groupBy('gender')->pluck('total', 'gender');
        $this->line(PHP_EOL . '<comment>Gender</comment>: ' . collect($genders)->map(fn ($n, $g) => ($g ?: 'null') . "={$n}")->implode(', '));
    }

    // Record a failure when a count is not zero
    private function expectZero(int $count, string $label): void
    {
        if ($count > 0) {
            $this->failures[] = "{$count} {$label}";
        }
    }
}
