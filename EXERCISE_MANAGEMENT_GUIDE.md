# 🏋️ Exercise Management Guide

## ✅ **Current Status**

Your database now has **5 workouts with exercises**:

- **4 Evening Yoga workouts** (flexibility) - 4 exercises each
- **1 Morning Cardio workout** (cardio) - 4 exercises each

## 🎯 **Testing Your Workout API**

### **1. Get Workouts by Difficulty**

```bash
# Get beginner workouts
curl -X GET "http://localhost:8000/api/v1/workouts/difficulty/beginner" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json"

# Get all workouts with exercises
curl -X GET "http://localhost:8000/api/v1/workouts" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json"
```

### **2. Get Specific Workout with Exercises**

```bash
# Get workout ID 9 (Morning Cardio) with all exercises
curl -X GET "http://localhost:8000/api/v1/workouts/9" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json"
```

### **3. Get Exercises for a Workout**

```bash
# Get all exercises for workout ID 9
curl -X GET "http://localhost:8000/api/v1/workouts/9/exercises" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json"
```

### **4. Start a Workout Session**

```bash
# Start workout session for workout ID 9
curl -X POST "http://localhost:8000/api/v1/workouts/9/start" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json"
```

### **5. Navigate Between Exercises**

```bash
# Get first exercise in workout
curl -X GET "http://localhost:8000/api/v1/workouts/9/exercises/next" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json"

# Get next exercise after exercise ID 1
curl -X GET "http://localhost:8000/api/v1/workouts/9/exercises/next/1" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json"
```

## 🔧 **How to Add Exercises Manually**

### **Method 1: Using API Endpoints**

#### **Create a New Exercise**

```bash
curl -X POST "http://localhost:8000/api/v1/exercises" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "workout_id": 9,
    "name": "Burpees",
    "instructions": "Drop to squat, kick back to plank, do push-up, jump feet back to squat, then jump up.",
    "video_url": "https://example.com/videos/burpees.mp4",
    "image_url": "https://example.com/images/burpees.jpg",
    "duration_seconds": 30,
    "repetitions": 10,
    "sets": 3,
    "rest_seconds": 60,
    "order": 5
  }'
```

#### **Update an Exercise**

```bash
curl -X PUT "http://localhost:8000/api/v1/exercises/1" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Modified Push-ups",
    "repetitions": 20,
    "sets": 4
  }'
```

### **Method 2: Using Laravel Tinker (Command Line)**

```bash
# Open tinker
php artisan tinker

# Create a new exercise
App\Models\Exercise::create([
    'workout_id' => 9,
    'name' => 'Jump Squats',
    'instructions' => 'Perform a regular squat, then jump up explosively.',
    'video_url' => 'https://example.com/videos/jump-squats.mp4',
    'duration_seconds' => 45,
    'repetitions' => 15,
    'sets' => 3,
    'rest_seconds' => 30,
    'order' => 6
]);
```

### **Method 3: Using Database Seeder (Bulk Addition)**

Create a custom seeder for specific workouts:

```php
<?php
// database/seeders/CustomExerciseSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Exercise;

class CustomExerciseSeeder extends Seeder
{
    public function run(): void
    {
        $exercises = [
            [
                'workout_id' => 9,
                'name' => 'Box Jumps',
                'instructions' => 'Jump onto a sturdy box or platform, then step back down.',
                'video_url' => 'https://example.com/videos/box-jumps.mp4',
                'duration_seconds' => 60,
                'repetitions' => 12,
                'sets' => 3,
                'rest_seconds' => 45,
                'order' => 7
            ],
            // Add more exercises here
        ];

        foreach ($exercises as $exercise) {
            Exercise::create($exercise);
        }
    }
}
```

Run the seeder:

```bash
php artisan db:seed --class=CustomExerciseSeeder
```

## 📱 **Testing Workout Flow (Like Your App)**

### **Complete Workout Session Flow**

