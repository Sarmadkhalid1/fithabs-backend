# Workout & Exercise Frontend Integration Guide

## Overview

This guide provides complete documentation for integrating workout and exercise functionality in the FitHabs frontend application.

## Database Relationship

- **One-to-Many**: One Workout can have multiple Exercises
- **Foreign Key**: Each exercise has a `workout_id` that references the workout
- **Ordering**: Exercises have an `order` field for sequencing
- **Cascade Delete**: When a workout is deleted, all its exercises are automatically deleted
- **Media Support**: Workouts can have images, Exercises can have both images and videos

## API Endpoints Reference

### Workout Endpoints

#### 1. Get All Workouts (with exercises)

```http
GET /api/workouts
Authorization: Bearer {token}
```

**Response:**

```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "name": "Upper Body Strength",
      "description": "A comprehensive upper body workout",
      "difficulty": "intermediate",
      "type": "upper_body",
      "duration_minutes": 45,
      "calories_per_session": 300,
      "equipment_needed": ["dumbbells", "bench"],
      "tags": ["strength", "muscle-building"],
      "is_featured": true,
      "is_active": true,
      "total_exercises": 5,
      "total_sets": 15,
      "exercises": [
        {
          "id": 1,
          "workout_id": 1,
          "name": "Push-ups",
          "instructions": "Start in plank position...",
          "sets": 3,
          "repetitions": 15,
          "rest_seconds": 60,
          "order": 1,
          "video_url": null,
          "image_url": null,
          "duration_seconds": 300
        }
      ]
    }
  ],
  "count": 1
}
```

#### 2. Get Single Workout (with exercises)

```http
GET /api/workouts/{id}
Authorization: Bearer {token}
```

#### 3. Filter Workouts

```http
GET /api/workouts/filter?difficulty=intermediate&type=upper_body&tags[]=strength
Authorization: Bearer {token}
```

#### 4. Get Workouts by Difficulty

```http
GET /api/workouts/difficulty/{difficulty}
Authorization: Bearer {token}
```

**Difficulty values:** `beginner`, `intermediate`, `advanced`

#### 5. Create Workout (Admin Only)

**Option A: JSON Request (with image_url)**

```http
POST /api/workouts
Authorization: Bearer {admin_token}
Content-Type: application/json

{
    "name": "Upper Body Strength",
    "description": "A comprehensive upper body workout",
    "difficulty": "intermediate",
    "type": "upper_body",
    "duration_minutes": 45,
    "calories_per_session": 300,
    "equipment_needed": ["dumbbells", "bench"],
    "tags": ["strength", "muscle-building"],
    "is_featured": true,
    "is_active": true,
    "created_by_admin": 1,
    "image_url": "https://example.com/workout-image.jpg"
}
```

**Option B: FormData Request (with image file upload)**

```http
POST /api/workouts
Authorization: Bearer {admin_token}
Content-Type: multipart/form-data

name: "Upper Body Strength"
description: "A comprehensive upper body workout"
difficulty: "intermediate"
type: "upper_body"
duration_minutes: "45"
calories_per_session: "300"
equipment_needed[]: "dumbbells"
equipment_needed[]: "bench"
tags[]: "strength"
tags[]: "muscle-building"
is_featured: "true"
is_active: "true"
created_by_admin: "1"
image: [FILE_UPLOAD]
```

#### 6. Update Workout (Admin Only)

```http
PUT /api/workouts/{id}
Authorization: Bearer {admin_token}
Content-Type: application/json
```

#### 7. Delete Workout (Admin Only)

```http
DELETE /api/workouts/{id}
Authorization: Bearer {admin_token}
```

### Exercise Endpoints

#### 1. Get All Exercises

```http
GET /api/exercises
Authorization: Bearer {token}
```

#### 2. Get Exercises for Specific Workout

```http
GET /api/exercises?workout_id={workout_id}
Authorization: Bearer {token}
```

#### 3. Get Single Exercise

```http
GET /api/exercises/{id}
Authorization: Bearer {token}
```

#### 4. Create Exercise (Admin Only)

**Option A: JSON Request (with image_url and video_url)**

```http
POST /api/exercises
Authorization: Bearer {admin_token}
Content-Type: application/json

{
    "workout_id": 1,
    "name": "Push-ups",
    "instructions": "Start in plank position, lower your body until chest nearly touches floor, then push back up",
    "video_url": "https://example.com/pushups.mp4",
    "image_url": "https://example.com/pushups.jpg",
    "duration_seconds": 300,
    "repetitions": 15,
    "sets": 3,
    "rest_seconds": 60,
    "order": 1
}
```

