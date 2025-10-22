<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Para PostgreSQL, primero eliminamos el check constraint actual
        DB::statement("ALTER TABLE difunto DROP CONSTRAINT IF EXISTS difunto_estado_check;");

        // Luego cambiamos la columna a varchar y dejamos el check con el nuevo valor 'registrado'
        DB::statement("ALTER TABLE difunto 
            ALTER COLUMN estado TYPE VARCHAR(50) 
            USING estado::VARCHAR;");

        DB::statement("ALTER TABLE difunto
            ADD CONSTRAINT difunto_estado_check CHECK (estado IN ('en_nicho','en_bodega','incinerado','osario','registrado'));");
    }

    public function down(): void
    {
        // Revertimos a la lista original
        DB::statement("ALTER TABLE difunto DROP CONSTRAINT IF EXISTS difunto_estado_check;");

        DB::statement("ALTER TABLE difunto
            ADD CONSTRAINT difunto_estado_check CHECK (estado IN ('en_nicho','en_bodega','incinerado','osario'));");
    }
};
