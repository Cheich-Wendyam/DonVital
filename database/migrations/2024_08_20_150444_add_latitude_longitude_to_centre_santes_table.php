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
        Schema::table('centre_santes', function (Blueprint $table) {
            $table->decimal('latitude', 10, 7)->nullable(); // Latitude avec une précision de 7 décimales
            $table->decimal('longitude', 10, 7)->nullable(); // Longitude avec une préCISION de 7 décimales
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('centre_santes', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude']);
        });
    }
};
