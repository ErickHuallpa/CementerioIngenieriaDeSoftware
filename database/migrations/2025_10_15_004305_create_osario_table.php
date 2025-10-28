<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('osario', function (Blueprint $table) {
            $table->id('id_osario');
            $table->unsignedBigInteger('id_difunto')->nullable();
            $table->foreign('id_difunto')
                  ->references('id_difunto')
                  ->on('difunto')
                  ->onDelete('cascade');
            $table->unsignedBigInteger('id_pabellon');
            $table->foreign('id_pabellon')
                  ->references('id_pabellon')
                  ->on('pabellon')
                  ->onDelete('cascade');
            $table->integer('fila');
            $table->char('columna', 1);
            $table->enum('estado', ['disponible', 'ocupado', 'por_vencer', 'vencido'])
                  ->default('disponible');
            $table->date('fecha_ingreso')->nullable();
            $table->date('fecha_salida')->nullable();
            $table->string('ubicacion')->nullable();
            $table->decimal('costo', 10, 2)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('osario');
    }
};
