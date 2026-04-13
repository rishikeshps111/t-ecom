<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class PlannerDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'planner_id',
        'company_id',
        'financial_year_id',
        'company_type_id',
        'business_user_id',
        'total_group_id',
        'title',
        'start_date',
        'end_date',
        'description',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    protected static function booted()
    {
        static::addGlobalScope('assignedCompanyPlannerDocuments', function (Builder $builder) {

            if (!Auth::check()) {
                return;
            }

            $user = Auth::user();

            // Super Admin → no restriction
            if ($user->hasRole('Super Admin')) {
                return;
            }

            // Planner → filter by planner_id
            if ($user->hasRole('Planner')) {
                $builder->where('planner_id', $user->id);
                return;
            }

            // Other users → existing logic
            $builder->whereHas('businessUser', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            });
        });
    }
    /**
     * Document belongs to a company
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function planner()
    {
        return $this->belongsTo(User::class, 'planner_id');
    }

    public function financialYear()
    {
        return $this->belongsTo(FinancialYear::class);
    }


    /**
     * Document has many files
     */
    public function files()
    {
        return $this->hasMany(PlannerDocumentFile::class);
    }

    public function companyType()
    {
        return $this->belongsTo(CompanyType::class);
    }

    public function businessUser()
    {
        return $this->belongsTo(User::class, 'business_user_id');
    }

    public function totalGroup()
    {
        return $this->belongsTo(Customer::class, 'total_group_id');
    }
}
