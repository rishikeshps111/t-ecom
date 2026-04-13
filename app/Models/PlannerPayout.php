<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlannerPayout extends Model
{
    protected $fillable = [
        'invoice_id',
        'payment_id',
        'planner_id',
        'amount',
        'remarks',
        'payment_method',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /**
     * The invoice related to this payout
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * The payment related to this payout (nullable)
     */
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * The planner related to this payout (nullable)
     */
    public function planner()
    {
        return $this->belongsTo(User::class, 'planner_id');
    }
}
