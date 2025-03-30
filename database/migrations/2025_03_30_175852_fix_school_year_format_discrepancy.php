<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class FixSchoolYearFormatDiscrepancy extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Check and update section_subject table
        if (Schema::hasColumn('section_subject', 'school_year')) {
            DB::statement("UPDATE section_subject SET school_year = REPLACE(school_year, ' - ', '-') WHERE school_year LIKE '% - %'");
        }

        // Check and update section_student table
        if (Schema::hasColumn('section_student', 'school_year')) {
            DB::statement("UPDATE section_student SET school_year = REPLACE(school_year, ' - ', '-') WHERE school_year LIKE '% - %'");
        }

        // Check and update syllabi table
        if (Schema::hasTable('syllabi') && Schema::hasColumn('syllabi', 'school_year')) {
            DB::statement("UPDATE syllabi SET school_year = REPLACE(school_year, ' - ', '-') WHERE school_year LIKE '% - %'");
        }

        // Check and update assessments table
        if (Schema::hasTable('assessments') && Schema::hasColumn('assessments', 'school_year')) {
            DB::statement("UPDATE assessments SET school_year = REPLACE(school_year, ' - ', '-') WHERE school_year LIKE '% - %'");
        }

        // Check and update seat_plans table
        if (Schema::hasTable('seat_plans') && Schema::hasColumn('seat_plans', 'school_year')) {
            DB::statement("UPDATE seat_plans SET school_year = REPLACE(school_year, ' - ', '-') WHERE school_year LIKE '% - %'");
        }

        // Check and update grading_systems table
        if (Schema::hasTable('grading_systems') && Schema::hasColumn('grading_systems', 'school_year')) {
            DB::statement("UPDATE grading_systems SET school_year = REPLACE(school_year, ' - ', '-') WHERE school_year LIKE '% - %'");
        }

        // Removed student_scores as it doesn't have a school_year column
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // No need to reverse this migration as it's a data cleanup operation
    }
}
