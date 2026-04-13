<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'state_id',
        'status'
    ];

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function customers()
    {
        return $this->hasMany(Customer::class, 'city_id');
    }
}
