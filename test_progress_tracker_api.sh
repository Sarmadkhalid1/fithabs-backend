#!/bin/bash

# Test script for Progress Tracker API endpoints
# Make sure to replace YOUR_TOKEN with actual authentication token

BASE_URL="http://localhost:8000/api/v1"
TOKEN="YOUR_TOKEN"

echo "🧪 Testing Progress Tracker API Endpoints"
echo "========================================"

# Test 1: Get progress summary
echo -e "\n1️⃣ Getting progress summary..."
curl -X GET "$BASE_URL/daily-activities/progress-summary" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -w "\nStatus: %{http_code}\n" | jq '.'

# Test 2: Get progress summary for specific date
echo -e "\n2️⃣ Getting progress summary for specific date..."
curl -X GET "$BASE_URL/daily-activities/progress-summary?date=2025-01-27" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -w "\nStatus: %{http_code}\n" | jq '.'

# Test 3: Update daily activity
echo -e "\n3️⃣ Updating daily activity..."
curl -X POST "$BASE_URL/daily-activities/update" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "steps": 8500,
    "calories_consumed": 1600,
    "calories_burned": 450,
    "sleep_time": 7.5,
    "daily_progress_percentage": 80,
    "protein_goal": 2145,
    "carbs_goal": 2145
  }' \
  -w "\nStatus: %{http_code}\n" | jq '.'

# Test 4: Update specific metrics
echo -e "\n4️⃣ Updating specific metrics..."
curl -X POST "$BASE_URL/daily-activities/update" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "steps": 10000,
    "sleep_time": 8
  }' \
  -w "\nStatus: %{http_code}\n" | jq '.'

# Test 5: Get date range activities
echo -e "\n5️⃣ Getting date range activities..."
curl -X GET "$BASE_URL/daily-activities/date-range?start_date=2025-01-20&end_date=2025-01-27" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -w "\nStatus: %{http_code}\n" | jq '.'

# Test 6: Get date range activities (default range)
echo -e "\n6️⃣ Getting date range activities (default range)..."
curl -X GET "$BASE_URL/daily-activities/date-range" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -w "\nStatus: %{http_code}\n" | jq '.'

# Test 7: Final progress summary check
echo -e "\n7️⃣ Final progress summary check..."
curl -X GET "$BASE_URL/daily-activities/progress-summary" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -w "\nStatus: %{http_code}\n" | jq '.'

echo -e "\n✅ Progress Tracker API testing completed!"
echo "========================================"
