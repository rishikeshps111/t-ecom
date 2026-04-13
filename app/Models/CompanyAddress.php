<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompanyAddress extends Model
{
    use HasFactory;
    protected $fillable = [
        'company_id',
        'address1',
        'address2',
        'city_id',
        'state_id',
        'postcode',
        'country',
        'office_phone',
        'office_email',
        'business_address1',
        'business_address2',
        'business_city_id',
        'business_state_id',
        'business_postcode',
        'business_country'
    ];

    public function city()
    {
        return $this->belongsTo(Location::class, 'city_id');
    }

    /**
     * State relation (office address)
     */
    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    /**
     * Business City relation (business address)
     */
    public function businessCity()
    {
        return $this->belongsTo(Location::class, 'business_city_id');
    }

    /**
     * Business State relation (business address)
     */
    public function businessState()
    {
        return $this->belongsTo(State::class, 'business_state_id');
    }
}
