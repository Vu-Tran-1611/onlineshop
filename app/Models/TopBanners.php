<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopBanners extends Model
{
    use HasFactory;
    protected $fillable = [
        'label',
        'text',
        'link',
        'is_active',
        'start_date',
        'end_date',
    ];
}
