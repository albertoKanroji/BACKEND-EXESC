<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    use HasFactory;
    protected $fillable = [
        'start_period', 'end_period', 'period', 'registration_start', 'registration_end', 'selectiv_start',
        'selectiv_end', 'periodscol', 'status'
    ];

    public function groups()
    {
        return $this->hasMany(Group::class);
    }
}
