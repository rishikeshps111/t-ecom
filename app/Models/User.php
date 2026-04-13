<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'city_id',
        'state_id',
        'user_name',
        'name',
        'email',
        'password',
        'show_password',
        'phone',
        'profile_image',
        'company_id',
        'status',
        'role',
        'profile_image',
        'contact_person',
        'address',
        'assigned_item',
        'pricing_level',
        'commission_factor',
        'tax_group',
        'quotation_access',
        'invoice_view',
        'department',
        'designation',
        'task_access',
        'document_upload',
        'document_edit',
        'task_scope',
        'approval_authority',
        'verson_control',
        'document_delete',
        'folder_access',
        'document_category',
        'alternate_phone',
        'billing_address',
        'country',
        'gst',
        'tax_id',
        'whats_app',
        'description',
        'is_active',
        'joining_date',
        'total_group_id',
        'user_type',
        'relived_at',
        'planner_code',
        'iv',
        'sequence_number',
        'planner_c_percentage',
        'production_c_percentage',
        'is_locked',
        'locked_reason',
        'user_code'
    ];

    protected $casts = [
        'joining_date' => 'date',
        'relived_at' => 'date',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function setPasswordAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['password'] = Hash::make($value);
        }
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

    public function quotations()
    {
        return $this->hasMany(Quotation::class, 'customer_id');
    }

    public function createdQuotations()
    {
        return $this->hasMany(Quotation::class, 'user_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'customer_id');
    }

    public function createdInvoices()
    {
        return $this->hasMany(Invoice::class, 'created_by');
    }

    public function Users()
    {
        return $this->hasMany(Customer::class);
    }

    public function city()
    {
        return $this->belongsTo(Location::class, 'city_id');
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function customers()
    {
        return $this->hasMany(Company::class, 'planner_id');
    }


    public function companies()
    {
        return $this->belongsToMany(
            Company::class,
            'user_companies'
        )->withTimestamps();
    }

    public function userCompanies()
    {
        return $this->hasMany(UserCompany::class);
    }

    public function announcements()
    {
        return $this->belongsToMany(
            Announcement::class,
            'announcement_users'
        )
            ->using(AnnouncementUser::class)
            ->withTimestamps();
    }

    // public function companies()
    // {
    //     return $this->hasMany(Company::class, 'business_user_id');
    // }

    public function totalGroup()
    {
        return $this->belongsTo(Customer::class, 'total_group_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
    public function customerUsers()
    {
        return $this->hasMany(Company::class, 'production_staff_id');
    }

    public function plannerCompanies()
    {
        return $this->hasMany(Company::class, 'planner_id');
    }




    public function totalGroups()
    {
        return $this->belongsToMany(
            Customer::class,          // related model
            'user_total_groups',      // pivot table
            'user_id',                // foreign key on pivot
            'total_group_id'          // related key on pivot
        );
    }

    public function plannerPayouts()
    {
        return $this->hasMany(PlannerPayout::class);
    }

    public function productionStaffPayouts()
    {
        return $this->hasMany(ProductionStaffPayout::class);
    }
}
