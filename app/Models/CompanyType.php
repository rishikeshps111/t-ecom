<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyType extends Model
{
    protected $fillable = [
        'name',
        'is_active',
    ];

    public function companies()
    {
        return $this->hasMany(Company::class);
    }
}
