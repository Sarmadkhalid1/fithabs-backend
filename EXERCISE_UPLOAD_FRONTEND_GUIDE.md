# Exercise Upload Frontend Guide

## Overview

This guide provides frontend developers with everything needed to implement exercise creation with video and image uploads in the FitHabs application.

## API Endpoints

### Base URL

```
http://localhost:8000/api/v1
```

### Authentication

All endpoints require Bearer token authentication:

```javascript
const headers = {
  Authorization: `Bearer ${userToken}`,
  'Content-Type': 'application/json',
};
```

## 1. Admin Login

### Endpoint

```
POST /admin-login
```

### Request Body

```json
{
  "email": "admin@example.com",
  "password": "admin123"
}
```

### Response

```json
{
  "user": {
    "id": 1,
    "name": "Super Admin",
    "email": "admin@example.com",
    "role": "super_admin"
  },
  "token": "108|mSOJBoRu5ivEfMpa8yAaIsRt69N9mUh3RGwyN8MX357cb412"
}
```

### Frontend Implementation

```javascript
const loginAdmin = async () => {
  try {
    const response = await fetch('/api/v1/admin-login', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        email: 'admin@example.com',
        password: 'admin123',
      }),
    });

    const data = await response.json();

    if (data.token) {
      localStorage.setItem('adminToken', data.token);
      return data.token;
    }
  } catch (error) {
    console.error('Login failed:', error);
  }
};
```

## 2. Create Workout

### Endpoint

```
POST /workouts
```

### Request Body

```json
{
  "name": "My Workout",
  "description": "Workout description",
  "difficulty": "beginner",
  "type": "full_body",
  "duration_minutes": 30,
  "created_by_admin": true
}
```

### Required Fields

- `name`: Workout name (string, max 255 chars)
- `description`: Workout description (string)
- `difficulty`: "beginner", "intermediate", or "advanced"
- `type`: "upper_body", "lower_body", "full_body", "cardio", or "flexibility"
- `created_by_admin`: Must be `true` for admin users

### Response

```json
{
  "status": "success",
  "message": "Workout created successfully",
  "data": {
    "id": 13,
    "name": "My Workout",
    "description": "Workout description",
    "difficulty": "beginner",
    "type": "full_body",
    "duration_minutes": 30,
    "created_by_admin": true,
    "created_at": "2025-09-15T12:57:44.000000Z",
    "updated_at": "2025-09-15T12:57:44.000000Z"
  }
}
```

### Frontend Implementation

```javascript
const createWorkout = async (workoutData) => {
  const token = localStorage.getItem('adminToken');

  try {
    const response = await fetch('/api/v1/workouts', {
      method: 'POST',
      headers: {
        Authorization: `Bearer ${token}`,
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        name: workoutData.name,
        description: workoutData.description,
        difficulty: workoutData.difficulty,
        type: workoutData.type,
        duration_minutes: workoutData.duration_minutes,
        created_by_admin: true,
      }),
    });

    const data = await response.json();

    if (data.status === 'success') {
      return data.data.id; // Return workout ID for exercise creation
    } else {
      throw new Error(data.message);
    }
  } catch (error) {
    console.error('Workout creation failed:', error);
    throw error;
  }
};
```

## 3. Create Exercise with File Uploads

### Endpoint

```
POST /exercises
```

### Request Body (FormData)

```javascript
const formData = new FormData();
formData.append('workout_id', workoutId);
formData.append('name', 'Exercise Name');
formData.append('instructions', 'Exercise instructions');
formData.append('video', videoFile); // File object
formData.append('image', imageFile); // File object
formData.append('duration_seconds', '60');
formData.append('repetitions', '10');
formData.append('sets', '3');
formData.append('order', '1');
```

### File Upload Specifications

#### Video Files

- **Supported formats**: MP4, AVI, MOV, WMV, FLV, WebM
- **Maximum size**: 100MB
- **Field name**: `video`

#### Image Files

- **Supported formats**: JPEG, PNG, JPG, GIF, WebP
- **Maximum size**: 10MB
- **Field name**: `image`

### Response

