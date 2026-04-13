<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_plan_id',
        'workorder_number',
        'date',
        'description',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public static function generateCode(): string
    {
        $prefix = get_prefix('work-order') ?? 'WO';
        $year   = active_financial_year_start();
        $lastCompany = self::withoutGlobalScopes()
            ->latest('id')
            ->first();
        $nextId = $lastCompany ? $lastCompany->id + 1 : 1;
        $number = str_pad($nextId, 4, '0', STR_PAD_LEFT);

        return $prefix  . $year . '#' . $number;
    }

    public function workPlan()
    {
        return $this->belongsTo(WorkPlan::class);
    }
}
