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
        Schema::create('appointment__department__doctors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')
                ->nullable()
                ->constrained('departments')
                ->onDelete('cascade');
            $table->foreignId('doctor_id')
                ->nullable()
                ->constrained('doctor_details')
                ->onDelete('cascade');

            $table->foreignId('appointment_id')
                ->nullable()
                ->constrained('appointments')
                ->onDelete('cascade');
            $table->dateTime('available_data_and_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointment__department__doctors');
    }
};