**Option B: FormData Request (with file uploads)**

```http
POST /api/exercises
Authorization: Bearer {admin_token}
Content-Type: multipart/form-data

workout_id: "1"
name: "Push-ups"
instructions: "Start in plank position, lower your body until chest nearly touches floor, then push back up"
duration_seconds: "300"
repetitions: "15"
sets: "3"
rest_seconds: "60"
order: "1"
image: [FILE_UPLOAD]
video: [FILE_UPLOAD]
```

#### 5. Update Exercise (Admin Only)

```http
PUT /api/exercises/{id}
Authorization: Bearer {admin_token}
Content-Type: application/json
```

#### 6. Delete Exercise (Admin Only)

```http
DELETE /api/exercises/{id}
Authorization: Bearer {admin_token}
```

#### 7. Get Workout's Exercises (Alternative endpoint)

```http
GET /api/workouts/{workout_id}/exercises
Authorization: Bearer {token}
```

#### 8. Get Next Exercise in Workout

```http
GET /api/workouts/{workout_id}/exercises/next/{current_exercise_id}
Authorization: Bearer {token}
```

#### 9. Get Previous Exercise in Workout

```http
GET /api/workouts/{workout_id}/exercises/previous/{current_exercise_id}
Authorization: Bearer {token}
```

### Media Management Endpoints

#### 1. Upload Image (Admin Only)

```http
POST /api/images
Authorization: Bearer {admin_token}
Content-Type: multipart/form-data

title: "Workout Image"
description: "Image for workout display"
category: "workout"
tags[]: "fitness"
tags[]: "exercise"
image: [FILE_UPLOAD]
```

**Response:**

```json
{
  "success": true,
  "message": "Image uploaded successfully",
  "data": {
    "id": 1,
    "title": "Workout Image",
    "description": "Image for workout display",
    "url": "http://127.0.0.1:8000/storage/images/uuid-generated-filename.jpg",
    "category": "workout",
    "tags": ["fitness", "exercise"],
    "width": 1920,
    "height": 1080,
    "file_size": 1024000,
    "created_at": "2025-01-15T12:00:00.000000Z"
  }
}
```

#### 2. Upload Video (Admin Only)

```http
POST /api/videos
Authorization: Bearer {admin_token}
Content-Type: multipart/form-data

title: "Push-up Tutorial"
description: "Learn proper push-up form"
category: "exercise"
tags[]: "strength"
tags[]: "upper-body"
video: [FILE_UPLOAD]
```

**Response:**

```json
{
  "status": "success",
  "message": "Video uploaded successfully",
  "data": {
    "id": 1,
    "title": "Push-up Tutorial",
    "description": "Learn proper push-up form",
    "category": "exercise",
    "file_path": "videos/pushup_tutorial.mp4",
    "file_size": 15728640,
    "duration": 120,
    "tags": ["strength", "upper-body"],
    "is_active": true,
    "created_by_admin": 1,
    "created_at": "2025-01-15T12:00:00.000000Z"
  }
}
```

#### 3. List Images

```http
GET /api/images?category=workout&search=fitness&per_page=20
Authorization: Bearer {token}
```

#### 4. List Videos

```http
GET /api/videos?category=exercise&search=strength&per_page=20
Authorization: Bearer {token}
```

#### 5. Serve Image

```http
GET /api/images/{id}/serve
Authorization: Bearer {token}
```

#### 6. Stream Video

```http
GET /api/videos/{id}/stream
Authorization: Bearer {token}
```

### User Workout Session Endpoints

#### 1. Start Workout Session

```http
POST /api/workouts/{workout_id}/start
Authorization: Bearer {user_token}
```

**Response:**

```json
{
    "status": "success",
    "message": "Workout session started",
    "data": {
        "session": {
            "id": 1,
            "user_id": 1,
            "workout_id": 1,
            "started_at": "2024-01-15T10:00:00Z",
            "completed_at": null,
            "exercise_progress": {}
        },
        "workout": {
            "id": 1,
            "name": "Upper Body Strength",
            "exercises": [...]
        }
    }
}
```

#### 2. Update Exercise Progress

```http
PUT /api/workout-sessions/{session_id}/exercises/{exercise_id}/progress
Authorization: Bearer {user_token}
Content-Type: application/json

{
    "sets_completed": 2,
    "reps_completed": 10,
    "duration_completed": 120,
    "is_completed": false
}
```

