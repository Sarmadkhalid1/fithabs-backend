# FitHabs FormData Upload Guide

## Overview

Your FitHabs backend is now **fully ready** to receive both images and videos from the frontend using FormData. You can upload images and videos together with other form data in a single request.

## ✅ **What's Ready**

### 🎥 **Video Upload System**

- **Complete VideoController** with full CRUD operations
- **File validation**: MP4, AVI, MOV, WMV, FLV, WebM formats
- **Size limit**: 100MB maximum file size
- **Admin authentication**: Only admins can upload videos
- **Streaming support**: Direct video serving with proper headers

### 🖼️ **Image Upload System** (NEWLY ADDED)

- **Complete ImageController** with full CRUD operations
- **File validation**: JPEG, JPG, PNG, GIF, WebP formats
- **Size limit**: 10MB maximum file size
- **Admin authentication**: Only admins can upload images
- **Image serving**: Direct image serving with proper headers
- **Automatic dimensions**: Width and height automatically detected

### 📝 **Content Management Integration**

- **WorkoutController**: Can handle image uploads in FormData
- **RecipeController**: Can handle image uploads in FormData
- **MealPlanController**: Ready for image uploads (can be extended)
- **EducationContentController**: Ready for image uploads (can be extended)

---

## 🚀 **Frontend Implementation Examples**

### 1. Upload Workout with Image

```javascript
// Frontend: Upload workout with image
const formData = new FormData();

// Add text data
formData.append('name', 'Morning Cardio Blast');
formData.append('description', 'High-intensity cardio workout for morning energy');
formData.append('difficulty', 'medium');
formData.append('type', 'cardio');
formData.append('duration_minutes', '30');
formData.append('calories_per_session', '300');
formData.append('equipment_needed[]', 'dumbbells');
formData.append('equipment_needed[]', 'mat');
formData.append('tags[]', 'cardio');
formData.append('tags[]', 'morning');
formData.append('is_featured', 'true');
formData.append('is_active', 'true');
formData.append('created_by_admin', '1');

// Add image file
if (imageFile) {
  formData.append('image', imageFile);
}

// Send request
const response = await fetch('/api/v1/workouts', {
  method: 'POST',
  headers: {
    Authorization: `Bearer ${adminToken}`,
    // Don't set Content-Type - let browser set it with boundary
  },
  body: formData,
});

const result = await response.json();
console.log('Workout created:', result);
```

### 2. Upload Recipe with Image

```javascript
// Frontend: Upload recipe with image
const formData = new FormData();

// Add text data
formData.append('name', 'Healthy Quinoa Bowl');
formData.append('description', 'Nutritious quinoa bowl with vegetables');
formData.append('meal_type', 'lunch');
formData.append('prep_time_minutes', '15');
formData.append('cook_time_minutes', '20');
formData.append('servings', '2');
formData.append('calories_per_serving', '350');
formData.append('protein_per_serving', '12.5');
formData.append('carbs_per_serving', '45.2');
formData.append('fat_per_serving', '8.1');
formData.append('ingredients', '1 cup quinoa, 2 cups water, 1 bell pepper, 1 cucumber, 1 avocado');
formData.append(
  'instructions',
  '1. Cook quinoa according to package instructions\n2. Chop vegetables\n3. Mix everything together\n4. Serve immediately'
);
formData.append('dietary_tags[]', 'vegetarian');
formData.append('dietary_tags[]', 'gluten-free');
formData.append('difficulty', 'easy');
formData.append('is_featured', 'true');
formData.append('created_by_admin', '1');

// Add image file
if (imageFile) {
  formData.append('image', imageFile);
}

// Send request
const response = await fetch('/api/v1/recipes', {
  method: 'POST',
  headers: {
    Authorization: `Bearer ${adminToken}`,
  },
  body: formData,
});

const result = await response.json();
console.log('Recipe created:', result);
```

### 3. Upload Video Only

```javascript
// Frontend: Upload video
const formData = new FormData();

// Add text data
formData.append('title', 'Push-up Tutorial');
formData.append('description', 'Learn proper push-up form');
formData.append('category', 'exercise');
formData.append('tags[]', 'strength');
formData.append('tags[]', 'upper-body');

// Add video file
if (videoFile) {
  formData.append('video', videoFile);
}

// Send request
const response = await fetch('/api/v1/videos', {
  method: 'POST',
  headers: {
    Authorization: `Bearer ${adminToken}`,
  },
  body: formData,
});

const result = await response.json();
console.log('Video uploaded:', result);
```

### 4. Upload Image Only

