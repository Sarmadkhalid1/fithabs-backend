#!/bin/bash

# Test AI Chat Functionality with Gemini Integration
echo "🤖 Testing FitHabs AI Chat with Gemini"
echo "====================================="

BASE_URL="http://127.0.0.1:8000/api/v1"

echo ""
echo "📱 Testing AI Chat Endpoints..."
echo ""

# Test 1: Get all chats
echo "1️⃣ Getting all chats..."
echo "GET $BASE_URL/ai-chats"
response=$(curl -s -X GET "$BASE_URL/ai-chats" \
  -H "Accept: application/json")
echo "Response:"
echo "$response" | python3 -m json.tool 2>/dev/null || echo "$response"

echo ""
echo "2️⃣ Creating a new chat..."
echo "POST $BASE_URL/ai-chats"
response=$(curl -s -X POST "$BASE_URL/ai-chats" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json")
echo "Response:"
echo "$response" | python3 -m json.tool 2>/dev/null || echo "$response"

# Extract chat ID from response
chat_id=$(echo "$response" | python3 -c "import sys, json; data=json.load(sys.stdin); print(data['data']['id'])" 2>/dev/null || echo "2")

echo ""
echo "3️⃣ Sending a message to AI..."
echo "POST $BASE_URL/ai-chats/$chat_id/send"
response=$(curl -s -X POST "$BASE_URL/ai-chats/$chat_id/send" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{"message": "What are some good exercises for beginners?"}')
echo "Response:"
echo "$response" | python3 -m json.tool 2>/dev/null || echo "$response"

echo ""
echo "4️⃣ Getting chat history..."
echo "GET $BASE_URL/ai-chats/$chat_id"
response=$(curl -s -X GET "$BASE_URL/ai-chats/$chat_id" \
  -H "Accept: application/json")
echo "Response:"
echo "$response" | python3 -m json.tool 2>/dev/null || echo "$response"

echo ""
echo "✅ AI Chat Test Complete!"
echo ""
echo "📋 What this AI Chat system provides:"
echo "   • Start new chats with AI"
echo "   • Send messages and get intelligent responses"
echo "   • View chat history and conversation context"
echo "   • Automatic chat title generation from first message"
echo "   • Powered by Google Gemini AI"
echo ""
echo "🎯 Perfect for mobile app AI Coach feature!"
echo "💬 Users can ask health, fitness, and nutrition questions"
echo "🧠 AI maintains conversation context for better responses"
echo ""
echo "📱 Mobile App Integration:"
echo "   • Use these endpoints for the 'Ai Coach' tab"
echo "   • Implement chat list for 'Old chat' functionality"
echo "   • Real-time messaging with AI responses"
echo "   • Chat history persistence for user convenience"
