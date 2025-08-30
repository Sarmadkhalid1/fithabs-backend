#!/bin/bash

# FitHabs Workout API Test Script
# Make sure to replace YOUR_TOKEN with an actual authentication token

BASE_URL="http://localhost:8000/api/v1"
TOKEN="YOUR_TOKEN"  # Replace with actual token

echo "🏋️ Testing FitHabs Workout API"
echo "================================"

# Test 1: Get all workouts
echo "1. Getting all workouts..."
curl -s -X GET "$BASE_URL/workouts" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" | jq '.'

echo -e "\n2. Getting beginner workouts..."
curl -s -X GET "$BASE_URL/workouts/difficulty/beginner" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" | jq '.'

echo -e "\n3. Getting workout details (ID: 9)..."
curl -s -X GET "$BASE_URL/workouts/9" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" | jq '.'

echo -e "\n4. Getting exercises for workout (ID: 9)..."
curl -s -X GET "$BASE_URL/workouts/9/exercises" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" | jq '.'

echo -e "\n5. Starting workout session..."
SESSION_RESPONSE=$(curl -s -X POST "$BASE_URL/workouts/9/start" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json")

echo $SESSION_RESPONSE | jq '.'

# Extract session ID (you might need to adjust this based on your response structure)
SESSION_ID=$(echo $SESSION_RESPONSE | jq -r '.data.session.id // empty')

if [ ! -z "$SESSION_ID" ]; then
    echo -e "\n6. Getting next exercise..."
    curl -s -X GET "$BASE_URL/workouts/9/exercises/next" \
      -H "Authorization: Bearer $TOKEN" \
      -H "Content-Type: application/json" | jq '.'
    
    echo -e "\n7. Getting user workout stats..."
    curl -s -X GET "$BASE_URL/user-workouts/stats" \
      -H "Authorization: Bearer $TOKEN" \
      -H "Content-Type: application/json" | jq '.'
else
    echo "⚠️  Could not extract session ID. Please check authentication."
fi

echo -e "\n✅ API test completed!"
echo "💡 Remember to replace YOUR_TOKEN with a valid authentication token"
