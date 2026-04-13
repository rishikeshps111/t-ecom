<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class Announcement extends Model
{
    protected $fillable = [
        'subject',
        'message',
        'type',
        'priority',
        'schedule_date'
    ];

    protected $casts = [
        'schedule_date' => 'date'
    ];

    protected static function booted()
    {
        static::addGlobalScope('assignedCompanyQuotations', function (Builder $builder) {
            if (Auth::check() && !Auth::user()->hasRole('Super Admin')) {
                $userId = Auth::id();

                $builder->where('schedule_date', '<=', \Carbon\Carbon::today())
                    ->where(function ($q) use ($userId) {

                        // 🔹 Public announcements → everyone sees
                        $q->where('type', 'public')

                            // 🔹 OR Private announcements → only assigned users
                            ->orWhere(function ($sub) use ($userId) {
                                $sub->where('type', 'private')
                                    ->whereHas('users', function ($u) use ($userId) {
                                        $u->where('announcement_users.user_id', $userId);
                                    });
                            });
                    });
            }
        });
    }

    public function isUnreadForUser($userId)
    {
        if ($this->type === 'public') {
            return !$this->users->contains($userId);
        }

        if ($this->type === 'private') {
            return $this->users->contains(function ($u) use ($userId) {
                return $u->id === $userId && $u->pivot->read_at === null;
            });
        }

        return false;
    }
    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'announcement_users'
        )
            ->using(AnnouncementUser::class)
            ->withPivot('read_at')
            ->withTimestamps();
    }
}
