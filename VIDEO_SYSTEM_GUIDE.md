# Video Storage System Guide

## Overview

Your FitHabs application now has a complete local video storage system that allows you to upload, manage, and serve your own exercise videos instead of relying on external copyrighted content.

## Features

✅ **Local Video Storage**: Videos are stored in your application's storage directory  
✅ **API Endpoints**: Complete REST API for video management  
✅ **Admin Controls**: Only admins can upload, update, and delete videos  
✅ **Video Streaming**: Direct video serving with proper headers  
✅ **Database Integration**: Videos are linked to exercises and workouts  
✅ **File Management**: Automatic file cleanup when videos are deleted

## Storage Structure

```
storage/app/public/videos/  # Video files
public/storage/videos/      # Symlinked for web access
```

## API Endpoints

### Public Endpoints (Authenticated Users)

- `GET /api/v1/videos` - List all active videos
- `GET /api/v1/videos/{id}` - Get video details
- `GET /api/v1/videos/{id}/stream` - Stream video file

### Admin Endpoints (Admin Only)

- `POST /api/v1/videos` - Upload new video
- `PUT /api/v1/videos/{id}` - Update video details
- `DELETE /api/v1/videos/{id}` - Delete video

## Video Upload

### Requirements

- File formats: MP4, AVI, MOV, WMV, FLV, WebM
- Maximum file size: 100MB
- Admin authentication required

### Upload Example (cURL)

```bash
curl -X POST http://localhost/api/v1/videos \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN" \
  -F "title=My Exercise Video" \
  -F "description=Detailed exercise demonstration" \
  -F "category=exercise" \
  -F "video=@/path/to/your/video.mp4" \
  -F "tags[]=strength" \
  -F "tags[]=upper-body"
```

### Upload Response

```json
{
  "success": true,
  "message": "Video uploaded successfully",
  "data": {
    "id": 1,
    "title": "My Exercise Video",
    "url": "http://localhost/storage/videos/uuid-filename.mp4",
    "duration": null,
    "file_size": 2048000,
    "category": "exercise",
    "tags": ["strength", "upper-body"]
  }
}
```

## Database Schema

### Videos Table

```sql
- id (primary key)
- title (string)
- description (text, nullable)
- filename (string) - original filename
- path (string) - storage path
- url (string) - public URL
- mime_type (string)
- file_size (bigint) - in bytes
- duration (int, nullable) - in seconds
- thumbnail_path (string, nullable)
- thumbnail_url (string, nullable)
- category (enum: exercise, workout, tutorial, other)
- tags (json, nullable)
- is_active (boolean)
- uploaded_by (foreign key to admin_users)
- created_at, updated_at
```

## Integration with Exercises

The system is already integrated with your exercise system:

1. **ExerciseSeeder** now uses local video URLs from the videos table
2. **Exercises** link to your local videos instead of YouTube
3. **Fallback system** provides placeholder URLs if no videos exist

## Current Status

✅ **6 Sample Videos** created in database  
✅ **19 Exercises** updated with local video URLs  
✅ **5 Workouts** with proper image URLs  
✅ **Storage Configuration** set up  
✅ **API Routes** configured

## Video URLs in Your Database

Your exercises now use URLs like:

- `http://localhost/storage/videos/pushups-demo.mp4`
- `http://localhost/storage/videos/squats-tutorial.mp4`
- `http://localhost/storage/videos/plank-technique.mp4`
- etc.

## Next Steps

1. **Upload Real Videos**: Use the API to upload your actual exercise videos
2. **Replace Placeholders**: The current videos are just database records - upload real files
3. **Add Thumbnails**: Optionally add video thumbnails for better UI
4. **Test Streaming**: Verify video streaming works in your frontend

## File Management

- Videos are automatically assigned UUID filenames to prevent conflicts
- Original filenames are preserved in the database
- File cleanup happens automatically when videos are deleted
- Storage symlink is already created (`php artisan storage:link`)

## Security

- Only authenticated admin users can upload/manage videos
- File type validation prevents malicious uploads
- File size limits prevent storage abuse
- Videos are served with proper MIME types

## Copyright Compliance

✅ **No External Dependencies**: All videos are stored locally  
✅ **Your Content**: Upload your own exercise demonstrations  
✅ **Copyright Safe**: No reliance on copyrighted YouTube content  
✅ **Full Control**: Complete ownership of your video content

---

Your FitHabs application now has a complete, copyright-safe video storage system ready for your own exercise content!
