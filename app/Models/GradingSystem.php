<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradingSystem extends Model
{
    use HasFactory;

    protected $table = 'grading_systems';

    protected $fillable = [
        'subject_id',
        'quiz_percentage',
        'unit_test_percentage',
        'activity_percentage',
        'exam_percentage',
        'school_year',
        'semester',
    ];
}
