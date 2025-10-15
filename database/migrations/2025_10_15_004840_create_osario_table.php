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
            $table->unsignedBigInteger('id_difunto');
            $table->foreign('id_difunto')->references('id_difunto')->on('difunto')->onDelete('cascade');
            $table->date('fecha_ingreso');
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
