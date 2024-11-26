<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pilots', function (Blueprint $table) {
            $table->dropColumn('team'); // Eliminar la columna team
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pilots', function (Blueprint $table) {
            $table->string('team'); // Volver a agregar la columna team si se revierte la migración
        });
    }
};
