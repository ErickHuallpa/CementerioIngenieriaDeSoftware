<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('osario', function (Blueprint $table) {
            $table->dropForeign(['id_difunto']);
        });

        Schema::table('osario', function (Blueprint $table) {
            $table->unsignedBigInteger('id_difunto')->nullable()->change();
            $table->foreign('id_difunto')->references('id_difunto')->on('difunto')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('osario', function (Blueprint $table) {
            $table->dropForeign(['id_difunto']);
        });

        Schema::table('osario', function (Blueprint $table) {
            $table->unsignedBigInteger('id_difunto')->nullable(false)->change();
            $table->foreign('id_difunto')->references('id_difunto')->on('difunto')->onDelete('cascade');
        });
    }
};
