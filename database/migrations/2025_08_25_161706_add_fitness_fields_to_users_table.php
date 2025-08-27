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
        Schema::table('users', function (Blueprint $table) {
            // Profile data
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('password');

            // weight & height with units
            $table->decimal('weight', 6, 2)->nullable()->after('gender');
            $table->enum('weight_unit', ['kg', 'lb'])->default('kg')->after('weight');
            $table->decimal('height', 6, 2)->nullable()->after('weight_unit');
            $table->enum('height_unit', ['cm', 'ft'])->default('cm')->after('height');

            // goals & activity
            $table->enum('goal', ['lose_weight', 'gain_weight', 'maintain_weight', 'build_muscle'])->nullable()->after('height_unit');
            $table->enum('activity_level', ['sedentary', 'light', 'moderate', 'very_active'])->nullable()->after('goal');

            // daily targets
            $table->integer('daily_calorie_goal')->default(2000)->after('activity_level');
            $table->integer('daily_steps_goal')->default(10000)->after('daily_calorie_goal');
            $table->decimal('daily_water_goal', 5, 2)->default(2.5)->after('daily_steps_goal'); // liters
        });
    }

    /**
     * Reverse the migrations.
     */
   public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'gender',
                'weight', 'weight_unit',
                'height', 'height_unit',
                'goal',
                'activity_level',
                'daily_calorie_goal',
                'daily_steps_goal',
                'daily_water_goal',
            ]);
        });
    }
};
