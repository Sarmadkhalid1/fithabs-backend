<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Workout;
use App\Models\Exercise;

class ExerciseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing exercises
        Exercise::truncate();
        
        // Get existing workouts
        $workouts = Workout::all();
        
        if ($workouts->isEmpty()) {
            $this->command->info('No workouts found. Please run WorkoutSeeder first.');
            return;
        }

        // Get local video URLs from the videos table
        $videos = \App\Models\Video::where('is_active', true)->get();
        $videoUrls = $videos->pluck('url')->toArray();
        
        // If no videos exist, use placeholder URLs
        if (empty($videoUrls)) {
            $videoUrls = [
                url('/storage/videos/pushups-demo.mp4'),
                url('/storage/videos/squats-tutorial.mp4'),
                url('/storage/videos/plank-technique.mp4'),
                url('/storage/videos/jumping-jacks.mp4'),
                url('/storage/videos/hiit-workout.mp4'),
            ];
        }

        // Working image URLs from Unsplash
        $imageUrls = [
            "https://images.unsplash.com/photo-1517836357463-d25dfeac3438",
            "https://images.unsplash.com/photo-1534438327276-14e5300c3a48",
            "https://images.unsplash.com/photo-1518611012118-696072aa579a",
            "https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b"
        ];

        // Define exercise sets for different workout types
        $exerciseData = [
            'upper_body' => [
                [
                    'name' => 'Push-ups',
                    'instructions' => 'Start in a plank position with hands shoulder-width apart. Lower your body until chest nearly touches the floor, then push back up.',
                    'video_url' => $videoUrls[0],
                    'image_url' => $imageUrls[0],
                    'duration_seconds' => 45,
                    'repetitions' => 15,
                    'sets' => 3,
                    'rest_seconds' => 30,
                    'order' => 1
                ],
                [
                    'name' => 'Dumbbell Rows',
                    'instructions' => 'Hold dumbbells with arms extended. Pull elbows back, squeezing shoulder blades together.',
                    'video_url' => $videoUrls[1],
                    'image_url' => $imageUrls[1],
                    'duration_seconds' => 60,
                    'repetitions' => 12,
                    'sets' => 3,
                    'rest_seconds' => 45,
                    'order' => 2
                ],
                [
                    'name' => 'Shoulder Press',
                    'instructions' => 'Hold weights at shoulder level. Press straight up overhead, then lower back to start.',
                    'video_url' => $videoUrls[2],
                    'image_url' => $imageUrls[2],
                    'duration_seconds' => 50,
                    'repetitions' => 10,
                    'sets' => 3,
                    'rest_seconds' => 60,
                    'order' => 3
                ],
                [
                    'name' => 'Tricep Dips',
                    'instructions' => 'Sit on edge of chair, hands gripping the edge. Lower body by bending elbows, then push back up.',
                    'video_url' => $videoUrls[3],
                    'image_url' => $imageUrls[3],
                    'duration_seconds' => 40,
                    'repetitions' => 12,
                    'sets' => 2,
                    'rest_seconds' => 30,
                    'order' => 4
                ]
            ],
            'lower_body' => [
                [
                    'name' => 'Squats',
                    'instructions' => 'Stand with feet shoulder-width apart. Lower by bending knees and hips, then return to standing.',
                    'video_url' => $videoUrls[4],
                    'image_url' => $imageUrls[0],
                    'duration_seconds' => 60,
                    'repetitions' => 20,
                    'sets' => 3,
                    'rest_seconds' => 45,
                    'order' => 1
                ],
                [
                    'name' => 'Lunges',
                    'instructions' => 'Step forward into lunge position, lower back knee toward ground, then return to start.',
                    'video_url' => $videoUrls[0],
                    'image_url' => $imageUrls[1],
                    'duration_seconds' => 50,
                    'repetitions' => 12,
                    'sets' => 3,
                    'rest_seconds' => 30,
                    'order' => 2
                ],
                [
                    'name' => 'Glute Bridges',
                    'instructions' => 'Lie on back with knees bent. Lift hips up by squeezing glutes, then lower back down.',
                    'video_url' => $videoUrls[1],
                    'image_url' => $imageUrls[2],
                    'duration_seconds' => 45,
                    'repetitions' => 15,
                    'sets' => 3,
                    'rest_seconds' => 30,
                    'order' => 3
                ],
                [
                    'name' => 'Calf Raises',
                    'instructions' => 'Stand tall, rise up on toes by lifting heels, then slowly lower back down.',
                    'video_url' => $videoUrls[2],
                    'image_url' => $imageUrls[3],
                    'duration_seconds' => 40,
                    'repetitions' => 20,
                    'sets' => 2,
                    'rest_seconds' => 20,
                    'order' => 4
                ]
            ],
            'cardio' => [
                [
                    'name' => 'Jumping Jacks',
                    'instructions' => 'Jump while spreading legs and raising arms overhead, then return to start position.',
                    'video_url' => $videoUrls[3],
                    'image_url' => $imageUrls[0],
                    'duration_seconds' => 60,
                    'repetitions' => null,
                    'sets' => 3,
                    'rest_seconds' => 30,
                    'order' => 1
                ],
                [
                    'name' => 'High Knees',
                    'instructions' => 'Run in place while lifting knees as high as possible toward chest.',
                    'video_url' => $videoUrls[4],
                    'image_url' => $imageUrls[1],
                    'duration_seconds' => 45,
                    'repetitions' => null,
                    'sets' => 3,
                    'rest_seconds' => 15,
                    'order' => 2
                ],
                [
                    'name' => 'Burpees',
                    'instructions' => 'Drop to squat, kick back to plank, do push-up, jump feet back to squat, then jump up.',
                    'video_url' => $videoUrls[0],
                    'image_url' => $imageUrls[2],
                    'duration_seconds' => 30,
                    'repetitions' => 10,
                    'sets' => 3,
                    'rest_seconds' => 60,
                    'order' => 3
                ],
                [
                    'name' => 'Mountain Climbers',
                    'instructions' => 'Start in plank position, alternate bringing knees to chest in running motion.',
                    'video_url' => $videoUrls[1],
                    'image_url' => $imageUrls[3],
                    'duration_seconds' => 45,
                    'repetitions' => null,
                    'sets' => 3,
                    'rest_seconds' => 30,
                    'order' => 4
                ]
            ],
            'full_body' => [
                [
                    'name' => 'Plank',
                    'instructions' => 'Hold a straight line from head to heels, engaging core muscles throughout.',
                    'video_url' => $videoUrls[2],
                    'image_url' => $imageUrls[0],
                    'duration_seconds' => 60,
                    'repetitions' => null,
                    'sets' => 3,
                    'rest_seconds' => 30,
                    'order' => 1
                ],
                [
                    'name' => 'Squat to Press',
                    'instructions' => 'Hold weights, squat down, then stand while pressing weights overhead.',
                    'video_url' => $videoUrls[3],
                    'image_url' => $imageUrls[1],
                    'duration_seconds' => 50,
                    'repetitions' => 12,
                    'sets' => 3,
                    'rest_seconds' => 45,
                    'order' => 2
                ],
                [
                    'name' => 'Deadlifts',
                    'instructions' => 'Hold weights, hinge at hips to lower weights toward ground, then return to standing.',
                    'video_url' => $videoUrls[4],
                    'image_url' => $imageUrls[2],
                    'duration_seconds' => 60,
                    'repetitions' => 10,
                    'sets' => 3,
                    'rest_seconds' => 60,
                    'order' => 3
                ]
            ],
            'flexibility' => [
                [
                    'name' => 'Child\'s Pose',
                    'instructions' => 'Kneel on floor, sit back on heels, then fold forward with arms extended.',
                    'video_url' => $videoUrls[0],
                    'image_url' => $imageUrls[3],
                    'duration_seconds' => 60,
                    'repetitions' => null,
                    'sets' => 1,
                    'rest_seconds' => 10,
                    'order' => 1
                ],
                [
                    'name' => 'Cat-Cow Stretch',
                    'instructions' => 'On hands and knees, alternate between arching and rounding your spine.',
                    'video_url' => $videoUrls[1],
                    'image_url' => $imageUrls[0],
                    'duration_seconds' => 45,
                    'repetitions' => 10,
                    'sets' => 2,
                    'rest_seconds' => 15,
                    'order' => 2
                ],
                [
                    'name' => 'Downward Dog',
                    'instructions' => 'From hands and knees, tuck toes and lift hips up into inverted V shape.',
                    'video_url' => $videoUrls[2],
                    'image_url' => $imageUrls[1],
                    'duration_seconds' => 60,
                    'repetitions' => null,
                    'sets' => 1,
                    'rest_seconds' => 20,
                    'order' => 3
                ],
                [
                    'name' => 'Seated Forward Fold',
                    'instructions' => 'Sit with legs extended, fold forward reaching toward toes.',
                    'video_url' => $videoUrls[3],
                    'image_url' => $imageUrls[2],
                    'duration_seconds' => 60,
                    'repetitions' => null,
                    'sets' => 1,
                    'rest_seconds' => 10,
                    'order' => 4
                ]
            ]
        ];

        // Add exercises to each workout based on its type
        foreach ($workouts as $workout) {
            $workoutType = $workout->type;
            
            if (isset($exerciseData[$workoutType])) {
                $this->command->info("Adding exercises to {$workout->name} ({$workoutType})");
                
                foreach ($exerciseData[$workoutType] as $exerciseInfo) {
                    Exercise::create(array_merge($exerciseInfo, [
                        'workout_id' => $workout->id
                    ]));
                }
            } else {
                // Default to full_body exercises if type not found
                $this->command->info("Adding default exercises to {$workout->name}");
                
                foreach ($exerciseData['full_body'] as $exerciseInfo) {
                    Exercise::create(array_merge($exerciseInfo, [
                        'workout_id' => $workout->id
                    ]));
                }
            }
        }

        $this->command->info('Exercise seeding completed!');
    }
}

