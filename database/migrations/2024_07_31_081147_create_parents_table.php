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
        Schema::create('parents', function (Blueprint $table) {
            $table->id();
			$table->string('father_name')->nullable();
			$table->string('father_mobile')->nullable();
			$table->string('father_job')->nullable();
			$table->string('mother_name')->nullable();
			$table->string('mother_mobile')->nullable();
			$table->string('mother_job')->nullable();
			$table->string('emergency_phone')->nullable();
            $table->foreignId('nursery_id')->constrained('nurseries')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
			$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parents');
    }
};
