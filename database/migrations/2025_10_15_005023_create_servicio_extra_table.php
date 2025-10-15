<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('servicio_extra', function (Blueprint $table) {
            $table->id('id_servicio');
            $table->unsignedBigInteger('id_difunto');
            $table->foreign('id_difunto')->references('id_difunto')->on('difunto')->onDelete('cascade');
            $table->unsignedBigInteger('id_trabajador')->nullable();
            $table->foreign('id_trabajador')->references('id_persona')->on('persona')->onDelete('set null');
            $table->enum('tipo_servicio', ['traslado', 'mantenimiento', 'incineracion', 'osario']);
            $table->date('fecha_servicio');
            $table->decimal('costo', 10, 2)->nullable();
            $table->text('observaciones')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('servicio_extra');
    }
};
