<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nicho', function (Blueprint $table) {
            $table->id('id_nicho');
            $table->unsignedBigInteger('id_pabellon');
            $table->foreign('id_pabellon')
                ->references('id_pabellon')
                ->on('pabellon')
                ->onDelete('cascade');
            $table->integer('fila');
            $table->char('columna', 1);
            $table->enum('posicion', ['superior', 'medio', 'inferior']);
            $table->decimal('costo_alquiler', 10, 2)->nullable();
            $table->enum('estado', ['disponible', 'ocupado', 'por_vencer', 'vencido'])->default('disponible');
            $table->date('fecha_ocupacion')->nullable();
            $table->date('fecha_vencimiento')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nicho');
    }
};
