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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->string('title');
            $table->string('subtitle');
            $table->string('attribution')->nullable(); // Crédito/fuente de la información
            $table->text('summary')->nullable(); // Resumen corto para preview
            $table->string('slug')->unique();
            $table->string('image_path');
            $table->enum('visibility', ['public', 'private'])->default('private');
            $table->enum('status', ['draft', 'review', 'published', 'denied'])->default('draft');
            $table->timestamp('published_at')->nullable(); // Para programar publicaciones
            $table->enum('section', [
                'destinations',
                'inspiring_stories',
                'social_events',
                'health_wellness',
                'gastronomy',
                'living_culture'
            ]);
            $table->json('tags');
            $table->json('related_articles')->nullable();
            $table->json('content');
            $table->integer('view_count')->default(0); // Contador de vistas
            $table->integer('reading_time')->nullable(); // Tiempo estimado de lectura en minutos
            $table->string('meta_description')->nullable(); // SEO
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
