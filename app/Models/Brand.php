<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
        "slug",
        "logo",
        "status",
        "is_featured"
    ];
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }


    // Products
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