```json
{
  "status": "success",
  "message": "Exercise created successfully",
  "data": {
    "id": 28,
    "workout_id": "13",
    "name": "Exercise Name",
    "instructions": "Exercise instructions",
    "duration_seconds": "60",
    "repetitions": "10",
    "sets": "3",
    "order": "1",
    "image_url": "/storage/exercises/images/exercise_1757941064_eaZ0yulB1F.jpg",
    "video_url": "/storage/exercises/videos/exercise_1757941064_15RwYtYGZc.mp4",
    "created_at": "2025-09-15T12:57:44.000000Z",
    "updated_at": "2025-09-15T12:57:44.000000Z"
  }
}
```

### Frontend Implementation

#### React Component Example

```jsx
import React, { useState } from 'react';

const ExerciseUploadForm = ({ workoutId }) => {
  const [formData, setFormData] = useState({
    name: '',
    instructions: '',
    duration_seconds: '',
    repetitions: '',
    sets: '',
    order: '',
  });
  const [videoFile, setVideoFile] = useState(null);
  const [imageFile, setImageFile] = useState(null);
  const [uploading, setUploading] = useState(false);
  const [error, setError] = useState(null);

  const handleFileChange = (e, type) => {
    const file = e.target.files[0];
    if (file) {
      // Validate file size
      const maxSize = type === 'video' ? 100 * 1024 * 1024 : 10 * 1024 * 1024; // 100MB or 10MB
      if (file.size > maxSize) {
        setError(`${type} file is too large. Maximum size: ${type === 'video' ? '100MB' : '10MB'}`);
        return;
      }

      if (type === 'video') {
        setVideoFile(file);
      } else {
        setImageFile(file);
      }
      setError(null);
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setUploading(true);
    setError(null);

    const token = localStorage.getItem('adminToken');
    const submitData = new FormData();

    // Add form fields
    Object.keys(formData).forEach((key) => {
      if (formData[key]) {
        submitData.append(key, formData[key]);
      }
    });

    submitData.append('workout_id', workoutId);

    // Add files
    if (videoFile) {
      submitData.append('video', videoFile);
    }
    if (imageFile) {
      submitData.append('image', imageFile);
    }

    try {
      const response = await fetch('/api/v1/exercises', {
        method: 'POST',
        headers: {
          Authorization: `Bearer ${token}`,
          // Don't set Content-Type for FormData, let browser set it
        },
        body: submitData,
      });

      const data = await response.json();

      if (data.status === 'success') {
        alert('Exercise created successfully!');
        // Reset form
        setFormData({
          name: '',
          instructions: '',
          duration_seconds: '',
          repetitions: '',
          sets: '',
          order: '',
        });
        setVideoFile(null);
        setImageFile(null);
        document.getElementById('video-input').value = '';
        document.getElementById('image-input').value = '';
      } else {
        setError(data.message || 'Upload failed');
      }
    } catch (error) {
      setError('Network error: ' + error.message);
    } finally {
      setUploading(false);
    }
  };

  return (
    <form onSubmit={handleSubmit} className="exercise-upload-form">
      <h2>Create Exercise</h2>

      {error && <div className="error-message">{error}</div>}

      <div className="form-group">
        <label htmlFor="name">Exercise Name *</label>
        <input
          type="text"
          id="name"
          value={formData.name}
          onChange={(e) => setFormData({ ...formData, name: e.target.value })}
          required
        />
      </div>

      <div className="form-group">
        <label htmlFor="instructions">Instructions</label>
        <textarea
          id="instructions"
          value={formData.instructions}
          onChange={(e) => setFormData({ ...formData, instructions: e.target.value })}
          rows="4"
        />
      </div>

      <div className="form-group">
        <label htmlFor="video">Video File (MP4, AVI, MOV, WMV, FLV, WebM - Max 100MB)</label>
        <input
          type="file"
          id="video-input"
          accept="video/mp4,video/avi,video/mov,video/wmv,video/flv,video/webm"
          onChange={(e) => handleFileChange(e, 'video')}
        />
        {videoFile && (
          <div className="file-info">
            Selected: {videoFile.name} ({(videoFile.size / 1024 / 1024).toFixed(2)} MB)
          </div>
        )}
      </div>

      <div className="form-group">
        <label htmlFor="image">Image File (JPEG, PNG, JPG, GIF, WebP - Max 10MB)</label>
        <input
          type="file"
          id="image-input"
          accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
          onChange={(e) => handleFileChange(e, 'image')}
        />
        {imageFile && (
          <div className="file-info">
            Selected: {imageFile.name} ({(imageFile.size / 1024 / 1024).toFixed(2)} MB)
          </div>
        )}
      </div>

      <div className="form-row">
        <div className="form-group">
          <label htmlFor="duration_seconds">Duration (seconds)</label>
          <input
            type="number"
            id="duration_seconds"
            value={formData.duration_seconds}
            onChange={(e) => setFormData({ ...formData, duration_seconds: e.target.value })}
            min="0"
          />
        </div>

        <div className="form-group">
          <label htmlFor="repetitions">Repetitions</label>
          <input
            type="number"
            id="repetitions"
            value={formData.repetitions}
            onChange={(e) => setFormData({ ...formData, repetitions: e.target.value })}
            min="0"
          />
        </div>

        <div className="form-group">
          <label htmlFor="sets">Sets</label>
          <input
            type="number"
            id="sets"
            value={formData.sets}
            onChange={(e) => setFormData({ ...formData, sets: e.target.value })}
            min="0"
          />
        </div>

        <div className="form-group">
          <label htmlFor="order">Order</label>
          <input
            type="number"
            id="order"
            value={formData.order}
            onChange={(e) => setFormData({ ...formData, order: e.target.value })}
            min="0"
          />
        </div>
      </div>

      <button type="submit" disabled={uploading}>
        {uploading ? 'Creating Exercise...' : 'Create Exercise'}
      </button>
    </form>
  );
};

export default ExerciseUploadForm;
```

