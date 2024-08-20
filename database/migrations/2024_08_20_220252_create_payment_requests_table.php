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
        Schema::create('payment_requests', function (Blueprint $table) {
            $table->id();
            $table->string('service')->nullable();
            $table->decimal('amount', 10, 2);
            $table->boolean('is_paid')->default(false);
            $table->dateTime('paid_at')->nullable(); 
            $table->foreignId('kid_id')->constrained();
            $table->foreignId('nursery_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_requests');
    }
};
