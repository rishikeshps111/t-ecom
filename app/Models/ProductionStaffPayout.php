<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionStaffPayout extends Model
{
    protected $fillable = [
        'invoice_id',
        'payment_id',
        'production_staff_id',
        'amount',
        'remarks',
        'payment_method',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function productionStaff()
    {
        return $this->belongsTo(User::class, 'production_staff_id');
    }
}
