<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompanyShareholder extends Model
{
    use HasFactory;
    protected $fillable = [
        'company_id',
        'type',
        'name',
        'identification',
        'nationality',
        'shares',
        'ownership',
        'share_class'
    ];
}
