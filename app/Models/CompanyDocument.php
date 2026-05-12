<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CompanyDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'title',
        'total_group_id',
        'financial_year_id',
        'type',
        'file',
    ];

    protected static function booted()
    {
        static::addGlobalScope('assignedCompanyDocuments', function (Builder $builder) {
            if (!Auth::check()) {
                return;
            }

            $user = Auth::user();

            if ($user->hasRole('Super Admin')) {
                return;
            }

            if ($user->hasRole(['Customer', 'Corp User'])) {
                $companyIds = UserCompany::where('user_id', $user->id)
                    ->pluck('company_id')
                    ->toArray();

                if (!empty($companyIds)) {
                    $builder->whereIn('company_id', $companyIds);
                }

                return;
            }

            if ($user->hasRole('Planner')) {
                $builder->whereHas('company', function ($query) use ($user) {
                    $query->where('planner_id', $user->id);
                });

                return;
            }

            if ($user->hasRole('Production Staff')) {
                $builder->whereHas('company', function ($query) use ($user) {
                    $query->where('production_staff_id', $user->id);
                });
            }
        });
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function totalGroup()
    {
        return $this->belongsTo(Customer::class, 'total_group_id');
    }

    public function financialYear()
    {
        return $this->belongsTo(FinancialYear::class);
    }

    protected function fileName(): Attribute
    {
        return Attribute::get(function () {
            if (!$this->file) {
                return '-';
            }

            return basename($this->file);
        });
    }
}
