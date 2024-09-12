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
        Schema::create('payment_histories', function (Blueprint $table) {
            $table->id();
            $table->string('package_name');
            $table->bigInteger('transaction_id')->unique()->nullable();
            $table->boolean('success');
            $table->double('amount');
            $table->longText('card_token')->unique()->nullable();
            $table->date('intial_payment');
            $table->date('next_payment');
            $table->string('saved_card')->default(false);
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('nursery_id')->constrained('nurseries');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_histories');
    }
};
