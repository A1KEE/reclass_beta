<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Application extends Model
{
    use HasFactory;

   protected $fillable = [
    'name',
    'current_position',
    'position_applied',
    'item_number',
    'school_name',
    'sg_annual_salary',
    'levels',
    'status',
    'last_activity_at'
];

    protected $casts = [
        'levels' => 'array'
    ];

    /* =========================
       RELATIONSHIPS
    ========================== */

    public function educations()
    {
        return $this->hasMany(Education::class);
    }

    public function trainings()
    {
        return $this->hasMany(Training::class);
    }

    public function experiences()
    {
        return $this->hasMany(Experience::class);
    }

    public function eligibilities()
    {
        return $this->hasMany(Eligibility::class);
    }

    public function ipcrfs()
    {
        return $this->hasMany(IpcrfFile::class);
    }
    public function ppstRatings()
    {
        return $this->hasMany(ApplicationPpstRating::class);
    }
    public function scores()
    {
        return $this->hasOne(\App\Models\ApplicationScore::class, 'application_id');
    }
}