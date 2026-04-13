<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserTotalGroup extends Model
{
    use HasFactory;

    protected $table = 'user_total_groups';

    protected $fillable = [
        'user_id',
        'total_group_id',
    ];

    // Relation to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relation to Customer (or Group)
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'total_group_id');
    }
}
