<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programacion_entierro', function (Blueprint $table) {
            $table->id('id_programacion');
            $table->unsignedBigInteger('id_difunto');
            $table->foreign('id_difunto')->references('id_difunto')->on('difunto')->onDelete('cascade');
            $table->unsignedBigInteger('id_trabajador')->nullable();
            $table->foreign('id_trabajador')->references('id_persona')->on('persona')->onDelete('set null');
            $table->date('fecha_programada');
            $table->time('hora_programada');
            $table->enum('estado', ['pendiente', 'completado', 'retrasado'])->default('pendiente');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programacion_entierro');
    }
};
