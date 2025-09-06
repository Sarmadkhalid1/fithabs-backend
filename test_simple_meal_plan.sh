#!/bin/bash

# FitHabs Meal Plan API - Simple Test Script (no jq dependency)
# This script will register a user, login, and test the personalized meal plans endpoint

BASE_URL="http://localhost:8000/api/v1"
TEST_EMAIL="test@fithabs.com"
TEST_PASSWORD="password123"

echo "🍽️ FitHabs Meal Plan API - Simple Test"
echo "======================================="

# Step 1: Register user
echo "1. Registering test user..."
REGISTER_RESPONSE=$(curl -s -X POST "$BASE_URL/register" \
  -H "Content-Type: application/json" \
  -d "{
    \"name\": \"Test User\",
    \"email\": \"$TEST_EMAIL\",
    \"password\": \"$TEST_PASSWORD\",
    \"password_confirmation\": \"$TEST_PASSWORD\"
  }")

echo "Register Response: $REGISTER_RESPONSE"

# Step 2: Login to get token
echo -e "\n2. Logging in to get authentication token..."
LOGIN_RESPONSE=$(curl -s -X POST "$BASE_URL/login" \
  -H "Content-Type: application/json" \
  -d "{
    \"email\": \"$TEST_EMAIL\",
    \"password\": \"$TEST_PASSWORD\"
  }")

echo "Login Response: $LOGIN_RESPONSE"

# Extract token using grep and sed (no jq dependency)
TOKEN=$(echo "$LOGIN_RESPONSE" | grep -o '"token":"[^"]*"' | sed 's/"token":"//;s/"//')

if [ -z "$TOKEN" ]; then
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

echo "Preferences Response: $PREFERENCES_RESPONSE"

# Step 4: Test the personalized meal plans endpoint (the one that was failing)
echo -e "\n4. Testing personalized meal plans endpoint..."
PERSONALIZED_RESPONSE=$(curl -s -X GET "$BASE_URL/meal-plans/personalized" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json")

echo "Personalized Meal Plans Response: $PERSONALIZED_RESPONSE"

# Step 5: Test breakfast meal plans
echo -e "\n5. Testing breakfast meal plans endpoint..."
BREAKFAST_RESPONSE=$(curl -s -X GET "$BASE_URL/meal-plans/meal-type/breakfast" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json")

echo "Breakfast Meal Plans Response: $BREAKFAST_RESPONSE"

echo -e "\n✅ Test completed!"
echo "📊 If you see JSON responses above, the API is working correctly."
echo "💡 Use the token for Postman testing: Bearer $TOKEN"
