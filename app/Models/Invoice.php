<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'created_by',
        'customer_id',
        'company_id',
        'quotation_id',
        'invoice_date',
        'due_date',
        'invoice_number',
        'payment_terms',
        'currency',
        'sub_total',
        'tax_total',
        'discount_total',
        'grant_total',
        'paid_amount',
        'balance_amount',
        'status',
        'payment_status',
        'company_type_id',
        'business_user_id',
        'planner_user_id',
        'currency_id',
        'remarks',
        'invoice_address',
        'delivery_address',
        'total_group_id',
        'terms',
        'planner_commission',
        'p_bill_percentage'
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date'  => 'date',
    ];


    public function getTotalPaidAttribute(): float
    {
        return (float) $this->payments()->sum('amount');
    }

    public function getTotalCreditNoteAmountAttribute(): float
    {
        return (float) $this->creditNotes()->sum('amount');
    }

    public function getFinalBalanceAttribute(): float
    {
        return max(
            $this->grant_total
                - $this->total_paid
                - $this->total_credit_note_amount,
            0
        );
    }

    public function getComputedPaymentStatusAttribute(): string
    {
        if ($this->final_balance == 0) {
            return 'paid';
        }

        if ($this->total_paid > 0 || $this->total_credit_note_amount > 0) {
            return 'partial';
        }

        return 'unpaid';
    }

    public function refreshPaymentStatus(): void
    {
        $this->update([
            'payment_status' => $this->computed_payment_status,
        ]);
    }
    protected static function booted()
    {
        static::addGlobalScope('assignedCompanyQuotations', function (Builder $builder) {
            if (Auth::check() && Auth::user()->hasRole('Customer')) {
                $builder->whereHas('quotation.workPlan.company.users', function ($q) {
                    $q->where('users.id', Auth::id());
                });
            }

            if (Auth::check() && Auth::user()->hasRole('Planner')) {
                $builder->whereRelation('quotation.workPlan.company', 'planner_id', Auth::id());
            }

            if (Auth::check() && Auth::user()->hasRole('Production Staff')) {
                $builder->whereRelation('quotation.workPlan.company', 'production_staff_id', Auth::id());
            }
        });
    }
    public static function generateInvoiceNumber(int $invoiceId): string
    {
        $year = now()->year;

        return sprintf(
            'INV/%s/%04d',
            $year,
            $invoiceId
        );
    }

    protected function customInvoiceId(): Attribute
    {
        return Attribute::get(function () {
            $prefix = get_prefix('invoice') ?? 'CUS';
            $year   = active_financial_year_start();;
            $number = str_pad($this->id, 4, '0', STR_PAD_LEFT);
            return $prefix . $year . '#' . $number;
        });
    }

    // public static function generateCode(): string
    // {
    //     $prefix = get_prefix('invoice') ?? 'INV';
    //     $year   = active_financial_year_start();
    //     $lastCompany = self::withoutGlobalScopes()->latest('id')->first();
    //     $nextId = $lastCompany ? $lastCompany->id + 1 : 1;
    //     $number = str_pad($nextId, 4, '0', STR_PAD_LEFT);

    //     return $prefix  . $year . '#' . $number;
    // }

    public static function generateCode(string $typeCode): string
    {
        $prefix = get_prefix('invoice') ?? 'INV';
        $year   = active_financial_year_start();

        $last = self::withoutGlobalScopes()
            ->where('invoice_number', 'like', $prefix . '/' . $typeCode . '/' . $year . '-%')
            ->latest('id')
            ->first();

        $nextId = $last
            ? (int) last(explode('-', $last->invoice_number)) + 1
            : 1;

        $number = str_pad($nextId, 5, '0', STR_PAD_LEFT);

        return "{$prefix}/{$typeCode}/{$year}-{$number}";
    }


    public function scopeSearchByCustomId($query, $value)
    {
        if (preg_match('/(\d+)$/', $value, $matches)) {
            $query->where('id', (int) $matches[1]);
        }
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::class, 'quotation_id');
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function approvals()
    {
        return $this->hasMany(InvoiceApproval::class);
    }

    public function currentApproval()
    {
        return $this->hasOne(InvoiceApproval::class)
            ->where('level', $this->current_level);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function creditNotes()
    {
        return $this->hasMany(CreditNote::class);
    }


    public function companyType()
    {
        return $this->belongsTo(CompanyType::class, 'company_type_id');
    }

    public function businessUser()
    {
        return $this->belongsTo(User::class, 'business_user_id');
    }

    public function plannerUser()
    {
        return $this->belongsTo(User::class, 'planner_user_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function totalGroup()
    {
        return $this->belongsTo(Customer::class, 'total_group_id');
    }

    public function plannerPayouts()
    {
        return $this->hasMany(PlannerPayout::class);
    }

    public function productionStaffPayouts()
    {
        return $this->hasMany(ProductionStaffPayout::class);
    }
}
