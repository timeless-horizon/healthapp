<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            //
            $table->string('status')->nullable();
        });

        Schema::table('appointment__department__doctors', function (Blueprint $table) {
            $table->string('status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            //
            $table->dropColumn(['status']);
        });

        Schema::table('appointment__department__doctors', function (Blueprint $table) {
            //
            $table->dropColumn(['status']);

        });
    }
};
