<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class Item extends Model
{
    use HasFactory;
    protected $fillable = [
        'item_code',
        'item_name',
        'category_id',
        'sub_category_id',
        'user_id',
        'company_id',
        'item_type',
        'status',

        'selling_price',
        'cost_price',
        'commission_factor',
        'tax_group',

        'uom',
        'opening_stock',
        'reorder_level',
        'safety_stock',

        'default_supplier',
        'supplier_item',
        'purchase_price',

        'warehouse',
        'bin_location',
        'weight',

        'short_description',
        'detail_description',
        'company_type_id',
        'total_group_id',
        'suggested_price',

        'tss',
        'planner_commission',
        'planner_iv_percentage',
        'planner_c_percentage',
        'production_commission',
        'production_iv_percentage',
        'production_c_percentage',
        'stt',
        'account_code'
    ];
    protected $casts = [
        'suggested_price' => 'float',
        'status' => 'boolean',
    ];

    protected function customItemId(): Attribute
    {
        return Attribute::get(function () {
            $prefix = get_prefix('item') ?? 'CUS';
            $year   = active_financial_year_start();;
            $number = str_pad($this->id, 4, '0', STR_PAD_LEFT);
            return $prefix . $year . '#' . $number;
        });
    }

    // protected static function booted()
    // {
    //     static::addGlobalScope('assignedCompanyItems', function (Builder $builder) {
    //         if (Auth::check() && ! Auth::user()->hasRole('Super Admin')) {
    //             $builder->whereHas('company.users', function ($q) {
    //                 $q->where('users.id', Auth::id());
    //             });
    //         }
    //     });
    // }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function quotationItems()
    {
        return $this->hasMany(QuotationItem::class);
    }

    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function companyType()
    {
        return $this->belongsTo(CompanyType::class);
    }

    public function totalGroup()
    {
        return $this->belongsTo(Customer::class, 'total_group_id');
    }
}
