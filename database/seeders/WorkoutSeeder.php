<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Workout;

class WorkoutSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data (use delete instead of truncate to avoid foreign key issues)
        Workout::query()->delete();
        
        // Working image URLs from Unsplash
        $imageUrls = [
            "https://images.unsplash.com/photo-1517836357463-d25dfeac3438",
            "https://images.unsplash.com/photo-1534438327276-14e5300c3a48",
            "https://images.unsplash.com/photo-1518611012118-696072aa579a",
            "https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b"
        ];

        $workouts = [
            [
                'name' => 'Upper Body Strength',
                'description' => 'Build upper body strength with targeted exercises for arms, shoulders, and chest.',
                'image_url' => $imageUrls[0],
                'difficulty' => 'intermediate',
                'type' => 'upper_body',
                'duration_minutes' => 45,
                'calories_per_session' => 300,
                'equipment_needed' => ['dumbbells', 'bench'],
                'tags' => ['strength', 'muscle building', 'upper body'],
                'is_featured' => true,
                'is_active' => true,
                'created_by_admin' => 1,
            ],
            [
                'name' => 'Lower Body Power',
                'description' => 'Strengthen your legs and glutes with powerful lower body exercises.',
                'image_url' => $imageUrls[1],
                'difficulty' => 'beginner',
                'type' => 'lower_body',
                'duration_minutes' => 30,
                'calories_per_session' => 250,
                'equipment_needed' => ['bodyweight'],
                'tags' => ['strength', 'legs', 'glutes'],
                'is_featured' => false,
                'is_active' => true,
                'created_by_admin' => 1,
            ],
            [
                'name' => 'Full Body HIIT',
                'description' => 'High-intensity interval training targeting your entire body for maximum results.',
                'image_url' => $imageUrls[2],
                'difficulty' => 'advanced',
                'type' => 'full_body',
                'duration_minutes' => 35,
                'calories_per_session' => 400,
                'equipment_needed' => ['bodyweight', 'kettlebell'],
                'tags' => ['hiit', 'cardio', 'full body', 'fat loss'],
                'is_featured' => true,
                'is_active' => true,
                'created_by_admin' => 1,
            ],
            [
                'name' => 'Cardio Blast',
                'description' => 'Get your heart pumping with this high-energy cardio workout.',
                'image_url' => $imageUrls[3],
                'difficulty' => 'intermediate',
                'type' => 'cardio',
                'duration_minutes' => 25,
                'calories_per_session' => 350,
                'equipment_needed' => ['bodyweight'],
                'tags' => ['cardio', 'fat loss', 'endurance'],
                'is_featured' => false,
                'is_active' => true,
                'created_by_admin' => 1,
            ],
            [
                'name' => 'Flexibility & Mobility',
                'description' => 'Improve your flexibility and mobility with gentle stretching exercises.',
                'image_url' => $imageUrls[0], // Reusing first image
                'difficulty' => 'beginner',
                'type' => 'flexibility',
                'duration_minutes' => 20,
                'calories_per_session' => 80,
                'equipment_needed' => ['yoga mat'],
                'tags' => ['flexibility', 'mobility', 'recovery'],
                'is_featured' => false,
                'is_active' => true,
                'created_by_admin' => 1,
            ]
        ];

        foreach ($workouts as $workoutData) {
            Workout::create($workoutData);
        }

        $this->command->info('Workout seeding completed with working image URLs!');
    }
}
