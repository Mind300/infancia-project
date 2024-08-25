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
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('address')->nullable();
            $table->bigInteger('branches_number')->default(0);
            $table->decimal('start_fees')->default(0)->nullable();
            $table->bigInteger('classes_Number')->default(0);
            $table->bigInteger('children_number')->default(0);
            $table->bigInteger('employees_number')->default(0);
            $table->longText('services')->nullable();
            $table->longText('about')->nullable();
            $table->integer('rateing')->default(0);
            $table->enum('status', array('pending', 'accept', 'decline'))->default('pending');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
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
