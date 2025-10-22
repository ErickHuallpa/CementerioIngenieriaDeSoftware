<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Agrega el nuevo valor 'registrado' al enum de PostgreSQL
        DB::statement("ALTER TYPE enum_difunto_estado ADD VALUE IF NOT EXISTS 'registrado'");
    }

    public function down(): void
    {
        // PostgreSQL no permite eliminar un valor de enum de manera sencilla
        // En caso de rollback, se podría crear un tipo nuevo y migrar datos, pero lo dejamos así
    }
};
