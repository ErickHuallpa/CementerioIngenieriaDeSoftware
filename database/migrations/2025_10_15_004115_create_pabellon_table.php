<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pabellon', function (Blueprint $table) {
            $table->id('id_pabellon');
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->enum('tipo', ['institucional', 'comun', 'osario'])
                  ->default('comun');

            $table->string('institucion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pabellon');
    }
};
