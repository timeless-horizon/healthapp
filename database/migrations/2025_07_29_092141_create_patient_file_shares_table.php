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
        Schema::create('patient_file_shares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_file_id')
                ->constrained('patient_files')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('patient_id')
                ->constrained('users')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('doctor_id')
                ->constrained('users')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->timestamp('shared_at');
            $table->timestamp('access_expires_at')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Ensure a file can only be shared once per doctor (prevent duplicates)
            $table->unique(['patient_file_id', 'doctor_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_file_shares');
    }
};
