<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyResponse extends Model
{
    use HasFactory;
    protected $fillable = ['surveys_id', 'responses_id', 'teachers_id', 'students_id'];

    public function survey()
    {
        return $this->belongsTo(Survey::class, 'surveys_id');
    }

    public function response()
    {
        return $this->belongsTo(Response::class, 'responses_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teachers_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'students_id');
    }
}
