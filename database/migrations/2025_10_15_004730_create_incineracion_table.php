<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incineracion', function (Blueprint $table) {
            $table->id('id_incineracion');
            $table->unsignedBigInteger('id_difunto');
            $table->foreign('id_difunto')->references('id_difunto')->on('difunto')->onDelete('cascade');
            $table->unsignedBigInteger('id_responsable');
            $table->foreign('id_responsable')->references('id_persona')->on('persona')->onDelete('restrict');
            $table->date('fecha_incineracion');
            $table->enum('tipo', ['individual', 'colectiva'])->default('individual');
            $table->decimal('costo', 10, 2)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incineracion');
    }
};
