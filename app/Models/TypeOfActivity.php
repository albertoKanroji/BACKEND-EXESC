<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeOfActivity extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    public function activities()
    {
        return $this->hasMany(Activity::class, 'type_of_activities_id');
    }
}
