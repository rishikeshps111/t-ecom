<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuotationItem extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'quotation_id',
        'item_id',
        'quantity',
        'tax_percentage',
        'unit_price',
        'tax_amount',
        'discount_amount',
        'total_amount',
        'description',
        'umo',
        'sum_amount',
        'is_selected',
        'planner_iv',
        'production_iv'
    ];

    /* ================= Relations ================= */

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
