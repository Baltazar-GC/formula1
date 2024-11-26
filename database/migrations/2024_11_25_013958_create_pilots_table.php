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
        Schema::create('pilots', function (Blueprint $table) {
            $table->id(); // ID del piloto
            $table->string('name'); // Nombre del piloto
            $table->string('team'); // Escudería del piloto
            $table->integer('age'); // Edad del piloto
            $table->string('country'); // País del piloto
            $table->timestamps(); // Fechas de creación y actualización
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pilots');
    }
};
