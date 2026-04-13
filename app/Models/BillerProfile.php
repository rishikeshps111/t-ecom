<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BillerProfile extends Model
{
    use HasFactory;

    protected $table = 'biller_profiles';

    protected $fillable = [
        'company_id',
        'total_group_id',
        'invoice_header',
        'invoice_footer',
        'quotation_header',
        'quotation_footer',
        'receipt_header',
        'receipt_footer',
        'work_plan_footer',
        'work_plan_header',
        'quotation_tc',
        'receipt_tc',
        'invoice_payment_terms',
        'address',
        'report_header',
        'report_footer',
        'report_tc'
    ];

    /**
     * Business User (users table)
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * Total Group (customers table)
     */
    public function totalGroup()
    {
        return $this->belongsTo(Customer::class, 'total_group_id');
    }
}
