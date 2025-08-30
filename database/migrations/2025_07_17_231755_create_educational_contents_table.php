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
        Schema::create('educational_contents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->enum('type', ['infographic', 'video', 'quiz', 'checklist', 'article', 'testimony']);
            $table->enum('category', ['discovery', 'preparation', 'quiz', 'testimonials', 'progress']);
            $table->integer('difficulty')->default(1);
            $table->integer('points')->default(10);
            $table->string('media_path')->nullable();
            $table->json('quiz_data')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('educational_contents');
    }
};
