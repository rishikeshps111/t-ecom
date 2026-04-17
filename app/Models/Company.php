<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class Company extends Model
{
    use HasFactory;
    protected $fillable = [
        'category_id',
        'sub_category_id',
        'planner_id',
        'planner_code',
        'company_code',
        'company_type',
        'company_name',
        'alt_company_name',
        'industry',
        'description',
        'status',
        'ssm_number',
        'incorporation_date',
        'commencement_date',
        'paid_up_capital',
        'authorized_capital',
        'employees',
        'primary_contact_name',
        'designation',
        'mobile_no',
        'email_address',
        'company_website',
        'company_type_id',
        'business_user_id',
        'total_group_id',
        'production_staff_id',
        'address'
    ];

    protected function customCompanyId(): Attribute
    {
        return Attribute::get(function () {
            $prefix = get_prefix('company') ?? 'COMP';
            $year   = active_financial_year_start();
            $number = str_pad($this->id, 4, '0', STR_PAD_LEFT);
            return $prefix . $year . '#' . $number;
        });
    }

    protected static function booted()
    {
        static::addGlobalScope('assignedCompanies', function (Builder $builder) {
            if (Auth::check() && (Auth::user()->hasRole('Corp User') || Auth::user()->hasRole('Customer'))) {
                // $builder->whereHas('businessUser', function ($q) {
                //     $q->where('users.id', Auth::id());
                // });
                $customerCompanyIds = UserCompany::where('user_id', Auth::user()->id)
                    ->pluck('company_id')  // ✅ only IDs
                    ->toArray();

                if (!empty($customerCompanyIds)) {
                    $builder->whereIn('id', $customerCompanyIds);
                }
            }
            if (Auth::check() && Auth::user()->hasRole('Planner')) {
                $builder->where('planner_id', Auth::user()->id);
            }

            if (Auth::check() && Auth::user()->hasRole('Production Staff')) {
                $builder->where('production_staff_id', Auth::user()->id);
            }
        });
    }

    public function address()
    {
        return $this->hasOne(CompanyAddress::class);
    }

    public function directors()
    {
        return $this->hasMany(CompanyDirector::class);
    }

    public function shareholders()
    {
        return $this->hasMany(CompanyShareholder::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function billerProfile()
    {
        return $this->hasOne(BillerProfile::class, 'company_id');
    }


    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function quotations()
    {
        return $this->hasMany(Quotation::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }


    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function workPlans()
    {
        return $this->hasMany(WorkPlan::class);
    }

    public function companyDocuments()
    {
        return $this->hasMany(CompanyDocument::class);
    }

    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'user_companies'
        )->withTimestamps();
    }

    public function userCompanies()
    {
        return $this->hasMany(UserCompany::class);
    }

    public function planner()
    {
        return $this->belongsTo(User::class, 'planner_id');
    }

    public function companyType()
    {
        return $this->belongsTo(CompanyType::class);
    }

    public function productionStaff()
    {
        return $this->belongsTo(User::class, 'production_staff_id');
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