#### Vanilla JavaScript Example

```javascript
class ExerciseUploader {
  constructor(apiBaseUrl, token) {
    this.apiBaseUrl = apiBaseUrl;
    this.token = token;
  }

  async createExercise(workoutId, exerciseData, videoFile, imageFile) {
    const formData = new FormData();

    // Add exercise data
    formData.append('workout_id', workoutId);
    formData.append('name', exerciseData.name);
    formData.append('instructions', exerciseData.instructions || '');
    formData.append('duration_seconds', exerciseData.duration_seconds || '');
    formData.append('repetitions', exerciseData.repetitions || '');
    formData.append('sets', exerciseData.sets || '');
    formData.append('order', exerciseData.order || '');

    // Add files
    if (videoFile) {
      formData.append('video', videoFile);
    }
    if (imageFile) {
      formData.append('image', imageFile);
    }

    try {
      const response = await fetch(`${this.apiBaseUrl}/exercises`, {
        method: 'POST',
        headers: {
          Authorization: `Bearer ${this.token}`,
        },
        body: formData,
      });

      const data = await response.json();

      if (data.status === 'success') {
        return {
          success: true,
          exercise: data.data,
        };
      } else {
        return {
          success: false,
          error: data.message,
          errors: data.errors,
        };
      }
    } catch (error) {
      return {
        success: false,
        error: 'Network error: ' + error.message,
      };
    }
  }

  validateFile(file, type) {
    const maxSize = type === 'video' ? 100 * 1024 * 1024 : 10 * 1024 * 1024;

    if (file.size > maxSize) {
      return {
        valid: false,
        error: `${type} file is too large. Maximum size: ${type === 'video' ? '100MB' : '10MB'}`,
      };
    }

    const allowedTypes =
      type === 'video'
        ? ['video/mp4', 'video/avi', 'video/mov', 'video/wmv', 'video/flv', 'video/webm']
        : ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];

    if (!allowedTypes.includes(file.type)) {
      return {
        valid: false,
        error: `Invalid ${type} file type. Allowed: ${allowedTypes.join(', ')}`,
      };
    }

    return { valid: true };
  }
}

// Usage
const uploader = new ExerciseUploader('/api/v1', localStorage.getItem('adminToken'));

// Validate files before upload
const videoValidation = uploader.validateFile(videoFile, 'video');
const imageValidation = uploader.validateFile(imageFile, 'image');

if (!videoValidation.valid) {
  console.error(videoValidation.error);
  return;
}

if (!imageValidation.valid) {
  console.error(imageValidation.error);
  return;
}

// Upload exercise
const result = await uploader.createExercise(workoutId, exerciseData, videoFile, imageFile);

if (result.success) {
  console.log('Exercise created:', result.exercise);
} else {
  console.error('Upload failed:', result.error);
}
```

