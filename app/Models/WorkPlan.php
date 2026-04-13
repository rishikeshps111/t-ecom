<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class WorkPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'company_type_id',
        'total_group_id',
        'planner_id',
        'production_staff_id',
        'workplan_number',
        'date',
        'description',
        'attachment',
        'status',
        'file_type',
        'approved_by',
        'approved_at',
        'rejection_reason'
    ];

    protected $casts = [
        'date' => 'date',
        'approved_at' => 'date',
    ];

    // public static function generateCode(): string
    // {
    //     $prefix = get_prefix('work-plan') ?? 'WO';
    //     $year   = active_financial_year_start();
    //     $lastCompany = self::withoutGlobalScopes()
    //         ->latest('id')
    //         ->first();
    //     $nextId = $lastCompany ? $lastCompany->id + 1 : 1;
    //     $number = str_pad($nextId, 5, '0', STR_PAD_LEFT);

    //     return $prefix . '/' . '{var}' . '/' . $year . '-' . $number;
    // }

    public static function generateCode(string $typeCode): string
    {
        $prefix = get_prefix('work-plan') ?? 'WO';
        $year   = active_financial_year_start();

        $last = self::withoutGlobalScopes()
            ->where('workplan_number', 'like', $prefix . '/' . $typeCode . '/' . $year . '-%')
            ->latest('id')
            ->first();

        $nextId = $last
            ? (int) last(explode('-', $last->workplan_number)) + 1
            : 1;

        $number = str_pad($nextId, 5, '0', STR_PAD_LEFT);

        return "{$prefix}/{$typeCode}/{$year}-{$number}";
    }


    protected static function booted()
    {
        static::addGlobalScope('assignedMessages', function (Builder $builder) {
            if (Auth::check() && Auth::user()->hasRole('Customer')) {
                $customerCompanyIds = UserCompany::where('user_id', Auth::user()->id)
                    ->pluck('company_id')  // ✅ only IDs
                    ->toArray();

                if (!empty($customerCompanyIds)) {
                    $builder->whereIn('company_id', $customerCompanyIds);
                }
            }

            if (Auth::check() && Auth::user()->hasRole('Planner')) {
                $builder->whereRelation('company', 'planner_id', Auth::id());
            }

            if (Auth::check() && Auth::user()->hasRole('Production Staff')) {
                $builder->whereRelation('company', 'production_staff_id', Auth::id());
            }
        });
    }



    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function companyType()
    {
        return $this->belongsTo(CompanyType::class);
    }

    public function totalGroup()
    {
        return $this->belongsTo(Customer::class, 'total_group_id');
    }

    public function planner()
    {
        return $this->belongsTo(User::class, 'planner_id');
    }

    public function productionStaff()
    {
        return $this->belongsTo(User::class, 'production_staff_id');
    }

    public function quotation()
    {
        return $this->hasOne(Quotation::class);
    }

    public function attachments()
    {
        return $this->hasMany(WorkPlanAttachment::class, 'work_plan_id');
    }

    public function notes()
    {
        return $this->hasMany(WorkPlanNote::class, 'work_plan_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
