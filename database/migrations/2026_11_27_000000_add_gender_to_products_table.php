<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Add who the product is for: men, women, kids or unisex
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('gender', 10)->nullable()->after('brand_id')->index();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['gender']);
            $table->dropColumn('gender');
        });
    }
};
