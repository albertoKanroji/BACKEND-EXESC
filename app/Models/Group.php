<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;
    protected $fillable = [
        'quota_limit', 'location', 'periods_id', 'teachers_id', 'activities_id', 'type_of_groups_id'
    ];

    public function period()
    {
        return $this->belongsTo(Period::class, 'periods_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teachers_id');
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class, 'activities_id');
    }

    public function typeOfGroup()
    {
        return $this->belongsTo(TypeOfGroup::class, 'type_of_groups_id');
    }

    public function schedules()
    {
        return $this->belongsToMany(Schedule::class, 'group_schedules');
    }
    // En el modelo Group
    public function students()
    {
        return $this->belongsToMany(Student::class, 'students_groups', 'groups_id', 'students_id');
    }
}
