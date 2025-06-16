<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    public $fillable = [
        "icon",
        "image",
        "banner",
        "name",
        "status",
        "slug",
    ];
    public function subCategories()
    {
        return $this->hasMany(SubCategory::class);
    }

    // Products 
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
