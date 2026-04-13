<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'message_id',
        'subject',
        'message',
        'send_by',
        'is_read'
    ];

    public function chat()
    {
        return $this->belongsTo(Message::class, 'message_id');
    }
}
