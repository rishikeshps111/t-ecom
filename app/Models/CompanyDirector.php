<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompanyDirector extends Model
{
    use HasFactory;
    protected $fillable = [
        'company_id',
        'name',
        'identification_type',
        'identification_number',
        'nationality',
        'date_of_birth',
        'address',
        'email',
        'mobile',
        'position',
        'appointment_date'
    ];
}
