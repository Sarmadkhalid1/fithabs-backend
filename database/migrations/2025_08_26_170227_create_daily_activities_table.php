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
        Schema::create('daily_activities', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->unsignedBigInteger('user_id');
            // Daily tracking
            $table->integer('steps')->default(0);
            $table->integer('calories_consumed')->default(0);
            $table->integer('calories_burned')->default(0);
            $table->decimal('water_intake', 4, 2)->default(0); // liters
            $table->integer('sleep_time')->default(0); // hours
            $table->decimal('daily_progress_percentage', 5, 2)->default(0);
            $table->integer('protein_goal')->default(0); // kcal
            $table->integer('carbs_goal')->default(0); // kcal
            $table->timestamps();
            $table->unique(['user_id', 'date']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');;

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_activities');
    }
};
