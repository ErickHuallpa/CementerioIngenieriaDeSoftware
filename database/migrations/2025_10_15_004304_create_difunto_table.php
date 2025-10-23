<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('difunto', function (Blueprint $table) {
            $table->id('id_difunto');
            $table->unsignedBigInteger('id_persona');
            $table->foreign('id_persona')->references('id_persona')->on('persona')->onDelete('cascade');
            $table->unsignedBigInteger('id_doliente')->nullable();
            $table->foreign('id_doliente')->references('id_persona')->on('persona')->onDelete('set null');
            $table->unsignedBigInteger('id_nicho')->nullable();
            $table->foreign('id_nicho')->references('id_nicho')->on('nicho')->onDelete('set null');
            $table->date('fecha_fallecimiento');
            $table->date('fecha_entierro')->nullable();
            $table->enum('estado', ['en_nicho', 'en_bodega', 'incinerado', 'osario'])->default('en_nicho');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('difunto');
    }
};