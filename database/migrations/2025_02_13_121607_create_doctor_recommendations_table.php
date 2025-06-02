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
        Schema::create('doctor_recommendations', function (Blueprint $table) {
            $table->id();
            $table->string('complain');
            $table->string('medication');
            $table->string('dosage');

            $table->foreignId('patient_id')
                ->constrained('users')
                ->onDelete('cascade');

            $table->foreignId('doctor_id')
                ->constrained('doctor_details');

            $table->foreignId('patient_complain_id')
                ->constrained('patient_complains')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_recommendations');
    }
};
