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
        Schema::create('nurseries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('province')->nullable();
            $table->string('address')->nullable();
            $table->bigInteger('branches_number')->default('0');
            $table->bigInteger('classes_Number')->default('0');
            $table->bigInteger('kids_Number')->default('0');
            $table->bigInteger('employees_number')->default('0');
            $table->longText('about')->nullable();
            $table->decimal('start_fees')->nullable();
            $table->longText('services')->nullable();
            $table->integer('rateing')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nurseries');
    }
};
