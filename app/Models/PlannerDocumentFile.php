<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PlannerDocumentFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'planner_document_id',
        'document',
        'type'
    ];

    /**
     * File belongs to a document
     */
    public function document()
    {
        return $this->belongsTo(PlannerDocument::class, 'planner_document_id');
    }
}
