<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('rol', [
                'writer_junior',    // Crea artículos que requieren aprobación
                'writer_senior',    // Crea artículos sin aprobación
                'editor_junior',    // Edita portada que requiere verificación  
                'editor_senior',    // Edita portada sin verificación
                'editor_chief',     // Aprueba contenido de otros + edita
                'moderator',        // Modera comentarios y contenido
                'administrator'     // Control total del sistema
            ])->default('writer_junior');

            $table->text('description')->nullable();
            $table->string('avatar')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('rol');
        });
    }
};
