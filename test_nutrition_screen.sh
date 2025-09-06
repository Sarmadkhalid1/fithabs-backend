#!/bin/bash

# Test script for Nutrition Screen endpoints
echo "🥗 Testing Nutrition Screen Endpoints"
echo "====================================="

# Base URL
BASE_URL="http://localhost:8000/api/v1"

# Login to get token
echo "🔐 Logging in with test user..."
LOGIN_RESPONSE=$(curl -s -X POST "$BASE_URL/login" \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "password123"
  }')

TOKEN=$(echo $LOGIN_RESPONSE | grep -o '"token":"[^"]*"' | cut -d'"' -f4)

if [ -z "$TOKEN" ]; then
    echo "❌ Login failed"
    echo "Response: $LOGIN_RESPONSE"
    exit 1
fi

echo "✅ Login successful"
echo "Token: $TOKEN"
echo ""

# Test 1: Recipe of the Day
echo "🌟 Test 1: Recipe of the Day"
echo "---------------------------"
curl -s -X GET "$BASE_URL/recipes/recipe-of-the-day" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" | grep -o '"name":"[^"]*"'
echo ""

# Test 2: Recommendations
echo "📋 Test 2: Recommendations"
echo "-------------------------"
curl -s -X GET "$BASE_URL/recipes/recommendations" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" | grep -o '"name":"[^"]*"' | head -5
echo ""

# Test 3: Complete Nutrition Screen
echo "📱 Test 3: Complete Nutrition Screen"
echo "-----------------------------------"
curl -s -X GET "$BASE_URL/recipes/nutrition-screen" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" | head -c 400
echo ""

# Test 4: Detailed Recipe of the Day
echo "🔍 Test 4: Detailed Recipe of the Day"
echo "-----------------------------------"
curl -s -X GET "$BASE_URL/recipes/recipe-of-the-day" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json"
echo ""

# Test 5: Detailed Recommendations
echo "🔍 Test 5: Detailed Recommendations"
echo "----------------------------------"
curl -s -X GET "$BASE_URL/recipes/recommendations" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" | head -c 600
echo ""

echo "✅ Nutrition screen tests completed!"
echo ""
echo "📝 Summary:"
echo "- Recipe of the Day: GET /api/v1/recipes/recipe-of-the-day"
echo "- Recommendations: GET /api/v1/recipes/recommendations"
echo "- Complete Screen: GET /api/v1/recipes/nutrition-screen"
echo ""
echo "🎯 Response Structure:"
echo "- Recipe of the Day: id, name, image_url, calories_per_serving, meal_type, is_favorite, tag"
echo "- Recommendations: section_1 (2 recipes), section_2 (3 recipes)"
echo "- Each recipe: id, name, image_url, calories_per_serving, meal_type"
echo ""
echo "📱 Perfect for your Nutrition Screen UI!"