```bash
# 1. Get beginner workouts
curl -X GET "http://localhost:8000/api/v1/workouts/difficulty/beginner" \
  -H "Authorization: Bearer YOUR_TOKEN"

# 2. Get workout details
curl -X GET "http://localhost:8000/api/v1/workouts/9" \
  -H "Authorization: Bearer YOUR_TOKEN"

# 3. Start workout session
curl -X POST "http://localhost:8000/api/v1/workouts/9/start" \
  -H "Authorization: Bearer YOUR_TOKEN"
# Note the session ID from response

# 4. Get first exercise
curl -X GET "http://localhost:8000/api/v1/workouts/9/exercises/next" \
  -H "Authorization: Bearer YOUR_TOKEN"

# 5. Update exercise progress
curl -X PUT "http://localhost:8000/api/v1/workout-sessions/SESSION_ID/exercises/EXERCISE_ID/progress" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "sets_completed": 1,
    "reps_completed": 10,
    "duration_completed": 30,
    "is_completed": false
  }'

# 6. Get next exercise
curl -X GET "http://localhost:8000/api/v1/workouts/9/exercises/next/CURRENT_EXERCISE_ID" \
  -H "Authorization: Bearer YOUR_TOKEN"

# 7. Complete workout session
curl -X PUT "http://localhost:8000/api/v1/workout-sessions/SESSION_ID/complete" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "calories_burned": 250,
    "rating": 5,
    "notes": "Great workout!"
  }'
```

## 📊 **Exercise Data Structure**

When creating exercises, use this structure:

```json
{
  "workout_id": 9, // Required: ID of the workout
  "name": "Exercise Name", // Required: Name of the exercise
  "instructions": "Step by step...", // Optional: How to perform
  "video_url": "https://...", // Optional: Video demonstration
  "image_url": "https://...", // Optional: Exercise image
  "duration_seconds": 60, // Optional: Time-based duration
  "repetitions": 15, // Optional: Rep-based count
  "sets": 3, // Optional: Number of sets
  "rest_seconds": 30, // Optional: Rest between sets
  "order": 1 // Required: Exercise sequence
}
```

## 🎯 **Exercise Types by Workout**

### **Upper Body Exercises**

- Push-ups, Dumbbell Rows, Shoulder Press, Tricep Dips

### **Lower Body Exercises**

- Squats, Lunges, Glute Bridges, Calf Raises

### **Cardio Exercises**

- Jumping Jacks, High Knees, Burpees, Mountain Climbers

### **Flexibility Exercises**

- Child's Pose, Cat-Cow Stretch, Downward Dog, Seated Forward Fold

### **Full Body Exercises**

- Plank, Squat to Press, Deadlifts

## 🔍 **Quick Database Queries for Testing**

```bash
# Check all workouts and their exercise counts
php artisan tinker --execute="
\App\Models\Workout::with('exercises')->get()->each(function(\$w) {
    echo \$w->name . ' - ' . \$w->exercises->count() . ' exercises' . PHP_EOL;
});
"

# Get specific workout with exercises
php artisan tinker --execute="
\$workout = \App\Models\Workout::with('exercises')->find(9);
echo 'Workout: ' . \$workout->name . PHP_EOL;
\$workout->exercises->each(function(\$e) {
    echo '- ' . \$e->name . ' (' . \$e->sets . ' sets)' . PHP_EOL;
});
"

# Create a quick test exercise
php artisan tinker --execute="
\App\Models\Exercise::create([
    'workout_id' => 9,
    'name' => 'Test Exercise',
    'instructions' => 'This is a test exercise',
    'duration_seconds' => 30,
    'sets' => 2,
    'order' => 10
]);
echo 'Test exercise created!' . PHP_EOL;
"
```

## 🚀 **Ready to Test!**

Your workout API is now fully functional with:

- ✅ 5 workouts with realistic exercises
- ✅ Complete workout session management
- ✅ Exercise navigation (next/previous)
- ✅ Progress tracking per exercise
- ✅ Workout statistics and history

**Next Steps:**

1. Use the API endpoints above to test your mobile app
2. Add more workouts using the seeder or API
3. Customize exercises based on your app's needs
4. Test the complete workout flow from start to finish

**Need more exercises?** Run the ExerciseSeeder again or use the API endpoints to add custom exercises!
