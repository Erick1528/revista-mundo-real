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
        Schema::create('suggested_topics', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('section', [
                'destinations',
                'inspiring_stories',
                'social_events',
                'health_wellness',
                'gastronomy',
                'living_culture'
            ]);
            $table->json('resources')->nullable(); // Contenido del ContentEditor
            $table->enum('status', ['available', 'taken', 'requested', 'completed', 'cancelled'])->default('available');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('requested_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('taken_at')->nullable();
            $table->timestamp('requested_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Índices para mejorar rendimiento de consultas
            $table->index('status');
            $table->index('section');
            $table->index('assigned_to');
            $table->index('requested_by');
            $table->index('created_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suggested_topics');
    }
};
