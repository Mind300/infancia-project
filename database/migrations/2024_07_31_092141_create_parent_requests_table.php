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
        Schema::create('parent_requests', function (Blueprint $table) {
            $table->id();
            $table->string('message');
			$table->foreignId('user_id')->constrained('users');
			$table->foreignId('recipient_id')->constrained('users');
			$table->foreignId('nursery_id')->constrained('nurseries');
			$table->boolean('seen')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
