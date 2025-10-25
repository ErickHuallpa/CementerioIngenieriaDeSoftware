<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('osario', function (Blueprint $table) {
            if (Schema::hasColumn('osario', 'id_difunto')) {
                $table->dropForeign(['id_difunto']);
            }
        });

        Schema::table('osario', function (Blueprint $table) {
            if (Schema::hasColumn('osario', 'id_difunto')) {
                $table->unsignedBigInteger('id_difunto')->nullable()->change();
                $table->foreign('id_difunto')->references('id_difunto')->on('difunto')->onDelete('cascade');
            }

            if (Schema::hasColumn('osario', 'fecha_ingreso')) {
                $table->date('fecha_ingreso')->nullable()->change();
            }

            if (Schema::hasColumn('osario', 'fecha_salida')) {
                $table->date('fecha_salida')->nullable()->change();
            }

            if (Schema::hasColumn('osario', 'costo')) {
                $table->decimal('costo', 10, 2)->nullable()->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('osario', function (Blueprint $table) {
            if (Schema::hasColumn('osario', 'id_difunto')) {
                $table->dropForeign(['id_difunto']);
                $table->unsignedBigInteger('id_difunto')->nullable(false)->change();
                $table->foreign('id_difunto')->references('id_difunto')->on('difunto')->onDelete('cascade');
            }

            if (Schema::hasColumn('osario', 'fecha_ingreso')) {
                $table->date('fecha_ingreso')->nullable(false)->change();
            }

            if (Schema::hasColumn('osario', 'fecha_salida')) {
                $table->date('fecha_salida')->nullable(false)->change();
            }

            if (Schema::hasColumn('osario', 'costo')) {
                $table->decimal('costo', 10, 2)->nullable(false)->change();
            }
        });
    }
};
