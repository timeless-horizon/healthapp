<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('doctor_id')
                ->constrained('doctor_details') // Ensure this matches your actual table name
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->text('diagnosis')->nullable();
            $table->text('medications')->nullable();
            $table->text('test_result')->nullable();
            $table->text('test_image')->nullable();
            $table->text('extra_notes')->nullable();
            $table->date('conducted_on');
            $table->string('month', 20);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_records');
    }
};
