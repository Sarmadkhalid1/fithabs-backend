#!/bin/bash

# Test script for getting individual meal plan recipes by ID
echo "🍽️  Testing Individual Meal Plan Recipe Endpoint"
echo "================================================"

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

# Test 1: Get meal plan recipe ID 1 (breakfast)
echo "🌅 Test 1: Get Meal Plan Recipe ID 1 (Breakfast)"
echo "------------------------------------------------"
curl -s -X GET "$BASE_URL/meal-plan-recipes/1" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" | grep -o '"name":"[^"]*"'
echo ""

# Test 2: Get meal plan recipe ID 2 (lunch)
echo "🌞 Test 2: Get Meal Plan Recipe ID 2 (Lunch)"
echo "---------------------------------------------"
curl -s -X GET "$BASE_URL/meal-plan-recipes/2" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" | grep -o '"name":"[^"]*"'
echo ""

# Test 3: Get meal plan recipe ID 3 (dinner)
echo "🌙 Test 3: Get Meal Plan Recipe ID 3 (Dinner)"
echo "---------------------------------------------"
curl -s -X GET "$BASE_URL/meal-plan-recipes/3" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" | grep -o '"name":"[^"]*"'
echo ""

# Test 4: Get meal plan recipe ID 4 (snack)
echo "🍎 Test 4: Get Meal Plan Recipe ID 4 (Snack)"
echo "--------------------------------------------"
curl -s -X GET "$BASE_URL/meal-plan-recipes/4" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" | grep -o '"name":"[^"]*"'
echo ""

# Test 5: Invalid ID (should return 404)
echo "❌ Test 5: Invalid Meal Plan Recipe ID (should return 404)"
echo "--------------------------------------------------------"
curl -s -X GET "$BASE_URL/meal-plan-recipes/999" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" | head -c 200
echo ""

# Test 6: Detailed view of meal plan recipe ID 1
echo "🔍 Test 6: Detailed View of Meal Plan Recipe ID 1"
echo "------------------------------------------------"
curl -s -X GET "$BASE_URL/meal-plan-recipes/1" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" | head -c 300
echo ""

echo "✅ Individual meal plan recipe tests completed!"
echo ""
echo "📝 Summary:"
echo "- Endpoint: GET /api/v1/meal-plan-recipes/{id}"
echo "- Returns individual meal plan recipe with full recipe details"
echo "- Includes meal_plan_id, recipe_id, day_number, meal_type, servings, order"
echo "- Includes complete recipe information (name, description, ingredients, etc.)"
echo "- Proper error handling for invalid IDs"
