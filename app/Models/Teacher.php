<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Teacher extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasFactory;
    use HasFactory;
    protected $fillable = [
        'name', 'last_name', 'mother_last_name', 'gender', 'abbreviated_title', 'curp', 'rfc', 'username', 'password',
        'email', 'profile_picture', 'signature', 'profile', 'departments_id', 'status'
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

    public function department()
    {
        return $this->belongsTo(Department::class, 'departments_id');
    }

    public function groups()
    {
        return $this->hasMany(Group::class, 'teachers_id');
    }


    public function surveyResponses()
    {
        return $this->hasMany(SurveyResponse::class);
    }
    public function modulos()
    {
        return $this->belongsToMany(Modulo::class, 'teacher_modules', 'teachers_id', 'modules_id');
    }
}
