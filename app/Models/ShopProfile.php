<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopProfile extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
        "slug",
        "banner",
        "phone",
        "email",
        "address",
        "description",
        "fb_link",
        "tw_link",
        "insta_link",
        "user_id"
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
