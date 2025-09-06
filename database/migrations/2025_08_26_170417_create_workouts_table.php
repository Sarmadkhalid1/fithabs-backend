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
        Schema::create('workouts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string('image_url')->nullable();
            $table->enum('difficulty', ['beginner', 'intermediate', 'advanced']);
            $table->enum('type', ['upper_body', 'lower_body', 'full_body', 'cardio', 'flexibility']);
            $table->integer('duration_minutes')->nullable();
            $table->integer('calories_per_session')->nullable();
            $table->json('equipment_needed')->nullable(); // Array of equipment
            $table->json('tags')->nullable(); // For better search and filtering
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);    
            $table->unsignedBigInteger('created_by_admin')->nullable();
            $table->foreign('created_by_admin')->references('id')->on('admin_users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workouts');
    }
};
