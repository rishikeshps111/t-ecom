<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class WorkPlanAttachment extends Model
{
    protected $fillable = [
        'work_plan_id',
        'payment_id',
        'entity',
        'file',
        'name',
        'type',
        'year',
        'description',
        'service_type_id'
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
    public function workPlan()
    {
        return $this->belongsTo(WorkPlan::class);
    }

    public function serviceType()
    {
        return $this->belongsTo(CompanyType::class, 'service_type_id');
    }
}
