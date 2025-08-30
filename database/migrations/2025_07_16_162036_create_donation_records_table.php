<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDonationRecordsTable extends Migration
{
    public function up()
    {
        Schema::create('donation_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('centre_sante_id')->constrained('centre_santes');
            $table->date('donation_date');
            $table->integer('volume_ml');
            $table->string('blood_type');
            $table->text('medical_notes')->nullable();
            $table->string('certificate_path')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('donation_records');
    }
}
