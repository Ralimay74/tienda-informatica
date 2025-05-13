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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->boolean('aprobado')->default(true);
            $table->boolean('persona_fisica')->default(false);
            $table->string('dni_cif');
            $table->string('nombre');
            $table->string('apellidos')->nullable();
            $table->string('marca_comercial')->nullable();
            $table->string('nombre_responsable')->nullable();
            $table->string('web')->nullable();
            $table->string('email')->nullable();
            $table->string('tlf_1')->nullable();
            $table->string('tlf_2')->nullable();
            $table->string('direccion');
            $table->string('cp');
            $table->string('localidad')->nullable();
            $table->string('provincia');
            $table->string('pais')->default('España');
            $table->text('observaciones')->nullable();
            $table->string('tipo_cliente')->default('particular');
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
