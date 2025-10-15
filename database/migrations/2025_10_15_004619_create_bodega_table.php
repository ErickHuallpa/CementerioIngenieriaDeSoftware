<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bodega', function (Blueprint $table) {
            $table->id('id_bodega');
            $table->unsignedBigInteger('id_difunto');
            $table->foreign('id_difunto')->references('id_difunto')->on('difunto')->onDelete('cascade');
            $table->date('fecha_ingreso');
            $table->date('fecha_salida')->nullable();
            $table->enum('destino', ['osario', 'incinerado', 'renovado'])->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bodega');
    }
};
