#!/bin/bash

# Test Professional Endpoints - Coaches, Therapists, Clinics
echo "🏋️ Testing FitHabs Professional Endpoints"
echo "=========================================="

BASE_URL="http://127.0.0.1:8000/api/v1"

echo ""
echo "📱 Testing Coaches Endpoint..."
echo "GET $BASE_URL/coaches"
echo ""

# Test coaches endpoint
response=$(curl -s -X GET "$BASE_URL/coaches" \
  -H "Accept: application/json")

echo "Response:"
echo "$response" | python3 -m json.tool 2>/dev/null || echo "$response"

echo ""
echo "🧠 Testing Therapists Endpoint..."
echo "GET $BASE_URL/therapists"
echo ""

# Test therapists endpoint
response=$(curl -s -X GET "$BASE_URL/therapists" \
  -H "Accept: application/json")

echo "Response:"
echo "$response" | python3 -m json.tool 2>/dev/null || echo "$response"

echo ""
echo "🏥 Testing Clinics Endpoint..."
echo "GET $BASE_URL/clinics"
echo ""

# Test clinics endpoint
response=$(curl -s -X GET "$BASE_URL/clinics" \
  -H "Accept: application/json")

echo "Response:"
echo "$response" | python3 -m json.tool 2>/dev/null || echo "$response"

echo ""
echo "✅ Professional Endpoints Test Complete!"
echo ""
echo "📋 What these endpoints provide:"
echo "   • Coaches: Personal trainers, fitness coaches, yoga instructors"
echo "   • Therapists: Mental health professionals with clinic affiliations"
echo "   • Clinics: Wellness centers and mental health facilities"
echo ""
echo "🎯 Perfect for mobile app professional browsing screens!"
echo "💬 Each professional includes a chat_url for direct communication"
echo ""
echo "📱 Mobile App Integration:"
echo "   • Use these endpoints to populate your Coaches/Therapist/Clinics tabs"
echo "   • Each card shows: name, bio, image, specializations, contact info"
echo "   • 'Go Chat' button can use the provided chat_url"
