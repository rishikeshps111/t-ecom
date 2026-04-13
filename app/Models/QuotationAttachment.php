<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuotationAttachment extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'quotation_id',
        'file',
        'alt',
    ];

    /* ================= Relations ================= */

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }
}
