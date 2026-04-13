<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class AnnouncementUser extends Pivot
{
    protected $table = 'announcement_users';

    protected $fillable = [
        'announcement_id',
        'user_id',
        'read_at'
    ];
}
