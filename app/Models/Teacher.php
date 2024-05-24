<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'last_name', 'mother_last_name', 'gender', 'abbreviated_title', 'curp', 'rfc', 'username', 'password',
        'email', 'profile_picture', 'signature', 'profile', 'departments_id'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'departments_id');
    }

    public function groups()
    {
        return $this->hasMany(Group::class);
    }

    public function surveyResponses()
    {
        return $this->hasMany(SurveyResponse::class);
    }
}
