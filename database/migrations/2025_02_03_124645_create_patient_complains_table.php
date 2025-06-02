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
        Schema::create('patient_complains', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');

            // Foreign key for department
            $table->foreignId('department_id')
                ->constrained('departments')
                ->onDelete('cascade');

            $table->text('message');
            $table->string('subject');

            $table->enum('status', ['pending', 'replied'])->default('pending');

            // Responded by (foreign key referencing customer care or doctor)
            $table->foreignId('responded_by')
                ->nullable()
                ->constrained('doctor_details')
                ->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_complains');
    }
};
