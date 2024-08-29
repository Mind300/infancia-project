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
        Schema::table('meal_amounts', function (Blueprint $table) {
            $table->foreignId('meal_id')->constrained('meals')->onDelete('cascade');
        });
        Schema::table('schedules', function (Blueprint $table) {
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forigen_keys');
    }
};
