<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE pabellon DROP CONSTRAINT IF EXISTS pabellon_tipo_check;");

        DB::statement("ALTER TABLE pabellon ALTER COLUMN tipo TYPE VARCHAR(50) USING tipo::VARCHAR;");

        DB::statement("ALTER TABLE pabellon
            ADD CONSTRAINT pabellon_tipo_check CHECK (tipo IN ('institucional', 'comun', 'osario'));");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE pabellon DROP CONSTRAINT IF EXISTS pabellon_tipo_check;");

        DB::statement("ALTER TABLE pabellon
            ADD CONSTRAINT pabellon_tipo_check CHECK (tipo IN ('institucional', 'comun'));");
    }
};
