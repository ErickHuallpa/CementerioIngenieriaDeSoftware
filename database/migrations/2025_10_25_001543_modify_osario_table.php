<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('osario', function (Blueprint $table) {
            $table->unsignedBigInteger('id_pabellon')->after('id_difunto');
            $table->foreign('id_pabellon')->references('id_pabellon')->on('pabellon')->onDelete('cascade');
            $table->integer('fila')->after('id_pabellon');
            $table->char('columna', 1)->after('fila');
            $table->enum('estado', ['disponible', 'ocupado', 'por_vencer', 'vencido'])->default('disponible')->after('columna');
            if (Schema::hasColumn('osario', 'posicion')) {
                $table->dropColumn('posicion');
            }
        });
    }

    public function down(): void
    {
        Schema::table('osario', function (Blueprint $table) {
            $table->dropForeign(['id_pabellon']);
            $table->dropColumn(['id_pabellon', 'fila', 'columna', 'estado']);
            $table->enum('posicion', ['superior', 'medio', 'inferior'])->nullable();
        });
    }
};
