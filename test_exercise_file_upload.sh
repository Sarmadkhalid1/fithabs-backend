#!/bin/bash

# Test script for Exercise API with file uploads
# Make sure to replace YOUR_TOKEN with actual authentication token

BASE_URL="http://localhost:8000/api/v1"
TOKEN="YOUR_TOKEN"

echo "🧪 Testing Exercise API with File Uploads"
echo "========================================"

# Test 1: Create exercise with image and video files
echo -e "\n1️⃣ Creating exercise with file uploads..."
curl -X POST "$BASE_URL/exercises" \
  -H "Authorization: Bearer $TOKEN" \
  -F "workout_id=4" \
  -F "name=Test Exercise with Files" \
  -F "instructions=Test instructions for file upload" \
  -F "duration_seconds=30" \
  -F "repetitions=10" \
  -F "sets=3" \
  -F "rest_seconds=60" \
  -F "order=1" \
  -F "image=@/path/to/test-image.jpg" \
  -F "video=@/path/to/test-video.mp4" \
  -w "\nStatus: %{http_code}\n" | jq '.'

# Test 2: Create exercise with only image
echo -e "\n2️⃣ Creating exercise with image only..."
curl -X POST "$BASE_URL/exercises" \
  -H "Authorization: Bearer $TOKEN" \
  -F "workout_id=4" \
  -F "name=Exercise with Image Only" \
  -F "instructions=Test instructions for image upload" \
  -F "duration_seconds=45" \
  -F "repetitions=15" \
  -F "sets=2" \
  -F "rest_seconds=30" \
  -F "order=2" \
  -F "image=@/path/to/test-image.jpg" \
  -w "\nStatus: %{http_code}\n" | jq '.'

# Test 3: Create exercise with only video
echo -e "\n3️⃣ Creating exercise with video only..."
curl -X POST "$BASE_URL/exercises" \
  -H "Authorization: Bearer $TOKEN" \
  -F "workout_id=4" \
  -F "name=Exercise with Video Only" \
  -F "instructions=Test instructions for video upload" \
  -F "duration_seconds=60" \
  -F "repetitions=20" \
  -F "sets=4" \
  -F "rest_seconds=45" \
  -F "order=3" \
  -F "video=@/path/to/test-video.mp4" \
  -w "\nStatus: %{http_code}\n" | jq '.'

# Test 4: Create exercise without files (URLs only)
echo -e "\n4️⃣ Creating exercise with URLs only..."
curl -X POST "$BASE_URL/exercises" \
  -H "Authorization: Bearer $TOKEN" \
  -F "workout_id=4" \
  -F "name=Exercise with URLs" \
  -F "instructions=Test instructions for URL upload" \
  -F "duration_seconds=30" \
  -F "repetitions=12" \
  -F "sets=3" \
  -F "rest_seconds=60" \
  -F "order=4" \
  -F "image_url=https://example.com/image.jpg" \
  -F "video_url=https://example.com/video.mp4" \
  -w "\nStatus: %{http_code}\n" | jq '.'

# Test 5: Update exercise with new files
echo -e "\n5️⃣ Updating exercise with new files..."
curl -X PUT "$BASE_URL/exercises/27" \
  -H "Authorization: Bearer $TOKEN" \
  -F "name=Updated Exercise with Files" \
  -F "instructions=Updated instructions" \
  -F "image=@/path/to/new-image.jpg" \
  -F "video=@/path/to/new-video.mp4" \
  -w "\nStatus: %{http_code}\n" | jq '.'

echo -e "\n✅ Exercise API file upload testing completed!"
echo "========================================"
echo ""
echo "📝 Notes:"
echo "- Replace /path/to/test-image.jpg with actual image file path"
echo "- Replace /path/to/test-video.mp4 with actual video file path"
echo "- Make sure files exist before running the tests"
echo "- Check storage/app/public/exercises/ directory for uploaded files"
