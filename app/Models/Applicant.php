<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Applicant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'position_applied',
        'station_school_id',
        'current_position',
        'item_number',
        'sg_annual_salary',
        'levels'
    ];

    protected $casts = [
        'levels' => 'array',
    ];

    public function school()
    {
        return $this->belongsTo(School::class, 'station_school_id');
    }
}
