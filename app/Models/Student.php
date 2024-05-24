<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'email', 'username', 'password', 'last_name', 'mother_last_name', 'control_number', 'status',
        'profile_picture', 'phone', 'profile', 'semester', 'gender', 'careers_id'
    ];

    public function career()
    {
        return $this->belongsTo(Career::class, 'careers_id');
    }

    public function surveyResponses()
    {
        return $this->hasMany(SurveyResponse::class);
    }
}
