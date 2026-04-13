<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotationApproval extends Model
{
    protected $fillable = [
        'quotation_id',
        'level',
        'status',
        'approved_by',
        'approved_at',
        'rejection_reason'
    ];

    protected $appends = ['role'];



    public const LEVEL_ROLES = [
        1 => 'Sales Executive',
        2 => 'Sales Manager',
        3 => 'Admin',
        4 => 'Finance',
    ];

    public function getRoleAttribute(): string
    {
        return self::LEVEL_ROLES[$this->level] ?? 'Unknown';
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
