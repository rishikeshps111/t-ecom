<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'created_by',
        'amount',
        'payment_method',
        'remark',
        'status',
        'notes'
    ];

    public static function generateReceiptNumber(int $nextId, string $typeCode = 'ALL'): string
    {
        // $prefix = get_prefix('payment') ?? 'INV';
        $prefix = 'OR';
        $year   = active_financial_year_start();
        $number = str_pad($nextId, 5, '0', STR_PAD_LEFT);

        // Example: PAY/SEC/2026#0001
        return $prefix . '/' . $typeCode . '/' . $year . '-' . $number;
    }

    protected static function booted()
    {
        static::addGlobalScope('assignedCompanyQuotations', function (Builder $builder) {
            if (Auth::check() && Auth::user()->hasRole('Customer')) {
                $builder->whereHas('invoice.quotation.workPlan.company.users', function ($q) {
                    $q->where('users.id', Auth::id());
                });
            }

            if (Auth::check() && Auth::user()->hasRole('Planner')) {
                $builder->whereRelation('invoice.quotation.workPlan.company', 'planner_id', Auth::id());
            }

            if (Auth::check() && Auth::user()->hasRole('Production Staff')) {
                $builder->whereRelation('invoice.quotation.workPlan.company', 'production_staff_id', Auth::id());
            }
        });
    }

    protected function customPaymentId(): Attribute
    {
        return Attribute::get(function () {
            // $prefix = get_prefix('payment') ?? 'INV';
            $prefix = 'OR';
            $year   = active_financial_year_start();;
            $number = str_pad($this->id, 5, '0', STR_PAD_LEFT);
            $typeCode = $this->getTypeCode($this->invoice->quotation->workPlan->total_group_id);
            // Example: PAY/SEC/2026#0001
            return $prefix . '/' . $typeCode . '/' . $year . '-' . $number;
        });
    }

    private function getTypeCode($companyTypeId): string
    {
        return match ((int) $companyTypeId) {
            1 => 'TTS',   // Secretarial
            2 => 'TSS',   // Taxation
            3 => 'TCS',   // Audit
            4 => 'TIA',  // Loan
            default => 'ALL',
        };
    }

    public function scopeSearchByCustomId($query, $value)
    {
        if (preg_match('/(\d+)$/', $value, $matches)) {
            $query->where('id', (int) $matches[1]);
        }
    }

    /**
     * Relation: Payment belongs to an Invoice
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function plannerPayout()
    {
        return $this->hasOne(PlannerPayout::class);
    }

    public function productionStaffPayout()
    {
        return $this->hasOne(ProductionStaffPayout::class);
    }
}
