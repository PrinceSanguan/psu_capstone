<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Syllabus extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'faculty_id',
        'file_path',
        'original_filename',
        'upload_timestamp',
        'school_year',
        'semester'
    ];

    protected $casts = [
        'upload_timestamp' => 'datetime',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function faculty()
    {
        return $this->belongsTo(User::class, 'faculty_id');
    }
}