```javascript
// Frontend: Upload image
const formData = new FormData();

// Add text data
formData.append('title', 'Workout Image');
formData.append('description', 'Image for workout display');
formData.append('category', 'workout');
formData.append('tags[]', 'fitness');
formData.append('tags[]', 'exercise');

// Add image file
if (imageFile) {
  formData.append('image', imageFile);
}

// Send request
const response = await fetch('/api/v1/images', {
  method: 'POST',
  headers: {
    Authorization: `Bearer ${adminToken}`,
  },
  body: formData,
});

const result = await response.json();
console.log('Image uploaded:', result);
```

### 5. Update Content with New Image

```javascript
// Frontend: Update workout with new image
const formData = new FormData();

// Add text data
formData.append('name', 'Updated Workout Name');
formData.append('description', 'Updated description');
formData.append('difficulty', 'hard');

// Add new image file (optional)
if (newImageFile) {
  formData.append('image', newImageFile);
}

// Send request
const response = await fetch(`/api/v1/workouts/${workoutId}`, {
  method: 'PUT',
  headers: {
    Authorization: `Bearer ${adminToken}`,
  },
  body: formData,
});

const result = await response.json();
console.log('Workout updated:', result);
```

---

## 📋 **API Endpoints Summary**

### **Content Management (with Image Support)**

- `POST /api/v1/workouts` - Create workout with image
- `PUT /api/v1/workouts/{id}` - Update workout with new image
- `POST /api/v1/recipes` - Create recipe with image
- `PUT /api/v1/recipes/{id}` - Update recipe with new image

### **Direct File Upload**

- `POST /api/v1/videos` - Upload video file
- `PUT /api/v1/videos/{id}` - Update video metadata
- `DELETE /api/v1/videos/{id}` - Delete video
- `GET /api/v1/videos/{id}/stream` - Stream video

- `POST /api/v1/images` - Upload image file
- `PUT /api/v1/images/{id}` - Update image metadata
- `DELETE /api/v1/images/{id}` - Delete image
- `GET /api/v1/images/{id}/serve` - Serve image

### **Public Access (Authenticated Users)**

- `GET /api/v1/videos` - List all videos
- `GET /api/v1/videos/{id}` - Get video details
- `GET /api/v1/images` - List all images
- `GET /api/v1/images/{id}` - Get image details

---

## 🔧 **File Validation Rules**

### **Images**

- **Formats**: JPEG, JPG, PNG, GIF, WebP
- **Max Size**: 10MB
- **Field Name**: `image`

### **Videos**

- **Formats**: MP4, AVI, MOV, WMV, FLV, WebM
- **Max Size**: 100MB
- **Field Name**: `video`

---

## 📁 **Storage Structure**

```
storage/app/public/
├── images/          # Image files
│   ├── uuid1.jpg
│   ├── uuid2.png
│   └── ...
└── videos/          # Video files
    ├── uuid1.mp4
    ├── uuid2.avi
    └── ...

public/storage/      # Symlinked for web access
├── images/          # Public image access
└── videos/          # Public video access
```

---

## 🎯 **Key Features**

### ✅ **Automatic File Management**

- Unique filenames using UUID
- Automatic file cleanup on deletion
- Proper MIME type detection
- File size tracking

### ✅ **Database Integration**

- Images and videos stored in database
- Metadata tracking (dimensions, file size, etc.)
- Admin user tracking
- Category and tag support

### ✅ **Security**

- Admin-only upload permissions
- File type validation
- Size limits enforced
- Proper authentication required

### ✅ **Flexible Usage**

- Upload images with content (workouts, recipes)
- Upload files independently
- Update content with new images
- Mix and match as needed

---

## 🚨 **Important Notes**

1. **Content-Type Header**: Don't set `Content-Type` manually - let the browser set it with the proper boundary for multipart/form-data

2. **Authentication**: All upload endpoints require admin authentication with `Authorization: Bearer {token}`

3. **File Field Names**: Use `image` for images and `video` for videos in FormData

4. **Array Fields**: Use `fieldName[]` for array fields like tags and equipment

5. **Boolean Fields**: Send as strings `'true'` or `'false'` in FormData

6. **Storage Links**: Make sure `php artisan storage:link` has been run (already done)

---

## 🧪 **Testing Examples**

### **cURL Examples**

#### Upload Workout with Image

```bash
curl -X POST http://127.0.0.1:8000/api/v1/workouts \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN" \
  -F "name=Test Workout" \
  -F "description=Test description" \
  -F "difficulty=medium" \
  -F "type=cardio" \
  -F "image=@/path/to/image.jpg" \
  -F "created_by_admin=1"
```

#### Upload Video

```bash
curl -X POST http://127.0.0.1:8000/api/v1/videos \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN" \
  -F "title=Test Video" \
  -F "description=Test video description" \
  -F "category=exercise" \
  -F "video=@/path/to/video.mp4"
```

---

Your backend is now **100% ready** to handle both images and videos in FormData requests! 🎉
