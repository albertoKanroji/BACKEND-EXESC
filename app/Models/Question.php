<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $fillable = ['question', 'surveys_id'];

    public function survey()
    {
        return $this->belongsTo(Survey::class, 'surveys_id');
    }

    public function options()
    {
        return $this->belongsToMany(Option::class, 'question_options', 'questions_id', 'options_id');
    }

    public function responses()
    {
        return $this->hasMany(Response::class);
    }
}
