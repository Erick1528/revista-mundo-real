<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Amplía la columna slug para evitar "Data too long" con títulos/subtítulos largos.
     * Solo se ejecuta en MySQL; SQLite no tiene límite práctico en string.
     */
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }
        DB::statement('ALTER TABLE articles MODIFY slug VARCHAR(500) NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }
        DB::statement('ALTER TABLE articles MODIFY slug VARCHAR(255) NOT NULL');
    }
};
