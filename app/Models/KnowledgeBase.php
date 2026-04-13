<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class KnowledgeBase extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'chat_category_id',
        'title',
        'content',
        'keywords',
        'status'
    ];

    protected $casts = [
        'keywords' => 'array',  // Cast JSON to array
    ];

    // Relationship to category
    public function category()
    {
        return $this->belongsTo(ChatCategory::class, 'chat_category_id');
    }
}
