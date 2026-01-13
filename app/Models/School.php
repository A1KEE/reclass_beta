<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class School extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'level_type'];

    public function applicants()
    {
        return $this->hasMany(Applicant::class, 'station_school_id');
    }
}
