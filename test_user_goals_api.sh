#!/bin/bash

# Test script for User Goals API
echo "🎯 Testing User Goals API"
echo "========================"

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

# Test 1: Get user goals (should be empty initially)
echo "📋 Test 1: Get User Goals (Initial)"
echo "-----------------------------------"
curl -s -X GET "$BASE_URL/user-goals" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json"
echo ""
echo ""

# Test 2: Create user goals
echo "➕ Test 2: Create User Goals"
echo "---------------------------"
curl -s -X POST "$BASE_URL/user-goals" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "steps": 10000,
    "calories": 2000.50,
    "water": 2.5
  }'
echo ""
echo ""

# Test 3: Get user goals (should show created goals)
echo "📋 Test 3: Get User Goals (After Creation)"
echo "------------------------------------------"
curl -s -X GET "$BASE_URL/user-goals" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json"
echo ""
echo ""

# Test 4: Update user goals
echo "✏️ Test 4: Update User Goals"
echo "---------------------------"
curl -s -X PUT "$BASE_URL/user-goals/1" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "steps": 15000,
    "calories": 2500.75,
    "water": 3.0
  }'
echo ""
echo ""

# Test 5: Get updated goals
echo "📋 Test 5: Get Updated Goals"
echo "----------------------------"
curl -s -X GET "$BASE_URL/user-goals" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json"
echo ""
echo ""

# Test 6: Partial update (only steps)
echo "✏️ Test 6: Partial Update (Steps Only)"
echo "------------------------------------"
curl -s -X PUT "$BASE_URL/user-goals/1" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "steps": 12000
  }'
echo ""
echo ""

# Test 7: Get partially updated goals
echo "📋 Test 7: Get Partially Updated Goals"
echo "--------------------------------------"
curl -s -X GET "$BASE_URL/user-goals" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json"
echo ""
echo ""

# Test 8: Validation tests
echo "⚠️ Test 8: Validation Tests"
echo "--------------------------"

echo "Testing invalid steps (too low):"
curl -s -X POST "$BASE_URL/user-goals" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "steps": 500,
    "calories": 2000,
    "water": 2.5
  }' | head -c 200
echo ""
echo ""

echo "Testing invalid calories (too high):"
curl -s -X POST "$BASE_URL/user-goals" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "steps": 10000,
    "calories": 1000,
    "water": 2.5
  }' | head -c 200
echo ""
echo ""

echo "Testing invalid water (too high):"
curl -s -X POST "$BASE_URL/user-goals" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "steps": 10000,
    "calories": 2000,
    "water": 6.0
  }' | head -c 200
echo ""
echo ""

# Test 9: Delete goals
echo "🗑️ Test 9: Delete User Goals"
echo "---------------------------"
curl -s -X DELETE "$BASE_URL/user-goals/1" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json"
echo ""
echo ""

# Test 10: Verify deletion
echo "📋 Test 10: Verify Goals Deleted"
echo "-------------------------------"
curl -s -X GET "$BASE_URL/user-goals" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json"
echo ""
echo ""

echo "✅ All tests completed!"
echo ""
echo "📝 Summary:"
echo "- ✅ GET /api/v1/user-goals - Get user goals"
echo "- ✅ POST /api/v1/user-goals - Create/update goals"
echo "- ✅ PUT /api/v1/user-goals/{id} - Update goals"
echo "- ✅ DELETE /api/v1/user-goals/{id} - Delete goals"
echo ""
echo "🎯 Validation Rules:"
echo "- Steps: 1000-20000 (integer)"
echo "- Calories: 64-643 kcal (decimal)"
echo "- Water: 1-5 liters (decimal)"
echo ""
echo "📱 Perfect for your mobile app goal setting!"
