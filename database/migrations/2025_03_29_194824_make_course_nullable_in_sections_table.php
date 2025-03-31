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
        Schema::table('sections', function (Blueprint $table) {
            $table->string('course')->nullable()->change();
            $table->string('year_level')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('sections', function (Blueprint $table) {
            // Revert them back to NOT NULL (or however they were defined before)
            $table->string('course')->nullable(false)->change();
            $table->string('year_level')->nullable(false)->change();
        });
    }
};
