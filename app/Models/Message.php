<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_id',
        'subject',
        'priority'
    ];

    protected static function booted()
    {
        static::addGlobalScope('assignedMessages', function (Builder $builder) {
            if (Auth::check() && Auth::user()->hasRole('Customer')) {
                $builder->where('user_id', Auth::id());
            }

            if (Auth::check() && Auth::user()->hasRole('Corp User')) {
                $builder->where('user_id', Auth::id());
            }

            if (Auth::check() && Auth::user()->hasRole('Planner')) {
                $builder->where('user_id', Auth::id());
            }

            if (Auth::check() && Auth::user()->hasRole('Production Staff')) {
                $builder->where('user_id', Auth::id());
            }
        });
    }

    /**
     * Message belongs to a user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class);
    }
}
