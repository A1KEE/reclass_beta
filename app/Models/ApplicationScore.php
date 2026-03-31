<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationScore extends Model
{
    protected $table = 'application_scores';

    protected $fillable = [
        'application_id',
        'education_points',
        'education_remarks',
        'training_points',
        'training_remarks',
        'experience_points',
        'experience_remarks',
        'performance_points',
        'coi_score',
        'ncoi_score',
        'bei_score',

        'total_score'
    ];
}
