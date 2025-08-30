<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reward_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reward_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamp('claimed_at')->nullable();
            $table->timestamps();

            $table->unique(['reward_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reward_user');
    }
};
