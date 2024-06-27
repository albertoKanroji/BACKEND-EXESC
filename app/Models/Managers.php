<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Managers extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'last_name',
        'mother_last_name',
        'abbreviated_title',
        'gender',
        'signature'
    ];
    public function managers_cargos()
    {
        return $this->belongsToMany(Schedule::class, 'managers_cargos', 'managers_id', 'cargos_id');
    }
}
