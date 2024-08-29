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
        Schema::create('kids', function (Blueprint $table) {
            $table->id();
			$table->string('kid_name');
			$table->enum('gender', array('boy', 'girl'));
			$table->date('birthdate');
			$table->boolean('has_medical_case')->default(0);
            $table->foreignId('parent_id')->constained('parents');
            $table->foreignId('class_id')->constained('parents');
            $table->foreignId('nursery_id')->constained('nurseries')->onDelete('cascade');
			$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kids');
    }
};
