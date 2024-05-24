<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'code', 'image', 'type_of_activities_id'];

    public function typeOfActivity()
    {
        return $this->belongsTo(TypeOfActivity::class, 'type_of_activities_id');
    }

    public function groups()
    {
        return $this->hasMany(Group::class);
    }
}
