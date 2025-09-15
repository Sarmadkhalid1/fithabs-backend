# 📁 Exercise File Upload API - Frontend Integration Guide

## 📱 **Overview**

The Exercise API now supports file uploads for images and videos. This guide covers how to properly send form data with binary files to create and update exercises.

## 🔐 **Authentication**

All endpoints require user authentication via Bearer token:

```javascript
const headers = {
  Authorization: `Bearer ${userToken}`,
  // Note: Don't set Content-Type for multipart/form-data
  // The browser will set it automatically with boundary
};
```

## 🚀 **API Endpoints**

### **1. Create Exercise with File Upload**

**Endpoint:** `POST /api/v1/exercises`

**Content-Type:** `multipart/form-data`

**Form Data Fields:**

- `workout_id` (required): ID of the workout
- `name` (required): Exercise name
- `instructions` (optional): Exercise instructions
- `duration_seconds` (optional): Duration in seconds
- `repetitions` (optional): Number of repetitions
- `sets` (optional): Number of sets
- `rest_seconds` (optional): Rest time in seconds
- `order` (optional): Exercise order in workout
- `image` (optional): Image file (jpeg, png, jpg, gif, max 10MB)
- `video` (optional): Video file (mp4, avi, mov, wmv, max 100MB)
- `image_url` (optional): External image URL (alternative to file upload)
- `video_url` (optional): External video URL (alternative to file upload)

**Response:**

```json
{
  "status": "success",
  "message": "Exercise created successfully",
  "data": {
    "id": 27,
    "workout_id": 4,
    "name": "exercise number 2",
    "instructions": "instructions are there",
    "duration_seconds": 30,
    "repetitions": 10,
    "sets": 3,
    "rest_seconds": 60,
    "order": 1,
    "image_url": "http://localhost:8000/storage/exercises/images/exercise_1694723991_abc123def.jpg",
    "video_url": "http://localhost:8000/storage/exercises/videos/exercise_1694723991_xyz789ghi.mp4",
    "created_at": "2025-09-14T19:59:51.000000Z",
    "updated_at": "2025-09-14T19:59:51.000000Z"
  }
}
```

### **2. Update Exercise with File Upload**

**Endpoint:** `PUT /api/v1/exercises/{id}`

**Content-Type:** `multipart/form-data`

**Form Data Fields:** Same as create, but all fields are optional

**Response:** Same format as create response

## 🎨 **Frontend Implementation Examples**

### **React Native Implementation**

```javascript
import { launchImageLibrary, launchCamera } from 'react-native-image-picker';

const createExerciseWithFiles = async (exerciseData, imageFile, videoFile) => {
  try {
    const formData = new FormData();

    // Add text fields
    formData.append('workout_id', exerciseData.workout_id);
    formData.append('name', exerciseData.name);
    formData.append('instructions', exerciseData.instructions);
    formData.append('duration_seconds', exerciseData.duration_seconds);
    formData.append('repetitions', exerciseData.repetitions);
    formData.append('sets', exerciseData.sets);
    formData.append('rest_seconds', exerciseData.rest_seconds);
    formData.append('order', exerciseData.order);

    // Add image file if provided
    if (imageFile) {
      formData.append('image', {
        uri: imageFile.uri,
        type: imageFile.type,
        name: imageFile.fileName || 'image.jpg',
      });
    }

    // Add video file if provided
    if (videoFile) {
      formData.append('video', {
        uri: videoFile.uri,
        type: videoFile.type,
        name: videoFile.fileName || 'video.mp4',
      });
    }

    const response = await fetch('/api/v1/exercises', {
      method: 'POST',
      headers: {
        Authorization: `Bearer ${userToken}`,
        // Don't set Content-Type, let the browser set it
      },
      body: formData,
    });

    const data = await response.json();

    if (data.status === 'success') {
      return data.data;
    } else {
      throw new Error(data.message);
    }
  } catch (error) {
    console.error('Error creating exercise:', error);
    throw error;
  }
};

// Image picker function
const pickImage = () => {
  return new Promise((resolve, reject) => {
    const options = {
      mediaType: 'photo',
      quality: 0.8,
      maxWidth: 1920,
      maxHeight: 1080,
    };

    launchImageLibrary(options, (response) => {
      if (response.didCancel || response.error) {
        reject(new Error('Image picker cancelled or error'));
      } else {
        resolve(response.assets[0]);
      }
    });
  });
};

// Video picker function
const pickVideo = () => {
  return new Promise((resolve, reject) => {
    const options = {
      mediaType: 'video',
      quality: 0.8,
    };

    launchImageLibrary(options, (response) => {
      if (response.didCancel || response.error) {
        reject(new Error('Video picker cancelled or error'));
      } else {
        resolve(response.assets[0]);
      }
    });
  });
};
```

