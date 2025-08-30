<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('campagne_participations', function (Blueprint $table) {
            // Supprimer les anciennes contraintes
            $table->dropForeign(['campagne_id']);
            $table->dropForeign(['user_id']);
        });

        Schema::table('campagne_participations', function (Blueprint $table) {
            // Recréer avec ON DELETE CASCADE
            $table->foreign('campagne_id')
                  ->references('id')
                  ->on('campagnes')
                  ->onDelete('cascade');

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('campagne_participations', function (Blueprint $table) {
            $table->dropForeign(['campagne_id']);
            $table->dropForeign(['user_id']);
        });

        Schema::table('campagne_participations', function (Blueprint $table) {
            $table->foreign('campagne_id')
                  ->references('id')
                  ->on('campagnes');

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users');
        });
    }
};
