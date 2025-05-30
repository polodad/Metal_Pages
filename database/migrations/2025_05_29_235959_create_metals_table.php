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
        Schema::create('metals', function (Blueprint $table) {
            $table->id();
            $table->string('name');                     // Nombre del metal (ej: Cobre, Acero, Aluminio, Bronce)
            $table->string('slug')->unique();            // URL amigable
            $table->text('description')->nullable();     // Descripción detallada
            $table->string('code')->unique();            // Código único del metal (ej: CU, FE, AL)
            $table->string('unit')->default('kg');        // Unidad de medida (kg, ton, etc.)
            $table->decimal('density', 10, 2)->nullable(); // Densidad en kg/m³
            $table->decimal('melting_point', 10, 2)->nullable(); // Punto de fusión en °C
            $table->decimal('boiling_point', 10, 2)->nullable(); // Punto de ebullición en °C
            $table->boolean('is_active')->default(true);  // Si el metal está activo en el sistema
            $table->timestamps();
            $table->softDeletes();                       // Para borrado lógico
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('metals');
    }
};