## 4. Debug Upload Endpoint

### Endpoint

```
POST /exercises/debug-upload
```

### Purpose

Use this endpoint to debug file upload issues. It returns detailed information about file upload status and PHP configuration.

### Request Body (FormData)

```javascript
const formData = new FormData();
formData.append('video', videoFile);
formData.append('image', imageFile);
```

### Response

```json
{
  "status": "success",
  "debug": {
    "has_video": true,
    "has_image": true,
    "all_files": {
      "video": {},
      "image": {}
    },
    "php_upload_max_filesize": "2M",
    "php_post_max_size": "8M",
    "php_max_file_uploads": "20",
    "video_info": {
      "is_valid": true,
      "size": 32,
      "mime_type": "video/mp4",
      "original_name": "test_video.mp4",
      "extension": "mp4",
      "error": ""
    },
    "image_info": {
      "is_valid": true,
      "size": 70,
      "mime_type": "image/png",
      "original_name": "test_image.jpg",
      "extension": "jpg",
      "error": ""
    }
  }
}
```

## 5. Error Handling

### Common Error Responses

#### Validation Errors

```json
{
  "status": "error",
  "message": "Validation failed",
  "errors": {
    "video": ["The video field must be a file of type: mp4, avi, mov, wmv, flv, webm."],
    "image": ["The image field must be a file of type: jpeg, png, jpg, gif, webp."]
  }
}
```

#### File Upload Errors

```json
{
  "status": "error",
  "message": "Video upload failed",
  "error": "File upload error: The file was not uploaded due to an unknown error.",
  "debug": {
    "file_size": 1048576,
    "max_size": 2097152,
    "mime_type": "video/mp4",
    "original_name": "large_video.mp4"
  }
}
```

#### Storage Errors

```json
{
  "status": "error",
  "message": "Failed to store video file",
  "error": "Storage error - check directory permissions"
}
```

### Frontend Error Handling

```javascript
const handleUploadError = (error, response) => {
  if (response && response.errors) {
    // Validation errors
    Object.keys(response.errors).forEach((field) => {
      console.error(`${field}: ${response.errors[field].join(', ')}`);
    });
  } else if (response && response.debug) {
    // File upload errors with debug info
    console.error('Upload error:', response.error);
    console.error('Debug info:', response.debug);
  } else {
    // Generic error
    console.error('Upload failed:', error);
  }
};
```

## 6. File URL Access

### Generated URLs

After successful upload, the API returns URLs for uploaded files:

- **Image URL**: `/storage/exercises/images/exercise_1757941064_eaZ0yulB1F.jpg`
- **Video URL**: `/storage/exercises/videos/exercise_1757941064_15RwYtYGZc.mp4`

### Full URLs

To access files, prepend your domain:

```javascript
const fullImageUrl = `http://localhost:8000${exercise.image_url}`;
const fullVideoUrl = `http://localhost:8000${exercise.video_url}`;
```

### Displaying Files

```jsx
// Display image
<img src={`http://localhost:8000${exercise.image_url}`} alt={exercise.name} />

// Display video
<video controls>
  <source src={`http://localhost:8000${exercise.video_url}`} type="video/mp4" />
  Your browser does not support the video tag.
</video>
```

## 7. CSS Styling Example

```css
.exercise-upload-form {
  max-width: 600px;
  margin: 0 auto;
  padding: 20px;
  border: 1px solid #ddd;
  border-radius: 8px;
}

.form-group {
  margin-bottom: 20px;
}

