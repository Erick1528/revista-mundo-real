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
        Schema::create('ads', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->string('name');
            $table->string('slug')->unique();
            $table->json('content')->nullable();
            $table->string('redirect_url', 2048)->nullable();
            $table->enum('status', ['draft', 'review', 'published', 'denied'])->default('draft');
            $table->string('visibility')->default('public')->nullable(); // public, private
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('advertiser_id')->nullable()->constrained('advertisers')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ads');
    }
};
