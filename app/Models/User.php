<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'student_number',
        'major',
        'sex',
        'course',
        'year',
        'password',
        'user_role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
    ];

    /**
     * Get the username field used for authentication.
     *
     * @return string
     */
    public function username()
    {
        return 'student_number';
    }

    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return $this->user_role === 'admin';
    }

    /**
     * Check if user is faculty
     */
    public function isFaculty()
    {
        return $this->user_role === 'faculty';
    }

    /**
     * Check if user is client/student
     */
    public function isClient()
    {
        return $this->user_role === 'client';
    }

    /**
     * Get sections taught by faculty
     */
    public function taughtSections()
    {
        return $this->belongsToMany(Section::class, 'section_subject', 'faculty_id', 'section_id')
            ->withPivot('subject_id', 'school_year', 'semester')
            ->withTimestamps();
    }

    /**
     * Get subjects taught by faculty
     */
    public function taughtSubjects()
    {
        return $this->belongsToMany(Subject::class, 'section_subject', 'faculty_id', 'subject_id')
            ->withPivot('section_id', 'school_year', 'semester')
            ->withTimestamps();
    }

    /**
     * Get sections enrolled by student
     */
    public function enrolledSections()
    {
        return $this->belongsToMany(Section::class, 'section_student', 'student_id', 'section_id')
            ->withPivot('school_year', 'semester')
            ->withTimestamps();
    }

    /**
     * Get syllabi uploaded by faculty
     */
    public function syllabi()
    {
        return $this->hasMany(Syllabus::class, 'faculty_id');
    }

    /**
     * Get assessments created by faculty
     */
    public function assessments()
    {
        return $this->hasMany(Assessment::class, 'faculty_id');
    }

    /**
     * Get scores for a student
     */
    public function scores()
    {
        return $this->hasMany(StudentScore::class, 'student_id');
    }

    /**
     * Get seat plans created by faculty
     */
    public function seatPlans()
    {
        return $this->hasMany(SeatPlan::class, 'faculty_id');
    }
}
