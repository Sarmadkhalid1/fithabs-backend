#!/bin/bash

# Test Home Screen API Endpoint
echo "🏠 Testing FitHabs Home Screen API"
echo "=================================="

BASE_URL="http://127.0.0.1:8000/api/v1"

echo ""
echo "📱 Testing Home Screen Endpoint..."
echo "GET $BASE_URL/home"
echo ""

# Test home screen endpoint
response=$(curl -s -X GET "$BASE_URL/home" \
  -H "Content-Type: application/json")

echo "Response:"
echo "$response" | python3 -m json.tool 2>/dev/null || echo "$response"

echo ""
echo "✅ Home Screen API Test Complete!"
echo ""
echo "📋 What this endpoint provides:"
echo "   • Today's Suggestions: 3 random exercises with repetitions"
echo "   • Education Cards: 2 featured education contents"
echo "   • Recommended Food: 2 random recipes with calories"
echo ""
echo "🎯 Perfect for mobile app home screen!"
