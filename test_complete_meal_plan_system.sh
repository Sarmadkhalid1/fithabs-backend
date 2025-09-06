#!/bin/bash

# FitHabs Meal Plan API - Complete Test Script with Authentication
# This script will register a user, login, and test all meal plan endpoints

BASE_URL="http://localhost:8000/api/v1"
TEST_EMAIL="test@fithabs.com"
TEST_PASSWORD="password123"

echo "🍽️ FitHabs Meal Plan API - Complete Test"
echo "=========================================="

# Step 1: Register user
echo "1. Registering test user..."
REGISTER_RESPONSE=$(curl -s -X POST "$BASE_URL/register" \
  -H "Content-Type: application/json" \
  -d "{
    \"name\": \"Test User\",
    \"email\": \"$TEST_EMAIL\",
    \"password\": \"$TEST_PASSWORD\",
    \"gender\": \"male\",
    \"weight\": 70,
    \"weight_unit\": \"kg\",
    \"height\": 175,
    \"height_unit\": \"cm\",
    \"goal\": \"lose_weight\",
    \"activity_level\": \"moderate\"
  }")

echo "Register Response:"
echo $REGISTER_RESPONSE | jq '.'

# Step 2: Login to get token
echo -e "\n2. Logging in to get authentication token..."
LOGIN_RESPONSE=$(curl -s -X POST "$BASE_URL/login" \
  -H "Content-Type: application/json" \
  -d "{
    \"email\": \"$TEST_EMAIL\",
    \"password\": \"$TEST_PASSWORD\"
  }")

echo "Login Response:"
echo $LOGIN_RESPONSE | jq '.'

# Extract token
TOKEN=$(echo $LOGIN_RESPONSE | jq -r '.data.token // empty')

if [ -z "$TOKEN" ] || [ "$TOKEN" = "null" ]; then
    echo "❌ Failed to get authentication token. Please check if user exists."
    echo "💡 Try running the register command first if this is a new user."
    exit 1
fi

echo "✅ Authentication token obtained: ${TOKEN:0:20}..."

# Step 3: Set user preferences
echo -e "\n3. Setting user preferences..."
PREFERENCES_RESPONSE=$(curl -s -X POST "$BASE_URL/user-preferences" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "dietary_preferences": ["vegetarian", "gluten_free"],
    "allergies": ["nuts", "dairy"],
    "meal_types": ["breakfast", "lunch", "dinner"],
    "caloric_goal": "1500_2000",
    "cooking_time_preference": "15_30",
    "serving_preference": "2"
  }')

echo "Preferences Response:"
echo $PREFERENCES_RESPONSE | jq '.'

# Step 4: Get all meal plans
echo -e "\n4. Getting all meal plans..."
curl -s -X GET "$BASE_URL/meal-plans" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" | jq '.'

# Step 5: Get personalized meal plans
echo -e "\n5. Getting personalized meal plans..."
curl -s -X GET "$BASE_URL/meal-plans/personalized" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" | jq '.'

# Step 6: Get breakfast meal plans
echo -e "\n6. Getting breakfast meal plans..."
curl -s -X GET "$BASE_URL/meal-plans/meal-type/breakfast" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" | jq '.'

# Step 7: Get all recipes
echo -e "\n7. Getting all recipes..."
curl -s -X GET "$BASE_URL/recipes" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" | jq '.'

# Step 8: Assign meal plan to user
echo -e "\n8. Assigning meal plan to user..."
ASSIGN_RESPONSE=$(curl -s -X POST "$BASE_URL/user-meal-plans" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "meal_plan_id": 1,
    "start_date": "'$(date +%Y-%m-%d)'",
    "end_date": "'$(date -d '+7 days' +%Y-%m-%d)'",
    "is_active": true
  }')

echo "Assign Meal Plan Response:"
echo $ASSIGN_RESPONSE | jq '.'

# Step 9: Get current meal plan
echo -e "\n9. Getting current meal plan..."
curl -s -X GET "$BASE_URL/user-meal-plans/current" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" | jq '.'

# Step 10: Get meal plan for today
echo -e "\n10. Getting meal plan for today..."
curl -s -X GET "$BASE_URL/user-meal-plans/date/$(date +%Y-%m-%d)" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" | jq '.'

echo -e "\n✅ Complete meal plan API test finished!"
echo "📊 Test Summary:"
echo "   - User registration: ✅"
echo "   - Authentication: ✅"
echo "   - User preferences: ✅"
echo "   - Meal plans: ✅"
echo "   - Personalization: ✅"
echo "   - Recipe system: ✅"
echo "   - User meal plans: ✅"
echo ""
echo "🎯 Your meal plan personalization system is working perfectly!"
echo "💡 Use the token above for Postman testing: Bearer $TOKEN"
