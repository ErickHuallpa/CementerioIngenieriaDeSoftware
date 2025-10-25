<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contrato_alquiler', function (Blueprint $table) {
            $table->unsignedBigInteger('id_nicho')->nullable()->change();
            $table->unsignedBigInteger('id_osario')->nullable()->after('id_nicho');
            $table->foreign('id_osario')->references('id_osario')->on('osario')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('contrato_alquiler', function (Blueprint $table) {
            $table->dropForeign(['id_osario']);
            $table->dropColumn('id_osario');
        });
    }
};
