<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KnowledgeBaseDocument extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'slug',
        'document_type',
        'content',
        'source_url',
        'version',
    ];
}
