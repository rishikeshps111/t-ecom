<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
class CreditNote extends Model
{
    protected $fillable = [
        'invoice_id',
        'credit_note_number',
        'type',
        'amount',
        'remark',
        'date',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    protected static function booted()
    {
        static::addGlobalScope('assignedCompanyQuotations', function (Builder $builder) {
            if (Auth::check() && Auth::user()->hasRole('Corp User')) {
                $builder->whereHas('invoice.quotation.workPlan.company.users', function ($q) {
                    $q->where('users.id', Auth::id());
                });
            }

            if (Auth::check() && Auth::user()->hasRole('Planner')) {
                $builder->whereRelation('invoice.quotation.workPlan.company', 'planner_id', Auth::id());
            }
        });
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