#### 3. Complete Workout Session

```http
PUT /api/workout-sessions/{session_id}/complete
Authorization: Bearer {user_token}
Content-Type: application/json

{
    "calories_burned": 250,
    "rating": 4,
    "notes": "Great workout!"
}
```

## Frontend Implementation Examples

### 1. Fetching Workouts with Exercises

```javascript
// Fetch all workouts with their exercises
const fetchWorkouts = async () => {
  try {
    const response = await fetch('/api/workouts', {
      headers: {
        Authorization: `Bearer ${userToken}`,
        'Content-Type': 'application/json',
      },
    });

    const data = await response.json();

    if (data.status === 'success') {
      // Sort exercises by order within each workout
      data.data.forEach((workout) => {
        workout.exercises.sort((a, b) => a.order - b.order);
      });

      return data.data;
    }
  } catch (error) {
    console.error('Error fetching workouts:', error);
  }
};
```

### 2. Creating a Workout (Admin)

```javascript
const createWorkout = async (workoutData) => {
  try {
    const response = await fetch('/api/workouts', {
      method: 'POST',
      headers: {
        Authorization: `Bearer ${adminToken}`,
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(workoutData),
    });

    const data = await response.json();

    if (data.status === 'success') {
      return data.data; // Returns the created workout
    }
  } catch (error) {
    console.error('Error creating workout:', error);
  }
};
```

### 3. Adding Exercises to a Workout (Admin)

```javascript
const addExerciseToWorkout = async (workoutId, exerciseData) => {
  try {
    const response = await fetch('/api/exercises', {
      method: 'POST',
      headers: {
        Authorization: `Bearer ${adminToken}`,
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        ...exerciseData,
        workout_id: workoutId,
      }),
    });

    const data = await response.json();

    if (data.status === 'success') {
      return data.data; // Returns the created exercise
    }
  } catch (error) {
    console.error('Error creating exercise:', error);
  }
};
```

### 4. Complete Workout Creation Flow (Admin)

```javascript
const createCompleteWorkout = async (workoutData, exercisesData) => {
  try {
    // Step 1: Create the workout
    const workout = await createWorkout(workoutData);

    if (!workout) {
      throw new Error('Failed to create workout');
    }

    // Step 2: Create all exercises
    const exercises = [];
    for (let i = 0; i < exercisesData.length; i++) {
      const exerciseData = {
        ...exercisesData[i],
        order: i + 1, // Ensure proper ordering
      };

      const exercise = await addExerciseToWorkout(workout.id, exerciseData);
      if (exercise) {
        exercises.push(exercise);
      }
    }

    return {
      workout,
      exercises,
    };
  } catch (error) {
    console.error('Error creating complete workout:', error);
    throw error;
  }
};
```

### 5. Upload Workout with Image (FormData)

```javascript
const createWorkoutWithImage = async (workoutData, imageFile) => {
  try {
    const formData = new FormData();

    // Add workout data
    Object.keys(workoutData).forEach((key) => {
      if (Array.isArray(workoutData[key])) {
        workoutData[key].forEach((item) => {
          formData.append(`${key}[]`, item);
        });
      } else {
        formData.append(key, workoutData[key]);
      }
    });

    // Add image file
    if (imageFile) {
      formData.append('image', imageFile);
    }

    const response = await fetch('/api/workouts', {
      method: 'POST',
      headers: {
        Authorization: `Bearer ${adminToken}`,
        // Don't set Content-Type, let browser set it with boundary
      },
      body: formData,
    });

    const data = await response.json();

    if (data.status === 'success') {
      return data.data;
    }
  } catch (error) {
    console.error('Error creating workout with image:', error);
  }
};
```

### 6. Upload Exercise with Media (FormData)

```javascript
const createExerciseWithMedia = async (exerciseData, imageFile, videoFile) => {
  try {
    const formData = new FormData();

    // Add exercise data
    Object.keys(exerciseData).forEach((key) => {
      formData.append(key, exerciseData[key]);
    });

    // Add media files
    if (imageFile) {
      formData.append('image', imageFile);
    }
    if (videoFile) {
      formData.append('video', videoFile);
    }

    const response = await fetch('/api/exercises', {
      method: 'POST',
      headers: {
        Authorization: `Bearer ${adminToken}`,
      },
      body: formData,
    });

    const data = await response.json();

    if (data.status === 'success') {
      return data.data;
    }
  } catch (error) {
    console.error('Error creating exercise with media:', error);
  }
};
```

