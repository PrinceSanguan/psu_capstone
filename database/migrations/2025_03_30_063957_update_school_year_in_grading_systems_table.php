<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('grading_systems', function (Blueprint $table) {
        // Option A: allow nullable
        $table->string('school_year')->nullable()->change();

        // OR Option B: give a default
        // $table->string('school_year')->default('N/A')->change();
    });
}

public function down()
{
    Schema::table('grading_systems', function (Blueprint $table) {
        // If you want to revert to NOT NULL with no default:
        $table->string('school_year')->nullable(false)->change();
        // or remove the default if previously used
    });
}

};
