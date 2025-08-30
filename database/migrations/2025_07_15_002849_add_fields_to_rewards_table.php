<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rewards', function (Blueprint $table) {
            $table->string('image_url')->nullable()->after('cost');
            $table->boolean('is_active')->default(true)->after('image_url');
            $table->integer('required_level')->default(1)->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('rewards', function (Blueprint $table) {
            $table->dropColumn(['image_url', 'is_active', 'required_level']);
        });
    }
};
