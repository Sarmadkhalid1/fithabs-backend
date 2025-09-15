# Postman Testing Guide for Exercise Upload

## Quick Setup

### 1. Import Collection

1. Open Postman
2. Click "Import" button
3. Select the `FitHabs_Exercise_Upload_Postman_Collection.json` file
4. The collection will be imported with all requests

### 2. Environment Variables

The collection uses these variables (automatically set):

- `base_url`: `http://localhost:8000/api/v1`
- `admin_token`: (set automatically after login)
- `workout_id`: (set automatically after workout creation)

## Step-by-Step Testing

### Step 1: Admin Login

**Request**: `1. Admin Login`

- **Method**: POST
- **URL**: `{{base_url}}/admin-login`
- **Body**: JSON

```json
{
  "email": "admin@example.com",
  "password": "admin123"
}
```

- **Expected Response**: 200 OK with token
- **Auto-sets**: `admin_token` variable

### Step 2: Create Workout

**Request**: `2. Create Workout`

- **Method**: POST
- **URL**: `{{base_url}}/workouts`
- **Headers**: `Authorization: Bearer {{admin_token}}`
- **Body**: JSON

```json
{
  "name": "Test Workout for Exercise Upload",
  "description": "A test workout for testing exercise uploads",
  "difficulty": "beginner",
  "type": "full_body",
  "duration_minutes": 30,
  "created_by_admin": true
}
```

- **Expected Response**: 200 OK with workout data
- **Auto-sets**: `workout_id` variable

### Step 3: Debug Upload (Optional)

**Request**: `3. Debug Upload (Test Files)`

- **Method**: POST
- **URL**: `{{base_url}}/exercises/debug-upload`
- **Headers**: `Authorization: Bearer {{admin_token}}`
- **Body**: Form-data
  - `video`: Select a video file
  - `image`: Select an image file
- **Purpose**: Test file upload detection and PHP configuration

### Step 4: Create Exercise with Both Files

**Request**: `4. Create Exercise with Video and Image`

- **Method**: POST
- **URL**: `{{base_url}}/exercises`
- **Headers**: `Authorization: Bearer {{admin_token}}`
- **Body**: Form-data
  - `workout_id`: `{{workout_id}}` (auto-filled)
  - `name`: "Test Exercise with Files"
  - `instructions`: "Test exercise instructions"
  - `video`: Select video file
  - `image`: Select image file
  - `duration_seconds`: "60"
  - `repetitions`: "10"
  - `sets`: "3"
  - `order`: "1"
- **Expected Response**: 201 Created with file URLs

### Step 5: Test File Access

**Requests**: `9. Test File Access (Image)` and `10. Test File Access (Video)`

- **Method**: GET
- **URL**: Direct file URLs from step 4 response
- **Expected Response**: 200 OK with file content

## Additional Test Cases

### Exercise with Video Only

**Request**: `5. Create Exercise with Video Only`

- Same as step 4 but only upload video file
- Leave image field empty

### Exercise with Image Only

**Request**: `6. Create Exercise with Image Only`

- Same as step 4 but only upload image file
- Leave video field empty

### Exercise without Files

**Request**: `7. Create Exercise without Files`

- Uses JSON body instead of form-data
- No file uploads

### View Created Exercises

**Request**: `8. Get Workout Exercises`

- **Method**: GET
- **URL**: `{{base_url}}/workouts/{{workout_id}}/exercises`
- Shows all exercises created for the workout

## File Requirements

### Video Files

- **Formats**: MP4, AVI, MOV, WMV, FLV, WebM
- **Max Size**: 100MB
- **Field Name**: `video`

### Image Files

- **Formats**: JPEG, PNG, JPG, GIF, WebP
- **Max Size**: 10MB
- **Field Name**: `image`

## Expected Responses

### Success Response (201 Created)

```json
{
  "status": "success",
  "message": "Exercise created successfully",
  "data": {
    "id": 28,
    "workout_id": "13",
    "name": "Test Exercise with Files",
    "instructions": "Test exercise instructions",
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

### Error Response (422 Validation Error)

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

## Troubleshooting

### Common Issues

1. **401 Unauthorized**
   - Make sure you've run the login request first
   - Check that the token is properly set in the Authorization header

2. **422 Validation Error**
   - Check file types match supported formats
   - Ensure file sizes are within limits
   - Verify all required fields are provided

3. **500 Internal Server Error**
   - Check server logs for detailed error messages
   - Verify storage directories exist and have proper permissions

4. **File Not Accessible**
   - Ensure Laravel storage link is created: `php artisan storage:link`
   - Check file permissions on storage directories

### Debug Tips

1. Use the debug upload endpoint first to test file detection
2. Check the console logs in Postman for auto-set variables
3. Verify file URLs in the response are accessible
4. Test with small files first, then larger ones

## Collection Runner

You can also run the entire collection automatically:

1. Click on the collection name
2. Click "Run collection"
3. Select the requests you want to run
4. Click "Run FitHabs Exercise Upload API"

This will execute all requests in sequence, automatically setting variables between requests.

## Environment Setup

For different environments, create environment variables:

### Development

- `base_url`: `http://localhost:8000/api/v1`

### Staging

- `base_url`: `https://staging-api.fithabs.com/api/v1`

### Production

- `base_url`: `https://api.fithabs.com/api/v1`

This allows you to easily switch between environments without changing the requests.
