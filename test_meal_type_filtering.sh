#!/bin/bash

# Test script for meal type filtering in personalized meal plans
echo "🍽️  Testing Meal Type Filtering for Personalized Meal Plans"
echo "=========================================================="

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

# Test 1: Get all personalized meal plans (no meal type filter)
echo "📋 Test 1: All Personalized Meal Plans (no meal type filter)"
echo "------------------------------------------------------------"
curl -s -X GET "$BASE_URL/meal-plans/personalized" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" | grep -o '"count":[0-9]*'
echo ""

# Test 2: Filter by breakfast
echo "🌅 Test 2: Personalized Meal Plans - Breakfast Only"
echo "---------------------------------------------------"
curl -s -X GET "$BASE_URL/meal-plans/personalized?meal_type=breakfast" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" | grep -o '"count":[0-9]*'
echo ""

# Test 3: Filter by lunch
echo "🌞 Test 3: Personalized Meal Plans - Lunch Only"
echo "-----------------------------------------------"
curl -s -X GET "$BASE_URL/meal-plans/personalized?meal_type=lunch" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" | grep -o '"count":[0-9]*'
echo ""

# Test 4: Filter by dinner
echo "🌙 Test 4: Personalized Meal Plans - Dinner Only"
echo "-------------------------------------------------"
curl -s -X GET "$BASE_URL/meal-plans/personalized?meal_type=dinner" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" | grep -o '"count":[0-9]*'
echo ""

# Test 5: Filter by snack
echo "🍎 Test 5: Personalized Meal Plans - Snack Only"
echo "------------------------------------------------"
curl -s -X GET "$BASE_URL/meal-plans/personalized?meal_type=snack" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" | grep -o '"count":[0-9]*'
echo ""

# Test 6: Invalid meal type (should return validation error)
echo "❌ Test 6: Invalid Meal Type (should return validation error)"
echo "------------------------------------------------------------"
curl -s -X GET "$BASE_URL/meal-plans/personalized?meal_type=invalid" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" | head -c 200
echo ""

# Test 7: Detailed view of breakfast meal plans
echo "🔍 Test 7: Detailed View of Breakfast Meal Plans"
echo "------------------------------------------------"
curl -s -X GET "$BASE_URL/meal-plans/personalized?meal_type=breakfast" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" | head -c 300
echo ""

echo "✅ Meal type filtering tests completed!"
echo ""
echo "📝 Summary:"
echo "- The endpoint now supports optional meal_type query parameter"
echo "- Valid meal types: breakfast, lunch, dinner, snack"
echo "- If no meal_type is provided, all meal plans are returned"
echo "- The response includes a 'meal_type_filter' field showing the applied filter"
echo "- User preferences are still applied when available"
