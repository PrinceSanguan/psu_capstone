<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->foreignId('faculty_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->enum('type', ['quiz', 'unit_test', 'activity', 'midterm_exam', 'final_exam']);
            $table->integer('max_score');
            $table->enum('term', ['midterm', 'final']);
            $table->date('schedule_date')->nullable();
            $table->time('schedule_time')->nullable();
            $table->string('school_year');
            $table->enum('semester', ['First', 'Second', 'Summer']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};
