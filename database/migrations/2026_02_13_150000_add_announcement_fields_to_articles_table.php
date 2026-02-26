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
        Schema::table('articles', function (Blueprint $table) {
            $table->boolean('is_announcement')->default(false)->after('section');
            $table->foreignId('advertiser_id')->nullable()->after('is_announcement')->constrained('advertisers')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropForeign(['advertiser_id']);
            $table->dropColumn(['is_announcement', 'advertiser_id']);
        });
    }
};
