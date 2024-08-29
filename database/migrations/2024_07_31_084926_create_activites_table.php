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
        Schema::create('activites', function (Blueprint $table) {
            $table->id();

            $table->foreignId('kid_id')->constrained('kids')->onDelete('cascade');
            $table->foreignId('nursery_id')->constrained('nurseries');

            $table->string('napping')->nullable();
            $table->integer('mood')->nullable();
            $table->string('comment')->nullable();

            $table->integer('diaper')->default(0);
            $table->integer('potty')->default(0);
            $table->integer('toilet')->default(0);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activites');
    }
};
