<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('section_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->string('school_year');
            $table->enum('semester', ['First', 'Second', 'Summer']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('section_student');
    }
};
