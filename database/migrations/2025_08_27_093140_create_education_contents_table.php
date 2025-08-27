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
        Schema::create('education_contents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('created_by_admin');
            $table->string('title');
            $table->text('description');
            $table->string('image_url')->nullable();
            $table->text('content');
            $table->enum('content_type', ['article', 'video', 'infographic', 'guide'])->default('article');
            $table->string('video_url')->nullable();
            $table->enum('category', ['training', 'nutrition', 'wellness', 'recovery', 'mental_health']);
            $table->json('tags')->nullable(); // For better search and filtering
            $table->integer('read_time_minutes')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->foreign('created_by_admin')->references('id')->on('admins')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('education_contents');
    }
};
