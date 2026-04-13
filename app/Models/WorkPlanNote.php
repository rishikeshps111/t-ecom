<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkPlanNote extends Model
{
    protected $fillable = [
        'work_plan_id',
        'note_type_id',
        'description',
        'status'
    ];


    public function workPlan()
    {
        return $this->belongsTo(WorkPlan::class);
    }

    public function noteType()
    {
        return $this->belongsTo(NoteType::class, 'note_type_id');
    }
}
