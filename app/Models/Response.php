<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    use HasFactory;
    protected $fillable = ['options_id', 'questions_id'];

    public function option()
    {
        return $this->belongsTo(Option::class, 'options_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class, 'questions_id');
    }

    public function surveyResponses()
    {
        return $this->hasMany(SurveyResponse::class);
    }
}