### **Web Implementation**

```javascript
const createExerciseWithFiles = async (exerciseData, imageFile, videoFile) => {
  try {
    const formData = new FormData();

    // Add text fields
    Object.keys(exerciseData).forEach((key) => {
      formData.append(key, exerciseData[key]);
    });

    // Add image file if provided
    if (imageFile) {
      formData.append('image', imageFile);
    }

    // Add video file if provided
    if (videoFile) {
      formData.append('video', videoFile);
    }

    const response = await fetch('/api/v1/exercises', {
      method: 'POST',
      headers: {
        Authorization: `Bearer ${userToken}`,
        // Don't set Content-Type, let the browser set it
      },
      body: formData,
    });

    const data = await response.json();

    if (data.status === 'success') {
      return data.data;
    } else {
      throw new Error(data.message);
    }
  } catch (error) {
    console.error('Error creating exercise:', error);
    throw error;
  }
};

// File input handler
const handleFileInput = (event, fileType) => {
  const file = event.target.files[0];
  if (file) {
    // Validate file type and size
    if (fileType === 'image') {
      const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
      if (!validTypes.includes(file.type)) {
        throw new Error('Invalid image type');
      }
      if (file.size > 10 * 1024 * 1024) {
        // 10MB
        throw new Error('Image too large');
      }
    } else if (fileType === 'video') {
      const validTypes = ['video/mp4', 'video/avi', 'video/mov', 'video/wmv'];
      if (!validTypes.includes(file.type)) {
        throw new Error('Invalid video type');
      }
      if (file.size > 100 * 1024 * 1024) {
        // 100MB
        throw new Error('Video too large');
      }
    }
    return file;
  }
  return null;
};
```

### **Complete Form Component**

```javascript
import React, { useState } from 'react';
import { View, Text, TextInput, TouchableOpacity, Alert } from 'react-native';

const ExerciseForm = ({ workoutId, onSave }) => {
  const [formData, setFormData] = useState({
    workout_id: workoutId,
    name: '',
    instructions: '',
    duration_seconds: '',
    repetitions: '',
    sets: '',
    rest_seconds: '',
    order: '',
  });
  const [imageFile, setImageFile] = useState(null);
  const [videoFile, setVideoFile] = useState(null);
  const [loading, setLoading] = useState(false);

  const handleInputChange = (field, value) => {
    setFormData((prev) => ({
      ...prev,
      [field]: value,
    }));
  };

  const handleImagePick = async () => {
    try {
      const image = await pickImage();
      setImageFile(image);
    } catch (error) {
      Alert.alert('Error', 'Failed to pick image');
    }
  };

  const handleVideoPick = async () => {
    try {
      const video = await pickVideo();
      setVideoFile(video);
    } catch (error) {
      Alert.alert('Error', 'Failed to pick video');
    }
  };

  const handleSubmit = async () => {
    try {
      setLoading(true);
      const exercise = await createExerciseWithFiles(formData, imageFile, videoFile);
      onSave(exercise);
      Alert.alert('Success', 'Exercise created successfully');
    } catch (error) {
      Alert.alert('Error', error.message);
    } finally {
      setLoading(false);
    }
  };

  return (
    <View style={styles.container}>
      <TextInput
        style={styles.input}
        placeholder="Exercise Name"
        value={formData.name}
        onChangeText={(value) => handleInputChange('name', value)}
      />

      <TextInput
        style={styles.input}
        placeholder="Instructions"
        value={formData.instructions}
        onChangeText={(value) => handleInputChange('instructions', value)}
        multiline
      />

      <TextInput
        style={styles.input}
        placeholder="Duration (seconds)"
        value={formData.duration_seconds}
        onChangeText={(value) => handleInputChange('duration_seconds', value)}
        keyboardType="numeric"
      />

      <TextInput
        style={styles.input}
        placeholder="Repetitions"
        value={formData.repetitions}
        onChangeText={(value) => handleInputChange('repetitions', value)}
        keyboardType="numeric"
      />

      <TextInput
        style={styles.input}
        placeholder="Sets"
        value={formData.sets}
        onChangeText={(value) => handleInputChange('sets', value)}
        keyboardType="numeric"
      />

      <TextInput
        style={styles.input}
        placeholder="Rest (seconds)"
        value={formData.rest_seconds}
        onChangeText={(value) => handleInputChange('rest_seconds', value)}
        keyboardType="numeric"
      />

      <TouchableOpacity style={styles.button} onPress={handleImagePick}>
        <Text style={styles.buttonText}>{imageFile ? 'Change Image' : 'Pick Image'}</Text>
      </TouchableOpacity>

      <TouchableOpacity style={styles.button} onPress={handleVideoPick}>
        <Text style={styles.buttonText}>{videoFile ? 'Change Video' : 'Pick Video'}</Text>
      </TouchableOpacity>

      <TouchableOpacity
        style={[styles.button, styles.submitButton]}
        onPress={handleSubmit}
        disabled={loading}
      >
        <Text style={styles.buttonText}>{loading ? 'Creating...' : 'Create Exercise'}</Text>
      </TouchableOpacity>
    </View>
  );
};

const styles = {
  container: {
    padding: 20,
  },
  input: {
    borderWidth: 1,
    borderColor: '#ccc',
    borderRadius: 8,
    padding: 12,
    marginBottom: 15,
    fontSize: 16,
  },
  button: {
    backgroundColor: '#2ECC71',
    padding: 15,
    borderRadius: 8,
    marginBottom: 10,
    alignItems: 'center',
  },
  submitButton: {
    backgroundColor: '#27AE60',
  },
  buttonText: {
    color: '#FFFFFF',
    fontSize: 16,
    fontWeight: 'bold',
  },
};

export default ExerciseForm;
```

