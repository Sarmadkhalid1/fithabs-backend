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
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string('image_url')->nullable();
            $table->enum('meal_type', ['breakfast', 'lunch', 'dinner', 'snack']);
            $table->integer('prep_time_minutes')->nullable();
            $table->integer('cook_time_minutes')->nullable();
            $table->integer('servings')->default(1);
            
            // Nutritional info per serving
            $table->integer('calories_per_serving');
            $table->decimal('protein_per_serving', 5, 2)->nullable();
            $table->decimal('carbs_per_serving', 5, 2)->nullable();
            $table->decimal('fat_per_serving', 5, 2)->nullable();
            $table->decimal('fiber_per_serving', 5, 2)->nullable();
            $table->decimal('sugar_per_serving', 5, 2)->nullable();
            
            $table->text('ingredients');
            $table->text('instructions');
            
            // Dietary tags for filtering
            $table->json('dietary_tags')->nullable(); // vegetarian, vegan, keto, etc.
            $table->json('allergen_info')->nullable();
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->default('easy');
            
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by_admin');
            $table->foreign('created_by_admin')->references('id')->on('admins')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
