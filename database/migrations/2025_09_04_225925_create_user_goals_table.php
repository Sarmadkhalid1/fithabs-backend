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
        Schema::create('user_goals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('steps')->nullable()->comment('Daily step goal (1000-20000)');
            $table->decimal('calories', 8, 2)->nullable()->comment('Daily calorie goal (64-643 kcal)');
            $table->decimal('water', 4, 2)->nullable()->comment('Daily water goal in liters (1-5 liters)');
            $table->timestamps();
            
            // Ensure one goal per user
            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_goals');
    }
};