## ⚠️ **Error Handling**

### **Common Error Responses**

```javascript
// 422 - Validation Error
{
  "status": "error",
  "message": "Validation failed",
  "errors": {
    "image": ["The image must be a file of type: jpeg, png, jpg, gif."],
    "video": ["The video must be a file of type: mp4, avi, mov, wmv."]
  }
}

// 413 - File Too Large
{
  "status": "error",
  "message": "File too large",
  "error": "The image may not be greater than 10240 kilobytes."
}

// 500 - Server Error
{
  "status": "error",
  "message": "Failed to create exercise",
  "error": "Storage disk not configured"
}
```

## 📁 **File Storage**

### **Storage Structure**

```
storage/
├── app/
│   └── public/
│       └── exercises/
│           ├── images/
│           │   ├── exercise_1694723991_abc123def.jpg
│           │   └── exercise_1694723992_xyz789ghi.png
│           └── videos/
│               ├── exercise_1694723993_def456jkl.mp4
│               └── exercise_1694723994_ghi789mno.mov
```

### **File Naming Convention**

- Format: `exercise_{timestamp}_{random_string}.{extension}`
- Example: `exercise_1694723991_abc123def.jpg`

### **Accessing Files**

- URL: `http://your-domain.com/storage/exercises/images/filename.jpg`
- Local: `storage/app/public/exercises/images/filename.jpg`

## 🧪 **Testing**

### **Test Script**

Use the provided test script to verify file upload functionality:

```bash
# Make sure to replace file paths with actual files
./test_exercise_file_upload.sh
```

### **Manual Testing Checklist**

- [ ] Upload image file (jpeg, png, jpg, gif)
- [ ] Upload video file (mp4, avi, mov, wmv)
- [ ] Test file size limits (10MB image, 100MB video)
- [ ] Test invalid file types
- [ ] Test mixed file and URL uploads
- [ ] Test file updates (replace existing files)
- [ ] Verify files are accessible via URLs

## 📱 **Mobile App Integration Tips**

1. **File Compression**: Compress images/videos before upload
2. **Progress Indicators**: Show upload progress for large files
3. **Offline Support**: Queue uploads when offline
4. **File Validation**: Validate files before upload
5. **Error Recovery**: Handle upload failures gracefully
6. **Storage Management**: Clean up old files when updating

---

**Need Help?** Check the test script or contact the backend team for any API-related questions.
