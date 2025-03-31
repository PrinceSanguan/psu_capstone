<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'faculty_id',
        'title',
        'type',
        'max_score',
        'term',
        'schedule_date',
        'schedule_time',
        'school_year',
        'semester'
    ];

    protected $casts = [
        'schedule_date' => 'date',
        'schedule_time' => 'datetime',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function faculty()
    {
        return $this->belongsTo(User::class, 'faculty_id');
    }

    public function scores()
    {
        return $this->hasMany(StudentScore::class);
    }
}
