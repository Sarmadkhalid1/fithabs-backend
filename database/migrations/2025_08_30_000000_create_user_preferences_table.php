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
        Schema::create('user_preferences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            
            // Dietary preferences
            $table->json('dietary_preferences')->nullable(); // vegetarian, keto, vegan, paleo, gluten-free
            
            // Allergies
            $table->json('allergies')->nullable(); // nuts, eggs, dairy, shellfish
            
            // Meal planning preferences
            $table->json('meal_types')->nullable(); // breakfast, lunch, dinner, snack
            $table->enum('caloric_goal', ['less_than_1500', '1500_2000', 'more_than_2000', 'not_sure'])->nullable();
            $table->enum('cooking_time_preference', ['less_than_15', '15_30', 'more_than_30'])->nullable();
            $table->enum('serving_preference', ['1', '2', '3_5', 'more_than_4'])->nullable();
            
            $table->timestamps();
            
            // One preference record per user
            $table->unique('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_preferences');
    }
};
