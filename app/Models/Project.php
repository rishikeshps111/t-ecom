<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_category_id',
        'company_id',
        'name',
        'description',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    protected function customProjectId(): Attribute
    {
        return Attribute::get(function () {
            $prefix = get_prefix('project') ?? 'PROJ';
            $year   = active_financial_year_start();;
            $number = str_pad($this->id, 4, '0', STR_PAD_LEFT);
            return $prefix . $year . '#' . $number;
        });
    }

    /** Relationships */
    public function category()
    {
        return $this->belongsTo(ProjectCategory::class, 'project_category_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