### 7. Upload Media Files Separately

```javascript
// Upload image
const uploadImage = async (imageFile, metadata) => {
  try {
    const formData = new FormData();
    formData.append('image', imageFile);
    formData.append('title', metadata.title);
    formData.append('description', metadata.description);
    formData.append('category', metadata.category);

    if (metadata.tags) {
      metadata.tags.forEach((tag) => {
        formData.append('tags[]', tag);
      });
    }

    const response = await fetch('/api/images', {
      method: 'POST',
      headers: {
        Authorization: `Bearer ${adminToken}`,
      },
      body: formData,
    });

    const data = await response.json();

    if (data.success) {
      return data.data.url; // Return the image URL
    }
  } catch (error) {
    console.error('Error uploading image:', error);
  }
};

// Upload video
const uploadVideo = async (videoFile, metadata) => {
  try {
    const formData = new FormData();
    formData.append('video', videoFile);
    formData.append('title', metadata.title);
    formData.append('description', metadata.description);
    formData.append('category', metadata.category);

    if (metadata.tags) {
      metadata.tags.forEach((tag) => {
        formData.append('tags[]', tag);
      });
    }

    const response = await fetch('/api/videos', {
      method: 'POST',
      headers: {
        Authorization: `Bearer ${adminToken}`,
      },
      body: formData,
    });

    const data = await response.json();

    if (data.status === 'success') {
      return data.data.url; // Return the video URL
    }
  } catch (error) {
    console.error('Error uploading video:', error);
  }
};
```

### 5. Starting a Workout Session (User)

```javascript
const startWorkoutSession = async (workoutId) => {
  try {
    const response = await fetch(`/api/workouts/${workoutId}/start`, {
      method: 'POST',
      headers: {
        Authorization: `Bearer ${userToken}`,
        'Content-Type': 'application/json',
      },
    });

    const data = await response.json();

    if (data.status === 'success') {
      return data.data; // Returns session and workout data
    }
  } catch (error) {
    console.error('Error starting workout session:', error);
  }
};
```

### 6. Tracking Exercise Progress (User)

```javascript
const updateExerciseProgress = async (sessionId, exerciseId, progress) => {
  try {
    const response = await fetch(
      `/api/workout-sessions/${sessionId}/exercises/${exerciseId}/progress`,
      {
        method: 'PUT',
        headers: {
          Authorization: `Bearer ${userToken}`,
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(progress),
      }
    );

    const data = await response.json();

    if (data.status === 'success') {
      return data.data;
    }
  } catch (error) {
    console.error('Error updating exercise progress:', error);
  }
};
```

### 7. Completing a Workout Session (User)

```javascript
const completeWorkoutSession = async (sessionId, completionData) => {
  try {
    const response = await fetch(`/api/workout-sessions/${sessionId}/complete`, {
      method: 'PUT',
      headers: {
        Authorization: `Bearer ${userToken}`,
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(completionData),
    });

    const data = await response.json();

    if (data.status === 'success') {
      return data.data;
    }
  } catch (error) {
    console.error('Error completing workout session:', error);
  }
};
```

## File Validation Rules

### Images

- **Formats**: JPEG, JPG, PNG, GIF, WebP
- **Max Size**: 10MB
- **Field Name**: `image`

### Videos

- **Formats**: MP4, AVI, MOV, WMV, FLV, WebM
- **Max Size**: 100MB
- **Field Name**: `video`

## Media Upload Best Practices

1. **File Validation**: Always validate file types and sizes on frontend before upload
2. **Progress Tracking**: Show upload progress for large files
3. **Error Handling**: Handle upload failures gracefully
4. **Preview**: Show image/video previews before upload
5. **Compression**: Consider compressing images before upload for better performance
6. **Fallbacks**: Provide fallback URLs if media fails to load

## Example React Component for Media Upload

