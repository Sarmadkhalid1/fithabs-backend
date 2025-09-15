# Exercise Upload Issues - Diagnosis and Solutions

## Issues Identified

### 1. PHP Upload Configuration Issues

- **Problem**: `upload_max_filesize=2M` and `post_max_size=8M` are too small
- **Impact**: Files larger than 2MB cannot be uploaded
- **Solution**: Increase limits to 100M for videos and 10M for images

### 2. File Upload Error Handling

- **Problem**: Generic "unknown error" messages during file upload
- **Impact**: Difficult to debug upload failures
- **Solution**: Enhanced error handling with detailed debug information

### 3. Missing Required Fields in Workout Creation

- **Problem**: `type` and `created_by_admin` fields required but not provided
- **Impact**: Cannot create workouts for testing exercise uploads
- **Solution**: Updated test script with required fields

### 4. Storage Directory Permissions

- **Problem**: Storage directories may not exist or have wrong permissions
- **Impact**: Files cannot be stored even if upload succeeds
- **Solution**: Created directories and set proper permissions

## Solutions Implemented

### 1. Updated ExerciseController Validation

```php
// Increased file size limits
'video' => 'nullable|file|mimes:mp4,avi,mov,wmv,flv,webm|max:102400', // 100MB max
'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp|max:10240', // 10MB max
```

### 2. Enhanced Error Handling

- Added detailed debug information for file upload failures
- Better error messages for storage issues
- File validation with size and type checking

### 3. Storage Directory Setup

- Created `storage/app/public/exercises/images` and `storage/app/public/exercises/videos`
- Set proper permissions (755)
- Ensured storage link exists

### 4. PHP Configuration Files

- Created `php_upload_config.ini` with proper limits
- Created `.htaccess_upload` for Apache configuration
- Provided instructions for server configuration

## Testing Steps

1. **Login as Admin**

   ```bash
   curl -X POST http://localhost:8000/api/v1/admin-login \
     -H "Content-Type: application/json" \
     -d '{"email": "admin@example.com", "password": "admin123"}'
   ```

2. **Create Workout**

   ```bash
   curl -X POST http://localhost:8000/api/v1/workouts \
     -H "Authorization: Bearer $TOKEN" \
     -H "Content-Type: application/json" \
     -d '{
       "name": "Test Workout",
       "description": "Test workout",
       "difficulty": "beginner",
       "type": "full_body",
       "duration_minutes": 30,
       "created_by_admin": true
     }'
   ```

3. **Create Exercise with Files**
   ```bash
   curl -X POST http://localhost:8000/api/v1/exercises \
     -H "Authorization: Bearer $TOKEN" \
     -F "workout_id=$WORKOUT_ID" \
     -F "name=Test Exercise" \
     -F "instructions=Test exercise" \
     -F "video=@test_video.mp4" \
     -F "image=@test_image.jpg" \
     -F "duration_seconds=60" \
     -F "repetitions=10" \
     -F "sets=3" \
     -F "order=1"
   ```

## Server Configuration Required

### For Apache (.htaccess)

Add the following to your `.htaccess` file in the public directory:

```apache
php_value upload_max_filesize 100M
php_value post_max_size 100M
php_value max_file_uploads 20
php_value max_execution_time 300
php_value max_input_time 300
php_value memory_limit 256M
```

### For Nginx

Add to your nginx configuration:

```nginx
client_max_body_size 100M;
```

### For PHP-FPM

Add to your `php.ini`:

```ini
upload_max_filesize = 100M
post_max_size = 100M
max_file_uploads = 20
max_execution_time = 300
max_input_time = 300
memory_limit = 256M
```

## Debug Endpoint

Use the debug endpoint to test file uploads:

```bash
curl -X POST http://localhost:8000/api/v1/exercises/debug-upload \
  -H "Authorization: Bearer $TOKEN" \
  -F "video=@test_video.mp4" \
  -F "image=@test_image.jpg"
```

This will return detailed information about:

- File detection status
- File sizes and types
- PHP configuration limits
- Upload errors
