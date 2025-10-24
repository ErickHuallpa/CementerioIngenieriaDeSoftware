<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE difunto DROP CONSTRAINT IF EXISTS difunto_estado_check;");
        DB::statement("ALTER TABLE difunto
            ADD CONSTRAINT difunto_estado_check CHECK (
                estado IN ('en_nicho','en_bodega','incinerado','osario','registrado','retirado')
            );");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE difunto DROP CONSTRAINT IF EXISTS difunto_estado_check;");

        DB::statement("ALTER TABLE difunto
            ADD CONSTRAINT difunto_estado_check CHECK (
                estado IN ('en_nicho','en_bodega','incinerado','osario','registrado')
            );");
    }
};
