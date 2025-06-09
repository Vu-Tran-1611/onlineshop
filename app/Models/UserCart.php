<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCart extends Model
{
    use HasFactory;
    public $fillable = [
        "user_id",
        "cart_data"
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
