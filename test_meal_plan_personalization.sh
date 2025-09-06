#!/bin/bash

# FitHabs Meal Plan Personalization Test Script
# Make sure to replace YOUR_TOKEN with an actual authentication token

BASE_URL="http://localhost:8000/api/v1"
TOKEN="YOUR_TOKEN"  # Replace with actual token

echo "🍽️ Testing FitHabs Meal Plan Personalization API"
echo "=================================================="

# Test 1: Set user preferences
echo "1. Setting user preferences..."
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

echo $PREFERENCES_RESPONSE | jq '.'

# Test 2: Get user preferences
echo -e "\n2. Getting user preferences..."
curl -s -X GET "$BASE_URL/user-preferences" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" | jq '.'

# Test 3: Get personalized meal plans
echo -e "\n3. Getting personalized meal plans..."
curl -s -X GET "$BASE_URL/meal-plans/personalized" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" | jq '.'

# Test 4: Get breakfast meal plans
echo -e "\n4. Getting breakfast meal plans..."
curl -s -X GET "$BASE_URL/meal-plans/meal-type/breakfast" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" | jq '.'

# Test 5: Get current meal plan
echo -e "\n5. Getting current meal plan..."
curl -s -X GET "$BASE_URL/user-meal-plans/current" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" | jq '.'

# Test 6: Get meal plan for specific date
echo -e "\n6. Getting meal plan for specific date..."
curl -s -X GET "$BASE_URL/user-meal-plans/date/$(date +%Y-%m-%d)" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" | jq '.'

echo -e "\n✅ Meal plan personalization API test completed!"
echo "💡 Remember to replace YOUR_TOKEN with a valid authentication token"
echo "📚 Check MEAL_PLAN_PERSONALIZATION_GUIDE.md for detailed documentation"
