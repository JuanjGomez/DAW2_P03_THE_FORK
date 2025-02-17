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
        Schema::create('restaurantes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_r', 75)->unique();
            $table->string('descripcion', 255)->nullable();
            $table->string('direccion', 255)->nullable();
            $table->decimal('precio_promedio', 8, 2);
            $table->string('imagen', 255)->nullable();
            $table->string('municipio', 255)->nullable();
            $table->unsignedBigInteger('tipo_cocina_id');
            $table->unsignedBigInteger('manager_id')->nullable();
            $table->foreign('tipo_cocina_id')->references('id')->on('tipo_cocina');
            $table->foreign('manager_id')->references('id')->on('usuarios');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurantes');
    }
};
