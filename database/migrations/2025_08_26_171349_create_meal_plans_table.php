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
        Schema::create('meal_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('image_url')->nullable();
            $table->integer('duration_days'); // 7, 14, 30 days etc.
            
            // Target audience
            $table->json('goals')->nullable(); // weight_loss, muscle_gain, etc.
            $table->json('dietary_preferences')->nullable(); // vegetarian, vegan, keto, etc.
            $table->json('allergen_free')->nullable();
            $table->integer('target_calories_min')->nullable();
            $table->integer('target_calories_max')->nullable();
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->default('easy');
            
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by_admin'); // in cents
            $table->foreign('created_by_admin')->references('id')->on('admins')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meal_plans');
    }
};
