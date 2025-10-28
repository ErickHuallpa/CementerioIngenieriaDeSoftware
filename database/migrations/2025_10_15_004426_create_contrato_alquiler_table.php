<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contrato_alquiler', function (Blueprint $table) {
            $table->id('id_contrato');
            $table->unsignedBigInteger('id_difunto');
            $table->foreign('id_difunto')
                  ->references('id_difunto')
                  ->on('difunto')
                  ->onDelete('cascade');

            $table->unsignedBigInteger('id_nicho')->nullable();
            $table->foreign('id_nicho')
                  ->references('id_nicho')
                  ->on('nicho')
                  ->onDelete('cascade');

            $table->unsignedBigInteger('id_osario')->nullable();
            $table->foreign('id_osario')
                  ->references('id_osario')
                  ->on('osario')
                  ->onDelete('cascade');
            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable();
            $table->integer('renovaciones')->default(0);
            $table->decimal('monto', 10, 2);
            $table->enum('estado', ['activo', 'vencido', 'renovado', 'cancelado'])
                  ->default('activo');

            $table->string('boleta_numero')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contrato_alquiler');
    }
};
