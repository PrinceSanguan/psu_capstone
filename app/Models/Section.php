<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'course',
        'year_level',
        'max_students'
    ];

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'section_subject')
            ->withPivot('faculty_id', 'school_year', 'semester')
            ->withTimestamps();
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'section_student', 'section_id', 'student_id')
            ->withPivot('school_year', 'semester')
            ->withTimestamps();
    }

    public function seatPlans()
    {
        return $this->hasMany(SeatPlan::class);
    }
}
