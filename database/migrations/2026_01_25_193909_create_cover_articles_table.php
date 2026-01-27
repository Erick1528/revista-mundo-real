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
        Schema::create('cover_articles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->nullable()->comment('e.g. "Portada enero 2025"');
            $table->json('main_articles')->comment('Article IDs in order, e.g. [1,2,3,4]');
            $table->json('mid_articles')->comment('Article IDs in order, e.g. [5,6,7]');
            $table->json('latest_articles')->comment('Article IDs in order, e.g. [8,9,10,11]');
            $table->timestamp('scheduled_at')->nullable()->comment('When this cover should go live');
            $table->timestamp('ends_at')->nullable()->comment('When this cover should be replaced');
            $table->enum('status', ['draft', 'pending_review', 'published', 'archived'])->default('pending_review')->comment('draft=edición; pending_review=pendiente de aprobación; published=aprobada/publicada; archived=archivada');
            $table->enum('visibility', ['public', 'private'])->default('public')->comment('Lo que elige el usuario: quién puede ver la portada');
            $table->text('notes')->nullable()->comment('Internal editor notes');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('edited_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('published_at')->nullable()->comment('When it was published');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cover_articles');
    }
};
