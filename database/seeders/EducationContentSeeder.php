<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EducationContent;

class EducationContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing education content
        EducationContent::truncate();

        // Fitness image URLs provided
        $fitnessImageUrls = [
            "https://images.unsplash.com/photo-1517836357463-d25dfeac3438?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80", // Gym weightlifting
            "https://images.unsplash.com/photo-1518609878373-06d740f60d8b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80", // Yoga pose
            "https://images.unsplash.com/photo-1534438327276-14e5300c3a48?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80", // Running on treadmill
        ];

        $educationContent = [
            [
                'title' => 'The Complete Guide to Strength Training',
                'description' => 'Master the fundamentals of strength training with proper form, progressive overload, and effective workout programming.',
                'cover_image' => $fitnessImageUrls[0],
                'sections' => [
                    [
                        'heading' => 'What is Strength Training?',
                        'content' => 'Strength training is one of the most effective ways to build muscle, increase bone density, and improve overall health. It involves exercises that make your muscles work against resistance.'
                    ],
                    [
                        'heading' => 'Benefits of Strength Training',
                        'content' => 'Regular strength training provides numerous benefits including increased muscle mass and strength, improved bone density, enhanced metabolism, and better functional movement for daily activities.'
                    ],
                    [
                        'heading' => 'Getting Started',
                        'content' => 'Begin with bodyweight exercises or light weights, focusing on proper form. Start with 2-3 sessions per week and gradually increase intensity as you build strength and confidence.'
                    ]
                ],
                'category' => 'training',
                'tags' => ['strength training', 'muscle building', 'beginner'],
                'is_featured' => true,
                'is_active' => true,
                'created_by_admin' => 1,
            ],
            [
                'title' => 'Yoga for Beginners: Mind, Body, and Soul',
                'description' => 'Discover the ancient practice of yoga and how it can transform your physical health, mental clarity, and spiritual well-being.',
                'cover_image' => $fitnessImageUrls[1],
                'sections' => [
                    [
                        'heading' => 'What is Yoga?',
                        'content' => 'Yoga is more than just physical exercise – it\'s a holistic practice that combines movement, breath, and mindfulness to create harmony between mind, body, and spirit.'
                    ],
                    [
                        'heading' => 'Benefits of Yoga Practice',
                        'content' => 'Regular yoga practice offers improved flexibility, enhanced strength and balance, reduced stress and anxiety, better sleep quality, and greater self-awareness.'
                    ],
                    [
                        'heading' => 'Essential Poses for Beginners',
                        'content' => 'Start with basic poses like Mountain Pose, Downward Facing Dog, Child\'s Pose, and Warrior I. Focus on proper alignment rather than achieving perfect poses.'
                    ]
                ],
                'category' => 'wellness',
                'tags' => ['yoga', 'flexibility', 'mindfulness', 'beginner'],
                'is_featured' => true,
                'is_active' => true,
                'created_by_admin' => 1,
            ],
            [
                'title' => 'Cardio Training: Heart Health and Endurance',
                'description' => 'Learn about different types of cardiovascular exercise and how to build an effective cardio routine for optimal heart health.',
                'cover_image' => $fitnessImageUrls[2],
                'sections' => [
                    [
                        'heading' => 'Why Cardio Matters',
                        'content' => 'Cardiovascular exercise is essential for heart health, weight management, and overall fitness. It strengthens your heart, improves circulation, and boosts energy levels.'
                    ],
                    [
                        'heading' => 'Types of Cardio Exercise',
                        'content' => 'Popular cardio activities include running, cycling, swimming, dancing, and HIIT workouts. Choose activities you enjoy to maintain consistency.'
                    ],
                    [
                        'heading' => 'Building Your Cardio Routine',
                        'content' => 'Start with 15-20 minutes of moderate activity 3 times per week. Gradually increase duration and intensity as your fitness improves.'
                    ]
                ],
                'category' => 'training',
                'tags' => ['cardio', 'heart health', 'endurance', 'running'],
                'is_featured' => false,
                'is_active' => true,
                'created_by_admin' => 1,
            ],
        ];

        foreach ($educationContent as $content) {
            EducationContent::create($content);
        }

        $this->command->info('Education content seeding completed with 3 articles!');
    }
}
