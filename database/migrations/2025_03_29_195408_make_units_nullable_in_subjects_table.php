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
    Schema::table('subjects', function (Blueprint $table) {
        // Example: make units an INT that can be NULL
        $table->integer('units')->nullable()->change();
    });
}

public function down()
{
    Schema::table('subjects', function (Blueprint $table) {
        // Revert back
        $table->integer('units')->nullable(false)->change();
    });
}

};
