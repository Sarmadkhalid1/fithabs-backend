<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Coach;
use App\Models\Therapist;
use App\Models\Clinic;
use Illuminate\Support\Facades\Hash;

class ProfessionalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample clinics first
        $clinic1 = Clinic::create([
            'name' => 'Wellness Center Downtown',
            'email' => 'info@wellnessdowntown.com',
            'password' => Hash::make('password'),
            'description' => 'Comprehensive mental health and wellness services in the heart of the city.',
            'logo' => 'https://images.unsplash.com/photo-1518609878373-06d740f60d8b?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
            'phone' => '+1-555-0123',
            'address' => '123 Main Street, Downtown',
            'website' => 'https://wellnessdowntown.com',
            'services' => ['Individual Therapy', 'Group Therapy', 'Family Counseling', 'Mental Health Assessment'],
            'is_active' => true,
        ]);

        $clinic2 = Clinic::create([
            'name' => 'Mind & Body Health Clinic',
            'email' => 'contact@mindbodyclinic.com',
            'password' => Hash::make('password'),
            'description' => 'Integrative approach to mental health combining traditional and alternative therapies.',
            'logo' => 'https://images.unsplash.com/photo-1559757148-5c350d0d3c56?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
            'phone' => '+1-555-0456',
            'address' => '456 Oak Avenue, Midtown',
            'website' => 'https://mindbodyclinic.com',
            'services' => ['Cognitive Behavioral Therapy', 'Mindfulness Training', 'Stress Management', 'Anxiety Treatment'],
            'is_active' => true,
        ]);

        // Create sample coaches
        Coach::create([
            'name' => 'Sarah Johnson',
            'email' => 'sarah@fitnesscoach.com',
            'password' => Hash::make('password'),
            'bio' => 'Certified personal trainer with 8 years of experience helping clients achieve their fitness goals through personalized workout plans and nutrition guidance.',
            'profile_image' => 'https://images.unsplash.com/photo-1594824388852-8d4b4a4b4b4b?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
            'specializations' => ['Weight Loss', 'Muscle Building', 'Functional Training', 'Nutrition Coaching'],
            'certifications' => ['NASM-CPT', 'Precision Nutrition Level 1', 'TRX Suspension Training'],
            'phone' => '+1-555-0789',
            'is_active' => true,
        ]);

        Coach::create([
            'name' => 'Mike Rodriguez',
            'email' => 'mike@strengthcoach.com',
            'password' => Hash::make('password'),
            'bio' => 'Strength and conditioning specialist focused on athletic performance and injury prevention. Former collegiate athlete with expertise in powerlifting and Olympic lifting.',
            'profile_image' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
            'specializations' => ['Strength Training', 'Athletic Performance', 'Injury Prevention', 'Powerlifting'],
            'certifications' => ['CSCS', 'USA Weightlifting Level 1', 'FMS Level 1'],
            'phone' => '+1-555-0321',
            'is_active' => true,
        ]);

        Coach::create([
            'name' => 'Emily Chen',
            'email' => 'emily@yogacoach.com',
            'password' => Hash::make('password'),
            'bio' => 'Yoga instructor and wellness coach specializing in mindful movement, stress reduction, and holistic health approaches for busy professionals.',
            'profile_image' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
            'specializations' => ['Yoga', 'Mindfulness', 'Stress Management', 'Flexibility Training'],
            'certifications' => ['RYT-500', 'Yin Yoga Certification', 'Meditation Teacher Training'],
            'phone' => '+1-555-0654',
            'is_active' => true,
        ]);

        // Create sample therapists
        Therapist::create([
            'name' => 'Dr. Jennifer Martinez',
            'email' => 'jennifer@wellnessdowntown.com',
            'password' => Hash::make('password'),
            'bio' => 'Licensed clinical psychologist with expertise in anxiety disorders, depression, and trauma therapy. Uses evidence-based approaches including CBT and EMDR.',
            'profile_image' => 'https://images.unsplash.com/photo-1559839734-2b71ea197ec2?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
            'specializations' => ['Anxiety Disorders', 'Depression', 'Trauma Therapy', 'Cognitive Behavioral Therapy'],
            'certifications' => ['PhD in Clinical Psychology', 'Licensed Clinical Psychologist', 'EMDR Certified'],
            'phone' => '+1-555-0987',
            'clinic_id' => $clinic1->id,
            'is_active' => true,
        ]);

        Therapist::create([
            'name' => 'Dr. Robert Kim',
            'email' => 'robert@mindbodyclinic.com',
            'password' => Hash::make('password'),
            'bio' => 'Marriage and family therapist specializing in relationship counseling, communication skills, and family dynamics. Over 10 years of experience helping couples and families.',
            'profile_image' => 'https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
            'specializations' => ['Marriage Counseling', 'Family Therapy', 'Communication Skills', 'Relationship Issues'],
            'certifications' => ['LMFT', 'Gottman Method Level 2', 'Emotionally Focused Therapy'],
            'phone' => '+1-555-0123',
            'clinic_id' => $clinic2->id,
            'is_active' => true,
        ]);

        Therapist::create([
            'name' => 'Dr. Lisa Thompson',
            'email' => 'lisa@wellnessdowntown.com',
            'password' => Hash::make('password'),
            'bio' => 'Licensed clinical social worker with a focus on adolescent and young adult mental health. Specializes in anxiety, depression, and life transitions.',
            'profile_image' => 'https://images.unsplash.com/photo-1594824388852-8d4b4a4b4b4b?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
            'specializations' => ['Adolescent Therapy', 'Young Adult Counseling', 'Anxiety', 'Life Transitions'],
            'certifications' => ['LCSW', 'Dialectical Behavior Therapy', 'Trauma-Informed Care'],
            'phone' => '+1-555-0456',
            'clinic_id' => $clinic1->id,
            'is_active' => true,
        ]);
    }
}
