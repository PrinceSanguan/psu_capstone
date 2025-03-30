<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'units'
    ];

    public function sections()
    {
        return $this->belongsToMany(Section::class, 'section_subject')
            ->withPivot('faculty_id', 'school_year', 'semester')
            ->withTimestamps();
    }

    public function faculty()
    {
        return $this->belongsToMany(User::class, 'section_subject', 'subject_id', 'faculty_id')
            ->withPivot('section_id', 'school_year', 'semester')
            ->withTimestamps();
    }

    public function syllabi()
    {
        return $this->hasMany(Syllabus::class);
    }

    public function gradingSystems()
    {
        return $this->hasMany(GradingSystem::class);
    }

    public function assessments()
    {
        return $this->hasMany(Assessment::class);
    }
}