.form-group label {
  display: block;
  margin-bottom: 5px;
  font-weight: bold;
}

.form-group input,
.form-group textarea {
  width: 100%;
  padding: 8px;
  border: 1px solid #ccc;
  border-radius: 4px;
  font-size: 14px;
}

.form-row {
  display: flex;
  gap: 15px;
}

.form-row .form-group {
  flex: 1;
}

.file-info {
  margin-top: 5px;
  padding: 5px;
  background-color: #f0f0f0;
  border-radius: 4px;
  font-size: 12px;
}

.error-message {
  background-color: #fee;
  color: #c33;
  padding: 10px;
  border-radius: 4px;
  margin-bottom: 20px;
}

button {
  background-color: #007bff;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 16px;
}

button:disabled {
  background-color: #ccc;
  cursor: not-allowed;
}

button:hover:not(:disabled) {
  background-color: #0056b3;
}
```

## 8. Complete Workflow Example

```javascript
// Complete workflow for creating an exercise with file uploads
const createExerciseWithFiles = async () => {
  try {
    // 1. Login as admin
    const token = await loginAdmin();

    // 2. Create workout
    const workoutId = await createWorkout({
      name: 'Morning Workout',
      description: 'A great morning workout routine',
      difficulty: 'beginner',
      type: 'full_body',
      duration_minutes: 30,
    });

    // 3. Get files from input elements
    const videoFile = document.getElementById('video-input').files[0];
    const imageFile = document.getElementById('image-input').files[0];

    // 4. Create exercise with files
    const uploader = new ExerciseUploader('/api/v1', token);
    const result = await uploader.createExercise(
      workoutId,
      {
        name: 'Push-ups',
        instructions: 'Do 10 push-ups with proper form',
        duration_seconds: '60',
        repetitions: '10',
        sets: '3',
        order: '1',
      },
      videoFile,
      imageFile
    );

    if (result.success) {
      console.log('Exercise created successfully!');
      console.log('Image URL:', result.exercise.image_url);
      console.log('Video URL:', result.exercise.video_url);
    } else {
      console.error('Failed to create exercise:', result.error);
    }
  } catch (error) {
    console.error('Workflow failed:', error);
  }
};
```

## 9. Testing Checklist

- [ ] Admin login works correctly
- [ ] Workout creation includes all required fields
- [ ] File validation works for both video and image files
- [ ] File size limits are enforced (100MB for video, 10MB for image)
- [ ] File type validation works correctly
- [ ] Upload progress is shown to user
- [ ] Error messages are displayed clearly
- [ ] Success response includes file URLs
- [ ] Files are accessible via returned URLs
- [ ] Form resets after successful upload

## 10. Production Considerations

### Environment Variables

```javascript
const API_BASE_URL = process.env.REACT_APP_API_URL || 'http://localhost:8000/api/v1';
const MAX_VIDEO_SIZE = process.env.REACT_APP_MAX_VIDEO_SIZE || 100 * 1024 * 1024; // 100MB
const MAX_IMAGE_SIZE = process.env.REACT_APP_MAX_IMAGE_SIZE || 10 * 1024 * 1024; // 10MB
```

### Upload Progress

```javascript
const uploadWithProgress = async (formData, onProgress) => {
  return new Promise((resolve, reject) => {
    const xhr = new XMLHttpRequest();

    xhr.upload.addEventListener('progress', (e) => {
      if (e.lengthComputable) {
        const percentComplete = (e.loaded / e.total) * 100;
        onProgress(percentComplete);
      }
    });

    xhr.addEventListener('load', () => {
      if (xhr.status === 200) {
        resolve(JSON.parse(xhr.responseText));
      } else {
        reject(new Error('Upload failed'));
      }
    });

    xhr.addEventListener('error', () => {
      reject(new Error('Network error'));
    });

    xhr.open('POST', '/api/v1/exercises');
    xhr.setRequestHeader('Authorization', `Bearer ${token}`);
    xhr.send(formData);
  });
};
```

This guide provides everything needed to implement exercise uploads with video and image files in your frontend application!
