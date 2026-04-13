<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'company_id',
        'city_id',
        'state_id',
        'currency_id',
        'customer_code',
        'customer_name',
        'email',
        'phone',
        'alternate_phone',
        'country',
        'billing_address',
        'shipping_address',
        'remarks',
        'logo',
        'gst',
        'tax_id',
        'is_active',
        'tss',
        'banner',
        'company_type_id'
    ];

    protected static function booted()
    {
        static::addGlobalScope('exclude_default', function ($query) {
            $query->where('customer_name', '!=', 'Default');
        });
    }

    public static function generateCustomerCode(int $customerId): string
    {
        $year = now()->year;

        return sprintf(
            'CUS/%s/%04d',
            $year,
            $customerId
        );
    }

    protected function customUserId(): Attribute
    {
        return Attribute::get(function () {
            $prefix = get_prefix('customer') ?? 'CUS';
            $year   = active_financial_year_start();;
            $number = str_pad($this->id, 4, '0', STR_PAD_LEFT);
            return $prefix . $year . '#' . $number;
        });
    }

    public function scopeSearchByCustomId($query, $value)
    {
        if (preg_match('/(\d+)$/', $value, $matches)) {
            $query->where('id', (int) $matches[1]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // Customer belongs to a User (created by / owner)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function billerProfile()
    {
        return $this->hasOne(BillerProfile::class, 'total_group_id');
    }

    // Customer belongs to a City (locations table)
    public function city()
    {
        return $this->belongsTo(Location::class, 'city_id');
    }

    // Customer belongs to a State
    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }
}
