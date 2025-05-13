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
        Schema::create('partes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained()->onDelete('cascade');
            $table->string('nombre_equipo');
            $table->text('caracteristicas')->nullable();
            $table->text('problema')->nullable();
            $table->date('fecha_entrada');
            $table->decimal('precio_estimado', 8, 2)->nullable();
            $table->decimal('precio_final', 8, 2)->nullable();
            $table->text('solucion_aplicada')->nullable();
            $table->enum('estado', ['pendiente', 'en_proceso', 'terminado', 'entregado'])->default('pendiente');
            $table->date('fecha_salida')->nullable();
            $table->text('observaciones')->nullable();
            $table->json('imagenes')->nullable(); // para múltiples archivos
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partes');
    }
};
