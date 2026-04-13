<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompanyMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'subject',
        'message',
        'priority'
    ];

    /**
     * Message belongs to a user
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
