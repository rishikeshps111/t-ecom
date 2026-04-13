<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'status'
    ];

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
}
