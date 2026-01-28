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
        Schema::table('cover_articles', function (Blueprint $table) {
            $table->boolean('is_active')->default(false)->after('visibility');
            $table->foreignId('activated_by')->nullable()->after('is_active')->constrained('users')->nullOnDelete();
            $table->timestamp('activated_at')->nullable()->after('activated_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cover_articles', function (Blueprint $table) {
            $table->dropForeign(['activated_by']);
            $table->dropColumn(['is_active', 'activated_by', 'activated_at']);
        });
    }
};
