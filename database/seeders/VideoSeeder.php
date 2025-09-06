<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Video;

class VideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing videos (use delete instead of truncate to avoid foreign key issues)
        Video::query()->delete();

        // Create sample placeholder videos
        $videos = [
            [
                'title' => 'Push-ups Exercise Demo',
                'description' => 'Complete guide on how to perform push-ups with proper form',
                'filename' => 'pushups-demo.mp4',
                'path' => 'pushups-demo.mp4',
                'url' => url('/storage/videos/pushups-demo.mp4'),
                'mime_type' => 'video/mp4',
                'file_size' => 1024000, // 1MB placeholder
                'duration' => 120, // 2 minutes
                'category' => 'exercise',
                'tags' => ['upper body', 'bodyweight', 'strength'],
                'is_active' => true,
                'uploaded_by' => 1,
            ],
            [
                'title' => 'Squats Exercise Tutorial',
                'description' => 'Learn proper squat form and technique',
                'filename' => 'squats-tutorial.mp4',
                'path' => 'squats-tutorial.mp4',
                'url' => url('/storage/videos/squats-tutorial.mp4'),
                'mime_type' => 'video/mp4',
                'file_size' => 1536000, // 1.5MB placeholder
                'duration' => 180, // 3 minutes
                'category' => 'exercise',
                'tags' => ['lower body', 'bodyweight', 'strength'],
                'is_active' => true,
                'uploaded_by' => 1,
            ],
            [
                'title' => 'Plank Hold Technique',
                'description' => 'Master the plank hold for core strength',
                'filename' => 'plank-technique.mp4',
                'path' => 'plank-technique.mp4',
                'url' => url('/storage/videos/plank-technique.mp4'),
                'mime_type' => 'video/mp4',
                'file_size' => 2048000, // 2MB placeholder
                'duration' => 150, // 2.5 minutes
                'category' => 'exercise',
                'tags' => ['core', 'bodyweight', 'stability'],
                'is_active' => true,
                'uploaded_by' => 1,
            ],
            [
                'title' => 'Jumping Jacks Cardio',
                'description' => 'High-energy jumping jacks for cardio workout',
                'filename' => 'jumping-jacks.mp4',
                'path' => 'jumping-jacks.mp4',
                'url' => url('/storage/videos/jumping-jacks.mp4'),
                'mime_type' => 'video/mp4',
                'file_size' => 1792000, // 1.75MB placeholder
                'duration' => 90, // 1.5 minutes
                'category' => 'exercise',
                'tags' => ['cardio', 'bodyweight', 'full body'],
                'is_active' => true,
                'uploaded_by' => 1,
            ],
            [
                'title' => 'Full Body HIIT Workout',
                'description' => 'Complete 20-minute HIIT workout routine',
                'filename' => 'hiit-workout.mp4',
                'path' => 'hiit-workout.mp4',
                'url' => url('/storage/videos/hiit-workout.mp4'),
                'mime_type' => 'video/mp4',
                'file_size' => 5120000, // 5MB placeholder
                'duration' => 1200, // 20 minutes
                'category' => 'workout',
                'tags' => ['hiit', 'full body', 'cardio', 'strength'],
                'is_active' => true,
                'uploaded_by' => 1,
            ],
            [
                'title' => 'Yoga Flexibility Routine',
                'description' => 'Gentle yoga routine for flexibility and relaxation',
                'filename' => 'yoga-flexibility.mp4',
                'path' => 'yoga-flexibility.mp4',
                'url' => url('/storage/videos/yoga-flexibility.mp4'),
                'mime_type' => 'video/mp4',
                'file_size' => 3072000, // 3MB placeholder
                'duration' => 900, // 15 minutes
                'category' => 'workout',
                'tags' => ['yoga', 'flexibility', 'stretching', 'relaxation'],
                'is_active' => true,
                'uploaded_by' => 1,
            ],
        ];

        foreach ($videos as $videoData) {
            Video::create($videoData);
        }

        $this->command->info('Video seeding completed with placeholder videos!');
    }
}