```jsx
// MediaUpload.jsx
const MediaUpload = ({ onUpload, type = 'image' }) => {
  const [file, setFile] = useState(null);
  const [preview, setPreview] = useState(null);
  const [uploading, setUploading] = useState(false);

  const handleFileChange = (e) => {
    const selectedFile = e.target.files[0];
    if (selectedFile) {
      setFile(selectedFile);

      // Create preview
      if (type === 'image') {
        const reader = new FileReader();
        reader.onload = (e) => setPreview(e.target.result);
        reader.readAsDataURL(selectedFile);
      }
    }
  };

  const handleUpload = async () => {
    if (!file) return;

    setUploading(true);
    try {
      const metadata = {
        title: file.name,
        description: `Uploaded ${type}`,
        category: type === 'image' ? 'workout' : 'exercise',
        tags: ['fitness'],
      };

      const url =
        type === 'image' ? await uploadImage(file, metadata) : await uploadVideo(file, metadata);

      onUpload(url);
    } catch (error) {
      console.error('Upload failed:', error);
    } finally {
      setUploading(false);
    }
  };

  return (
    <div className="media-upload">
      <input
        type="file"
        accept={type === 'image' ? 'image/*' : 'video/*'}
        onChange={handleFileChange}
      />

      {preview && (
        <div className="preview">
          <img src={preview} alt="Preview" style={{ maxWidth: '200px' }} />
        </div>
      )}

      {file && (
        <button onClick={handleUpload} disabled={uploading}>
          {uploading ? 'Uploading...' : 'Upload'}
        </button>
      )}
    </div>
  );
};
```

## Data Models

### Workout Model

```typescript
interface Workout {
  id: number;
  name: string;
  description: string;
  image_url?: string;
  difficulty: 'beginner' | 'intermediate' | 'advanced';
  type: 'upper_body' | 'lower_body' | 'full_body' | 'cardio' | 'flexibility';
  duration_minutes?: number;
  calories_per_session?: number;
  equipment_needed?: string[];
  tags?: string[];
  is_featured: boolean;
  is_active: boolean;
  created_by_admin: number;
  created_at: string;
  updated_at: string;
  // Computed fields
  total_exercises?: number;
  total_sets?: number;
  estimated_duration?: number;
  // Related data
  exercises?: Exercise[];
}
```

### Exercise Model

```typescript
interface Exercise {
  id: number;
  workout_id: number;
  name: string;
  instructions?: string;
  video_url?: string;
  image_url?: string;
  duration_seconds?: number;
  repetitions?: number;
  sets?: number;
  rest_seconds?: number;
  order: number;
  created_at: string;
  updated_at: string;
  // Related data
  workout?: Workout;
}
```

### User Workout Session Model

```typescript
interface UserWorkoutSession {
  id: number;
  user_id: number;
  workout_id: number;
  started_at: string;
  completed_at?: string;
  calories_burned?: number;
  exercise_progress?: Record<number, ExerciseProgress>;
  rating?: number;
  notes?: string;
  created_at: string;
  updated_at: string;
  // Related data
  workout?: Workout;
}

interface ExerciseProgress {
  sets_completed: number;
  reps_completed: number;
  duration_completed: number;
  is_completed: boolean;
  updated_at: string;
}
```

## Error Handling

All API endpoints return consistent error responses:

```json
{
  "status": "error",
  "message": "Error description",
  "error": "Detailed error message"
}
```

Common HTTP status codes:

- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden (Admin access required)
- `404` - Not Found
- `422` - Validation Error
- `500` - Server Error

## Authentication

- **User Token**: For viewing workouts, starting sessions, tracking progress
- **Admin Token**: For creating, updating, deleting workouts and exercises

## Best Practices

1. **Always sort exercises by order** when displaying workout details
2. **Handle loading states** for better UX during API calls
3. **Validate data** before sending to API
4. **Use proper error handling** for all API calls
5. **Cache workout data** when appropriate to reduce API calls
6. **Implement offline support** for workout sessions in progress
7. **Use optimistic updates** for better perceived performance

## Example React Component Structure

```jsx
// WorkoutList.jsx
const WorkoutList = () => {
  const [workouts, setWorkouts] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetchWorkouts().then((data) => {
      setWorkouts(data);
      setLoading(false);
    });
  }, []);

  return (
    <div>
      {loading ? (
        <div>Loading workouts...</div>
      ) : (
        workouts.map((workout) => <WorkoutCard key={workout.id} workout={workout} />)
      )}
    </div>
  );
};

// WorkoutCard.jsx
const WorkoutCard = ({ workout }) => {
  const startWorkout = async () => {
    const session = await startWorkoutSession(workout.id);
    // Navigate to workout session page
  };

  return (
    <div className="workout-card">
      <h3>{workout.name}</h3>
      <p>{workout.description}</p>
      <p>Difficulty: {workout.difficulty}</p>
      <p>Exercises: {workout.total_exercises}</p>
      <button onClick={startWorkout}>Start Workout</button>
    </div>
  );
};
```

This documentation provides everything your frontend developer needs to implement the complete workout and exercise functionality.
