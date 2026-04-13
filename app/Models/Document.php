<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_type_id',
        'business_user_id',
        'total_group_id',
        'company_id',
        'project_id',
        'financial_year_id',
        'type',
        'title',
        'document_type',
        'document',
        'valid_from',
        'valid_to',
        'year',
        'status',
    ];

    protected $casts = [
        'valid_from' => 'date',
        'valid_to' => 'date',
    ];

    protected static function booted()
    {
        static::addGlobalScope('assignedCompanyDocuments', function (Builder $builder) {
            if (Auth::check() && ! Auth::user()->hasRole('Super Admin')) {
                $builder->whereHas('businessUser', function ($q) {
                    $q->where('users.id', Auth::id());
                });
            }
        });
    }
    /** Relationships */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function financialYear()
    {
        return $this->belongsTo(FinancialYear::class);
    }


    public function project()
    {
        return $this->belongsTo(Project::class);
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
