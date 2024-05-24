<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;
    public function questions()
    {
        return $this->belongsToMany(Question::class, 'question_options', 'options_id', 'questions_id');
    }

    public function responses()
    {
        return $this->hasMany(Response::class);
    }
}
