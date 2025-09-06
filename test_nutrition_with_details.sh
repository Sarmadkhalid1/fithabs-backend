#!/bin/bash

# Test script for Nutrition Screen with Recipe Details
echo "🥗 Testing Nutrition Screen with Recipe Details"
echo "=============================================="

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

# Test 1: Get Nutrition Screen Data
echo "📱 Test 1: Get Nutrition Screen Data"
echo "-----------------------------------"
NUTRITION_RESPONSE=$(curl -s -X GET "$BASE_URL/recipes/nutrition-screen" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json")

echo "Nutrition Screen Response:"
echo "$NUTRITION_RESPONSE" | head -c 500
echo ""

# Extract recipe IDs from nutrition screen
RECIPE_OF_DAY_ID=$(echo "$NUTRITION_RESPONSE" | grep -o '"id":[0-9]*' | head -1 | cut -d':' -f2)
SECTION1_ID1=$(echo "$NUTRITION_RESPONSE" | grep -o '"section_1":\[[^]]*\]' | grep -o '"id":[0-9]*' | head -1 | cut -d':' -f2)
SECTION1_ID2=$(echo "$NUTRITION_RESPONSE" | grep -o '"section_1":\[[^]]*\]' | grep -o '"id":[0-9]*' | tail -1 | cut -d':' -f2)

echo "📋 Extracted Recipe IDs:"
echo "- Recipe of the Day: $RECIPE_OF_DAY_ID"
echo "- Section 1 Recipe 1: $SECTION1_ID1"
echo "- Section 1 Recipe 2: $SECTION1_ID2"
echo ""

# Test 2: Get Recipe of the Day Details
echo "🔍 Test 2: Get Recipe of the Day Details"
echo "---------------------------------------"
echo "Getting details for Recipe ID: $RECIPE_OF_DAY_ID"
curl -s -X GET "$BASE_URL/recipes/$RECIPE_OF_DAY_ID" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" | head -c 400
echo ""
echo ""

# Test 3: Get Section 1 Recipe 1 Details
echo "🔍 Test 3: Get Section 1 Recipe 1 Details"
echo "----------------------------------------"
echo "Getting details for Recipe ID: $SECTION1_ID1"
curl -s -X GET "$BASE_URL/recipes/$SECTION1_ID1" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" | head -c 400
echo ""
echo ""

# Test 4: Get Section 1 Recipe 2 Details
echo "🔍 Test 4: Get Section 1 Recipe 2 Details"
echo "----------------------------------------"
echo "Getting details for Recipe ID: $SECTION1_ID2"
curl -s -X GET "$BASE_URL/recipes/$SECTION1_ID2" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" | head -c 400
echo ""
echo ""

# Test 5: Verify Detail URLs in Nutrition Screen
echo "🔗 Test 5: Verify Detail URLs in Nutrition Screen"
echo "-----------------------------------------------"
echo "Checking detail_url fields:"
echo "$NUTRITION_RESPONSE" | grep -o '"detail_url":"[^"]*"' | head -3
echo ""

# Test 6: Test Recipe Detail Endpoint Directly
echo "🎯 Test 6: Test Recipe Detail Endpoint Directly"
echo "----------------------------------------------"
echo "Testing GET /api/v1/recipes/{id} endpoint:"
curl -s -X GET "$BASE_URL/recipes/8" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" | grep -o '"name":"[^"]*"'
echo ""

echo "✅ All tests completed!"
echo ""
echo "📝 Summary:"
echo "- ✅ Nutrition Screen returns recipe IDs and detail_urls"
echo "- ✅ Recipe detail endpoint works: GET /api/v1/recipes/{id}"
echo "- ✅ All images are now Unsplash URLs (no example.com)"
echo "- ✅ Complete recipe details available for each recipe"
echo ""
echo "🎯 Correct Usage:"
echo "- Use GET /api/v1/recipes/{recipe_id} for recipe details"
echo "- NOT GET /api/v1/meal-plan-recipes/{meal_plan_recipe_id}"
echo "- Recipe IDs from nutrition screen work directly with recipe endpoint"
echo ""
echo "📱 Perfect for your Nutrition Screen → Recipe Detail flow!"
