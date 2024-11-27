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
        Schema::table('teams', function (Blueprint $table) {
            $table->string('director')->nullable(); // Add the director column
            $table->string('logo')->nullable(); // Add the logo column
        });
    }

    public function down()
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->dropColumn('director'); // Remove the director column if rolled back
            $table->dropColumn('logo'); // Remove the logo column if rolled back
        });
    }
};
