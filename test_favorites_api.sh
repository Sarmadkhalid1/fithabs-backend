#!/bin/bash

# Test script for Favorites API endpoints
# Make sure to replace YOUR_TOKEN with actual authentication token

BASE_URL="http://localhost:8000/api/v1"
TOKEN="YOUR_TOKEN"

echo "🧪 Testing Favorites API Endpoints"
echo "=================================="

# Test 1: Get user's favorites
echo -e "\n1️⃣ Getting user's favorites..."
curl -X GET "$BASE_URL/user-favorites" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -w "\nStatus: %{http_code}\n"

# Test 2: Add a workout to favorites
echo -e "\n2️⃣ Adding workout to favorites..."
curl -X POST "$BASE_URL/user-favorites" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "favoritable_type": "workout",
    "favoritable_id": 1
  }' \
  -w "\nStatus: %{http_code}\n"

# Test 3: Add an article (education_content) to favorites
echo -e "\n3️⃣ Adding article to favorites..."
curl -X POST "$BASE_URL/user-favorites" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "favoritable_type": "education_content",
    "favoritable_id": 1
  }' \
  -w "\nStatus: %{http_code}\n"

# Test 4: Try to add duplicate favorite (should fail)
echo -e "\n4️⃣ Trying to add duplicate favorite..."
curl -X POST "$BASE_URL/user-favorites" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "favoritable_type": "workout",
    "favoritable_id": 1
  }' \
  -w "\nStatus: %{http_code}\n"

# Test 5: Get favorites again to see the added items
echo -e "\n5️⃣ Getting favorites after adding items..."
curl -X GET "$BASE_URL/user-favorites" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -w "\nStatus: %{http_code}\n" | jq '.'

# Test 6: Remove favorite by item type and ID
echo -e "\n6️⃣ Removing favorite by item..."
curl -X POST "$BASE_URL/user-favorites/remove-by-item" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "favoritable_type": "workout",
    "favoritable_id": 1
  }' \
  -w "\nStatus: %{http_code}\n"

# Test 7: Final check of favorites
echo -e "\n7️⃣ Final check of favorites..."
curl -X GET "$BASE_URL/user-favorites" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -w "\nStatus: %{http_code}\n"

echo -e "\n✅ Favorites API testing completed!"
echo "=================================="
