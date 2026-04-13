<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class Quotation extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'quotation_number',
        'user_id',
        'customer_id',
        'company_id',
        'contact_person',
        'quotation_date',
        'validity_date',
        'approval_date',
        'validity_in_days',
        'sub_total',
        'tax_total',
        'discount_total',
        'grant_total',
        'payment_terms',
        'notes',
        'terms',
        'status',
        'company_type_id',
        'business_user_id',
        'remarks',
        'planner_user_id',
        'invoice_address',
        'delivery_address',
        'currency_id',
        'total_group_id',
        'work_plan_id',
        'approved_by',
        'approved_at',
        'planner_commission',
        'p_bill_percentage'
    ];

    protected $casts = [
        'quotation_date' => 'date',
        'validity_date'  => 'date',
        'approval_date'  => 'date',
        'approved_at' => 'date',
    ];

    protected static function booted()
    {
        static::addGlobalScope('assignedCompanyQuotations', function (Builder $builder) {
            if (Auth::check() && Auth::user()->hasRole('Customer')) {
                $builder->whereHas('workPlan.company.users', function ($q) {
                    $q->where('users.id', Auth::id());
                });
            }
            if (Auth::check() && Auth::user()->hasRole('Planner')) {
                $builder->whereRelation('workPlan.company', 'planner_id', Auth::id());
            }
            if (Auth::check() && Auth::user()->hasRole('Production Staff')) {
                $builder->whereRelation('workPlan.company', 'production_staff_id', Auth::id());
            }
        });
    }
    // public static function generateQuotationNumber(int $quotationId): string
    // {

    //     return  Attribute::get(function () {
    //         $prefix = get_prefix('quotation') ?? 'CUS';
    //         $year   = active_financial_year_start();
    //         $number = str_pad($this->id, 4, '0', STR_PAD_LEFT);
    //         return $prefix . $year . '#' . $number;
    //     });
    // }

    protected function customQuotationId(): Attribute
    {
        return Attribute::get(function () {
            $prefix = get_prefix('quotation') ?? 'CUS';
            $year   = active_financial_year_start();
            $number = str_pad($this->id, 4, '0', STR_PAD_LEFT);
            return $prefix . $year . '#' . $number;
        });
    }

    // public static function generateCompanyCode(): string
    // {
    //     $prefix = get_prefix('quotation') ?? 'QT';
    //     $year   = active_financial_year_start();
    //     $lastCompany = self::withoutGlobalScopes()
    //         ->latest('id')
    //         ->first();
    //     $nextId = $lastCompany ? $lastCompany->id + 1 : 1;
    //     $number = str_pad($nextId, 4, '0', STR_PAD_LEFT);

    //     return $prefix  . $year . '#' . $number;
    // }

    public static function generateCompanyCode(string $typeCode): string
    {
        $prefix = get_prefix('quotation') ?? 'QT';
        $year   = active_financial_year_start();

        $last = self::withoutGlobalScopes()
            ->where('quotation_number', 'like', $prefix . '/' . $typeCode . '/' . $year . '-%')
            ->latest('id')
            ->first();

        $nextId = $last
            ? (int) last(explode('-', $last->quotation_number)) + 1
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

    /* ================= Relations ================= */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function workPlan()
    {
        return $this->belongsTo(WorkPlan::class, 'work_plan_id');
    }


    public function items()
    {
        return $this->hasMany(QuotationItem::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function attachments()
    {
        return $this->hasMany(QuotationAttachment::class);
    }

    public function approvals()
    {
        return $this->hasMany(QuotationApproval::class);
    }

    public function currentApproval()
    {
        return $this->hasOne(QuotationApproval::class)
            ->where('level', $this->current_level);
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

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
