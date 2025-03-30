<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grading_systems', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->decimal('quiz_percentage', 5, 2);
            $table->decimal('unit_test_percentage', 5, 2);
            $table->decimal('activity_percentage', 5, 2);
            $table->decimal('exam_percentage', 5, 2);
            $table->string('school_year');
            $table->enum('semester', ['First', 'Second', 'Summer']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grading_systems');
    }
};
