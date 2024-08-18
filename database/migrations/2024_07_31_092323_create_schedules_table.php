<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**`
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->longText('content')->nullable();
            $table->enum('days', array('Sat','Sun','Mon','Tue','Wed','Thu','Fri'));
			$table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
			$table->foreignId('nursery_id')->constrained('nurseries')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
