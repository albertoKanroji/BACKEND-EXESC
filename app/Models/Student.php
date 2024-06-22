<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Student extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasFactory;
    protected $fillable = [
        'name', 'email', 'username', 'password', 'last_name', 'mother_last_name', 'control_number', 'status',
        'image', 'phone', 'profile', 'semester', 'gender', 'careers_id'
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    public function career()
    {
        return $this->belongsTo(Career::class, 'careers_id');
    }

    public function surveyResponses()
    {
        return $this->hasMany(SurveyResponse::class);
    }
    public function modulos()
    {
        return $this->belongsToMany(Modulo::class, 'student_modules', 'students_id', 'modules_id');
    }
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'students_groups', 'students_id', 'groups_id');
    }
}