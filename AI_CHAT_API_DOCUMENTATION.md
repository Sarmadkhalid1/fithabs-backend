# FitHabs AI Chat API Documentation

## Overview

This documentation provides all the necessary endpoints and implementation details for the AI Chat functionality in the FitHabs mobile application. The AI Chat system is powered by Google Gemini AI and allows users to have intelligent conversations about health, fitness, and nutrition.

## Base URL

```
http://127.0.0.1:8000/api/v1
```

## Authentication

All AI Chat endpoints require **authentication** using Laravel Sanctum. Users must be logged in to access their chat history and send messages.

**Authentication Header Required:**

```
Authorization: Bearer {your_token}
```

---

## Endpoints

### 1. Get All Chats

**Endpoint:** `GET /ai-chats`

**Description:** Retrieves all active AI chats for the user, perfect for populating the "Old chat" sidebar.

**Request:**

```http
GET /api/v1/ai-chats
Accept: application/json
Authorization: Bearer {your_token}
```

**Response:**

```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "title": "Hey, I have been feeling low o...",
      "created_at": "2025-09-05T19:38:17.000000Z",
      "updated_at": "2025-09-05T19:38:25.000000Z",
      "message_count": 4
    },
    {
      "id": 2,
      "title": "What are some good exercises f...",
      "created_at": "2025-09-05T19:43:54.000000Z",
      "updated_at": "2025-09-05T19:44:05.000000Z",
      "message_count": 2
    }
  ],
  "count": 2
}
```

**Use Case:** Populate the sidebar with chat history for the "Old chat" functionality.

---

### 2. Create New Chat

**Endpoint:** `POST /ai-chats`

**Description:** Creates a new AI chat session. Use this when the user taps "New Chat".

**Request:**

```http
POST /api/v1/ai-chats
Accept: application/json
Authorization: Bearer {your_token}
Content-Type: application/json
```

**Response:**

```json
{
  "status": "success",
  "data": {
    "id": 3,
    "title": "New Chat",
    "session_id": "48c1b042-13a0-448d-81a1-2086be9d74cb",
    "created_at": "2025-09-05T19:43:54.000000Z"
  }
}
```

**Use Case:** Initialize a new conversation when user taps "New Chat" button.

---

### 3. Send Message to AI

**Endpoint:** `POST /ai-chats/{id}/send`

**Description:** Sends a user message to the AI and receives an intelligent response. This is the core chat functionality.

**Request:**

```http
POST /api/v1/ai-chats/{chat_id}/send
Accept: application/json
Authorization: Bearer {your_token}
Content-Type: application/json

{
  "message": "Hey, I've been feeling low on energy lately. Any quick health tips?"
}
```

**Response:**

```json
{
  "status": "success",
  "data": {
    "user_message": {
      "id": 5,
      "role": "user",
      "content": "Hey, I've been feeling low on energy lately. Any quick health tips?",
      "created_at": "2025-09-05T19:43:54.000000Z"
    },
    "ai_message": {
      "id": 6,
      "role": "assistant",
      "content": "Feeling low on energy is common, but it's important to address it. Here are some quick health tips to try...",
      "created_at": "2025-09-05T19:44:05.000000Z"
    }
  }
}
```

**Use Case:** Send user messages and display both user and AI responses in the chat interface.

---

### 4. Get Chat History

**Endpoint:** `GET /ai-chats/{id}`

**Description:** Retrieves the complete conversation history for a specific chat.

**Request:**

```http
GET /api/v1/ai-chats/{chat_id}
Accept: application/json
Authorization: Bearer {your_token}
```

**Response:**

```json
{
  "status": "success",
  "data": {
    "chat": {
      "id": 2,
      "title": "What are some good exercises f...",
      "created_at": "2025-09-05T19:43:54.000000Z",
      "updated_at": "2025-09-05T19:44:05.000000Z"
    },
    "messages": [
      {
        "id": 5,
        "role": "user",
        "content": "What are some good exercises for beginners?",
        "created_at": "2025-09-05T19:43:54.000000Z"
      },
      {
        "id": 6,
        "role": "assistant",
        "content": "For beginners, it's crucial to focus on building a foundation of strength and endurance...",
        "created_at": "2025-09-05T19:44:05.000000Z"
      }
    ]
  }
}
```

**Use Case:** Load previous conversations when user taps on an "Old chat" item.

---

### 5. Delete Chat

**Endpoint:** `DELETE /ai-chats/{id}`

**Description:** Soft deletes a chat (marks as inactive).

**Request:**

```http
DELETE /api/v1/ai-chats/{chat_id}
Accept: application/json
Authorization: Bearer {your_token}
```

**Response:**

```json
{
  "status": "success",
  "message": "Chat deleted successfully"
}
```

**Use Case:** Allow users to delete unwanted chat conversations.

---

## Error Responses

All endpoints return consistent error responses:

```json
{
  "status": "error",
  "message": "Error description",
  "error": "Detailed error information"
}
```

**Common HTTP Status Codes:**

- `200` - Success
- `201` - Created (for new chat)
- `422` - Validation Error
- `404` - Chat not found
- `500` - Server Error

---

## Frontend Implementation Guide

### 1. Chat List Screen (Sidebar)

```javascript
// Fetch all chats for sidebar
const fetchChats = async () => {
  try {
    const token = localStorage.getItem('auth_token'); // Get stored token
    const response = await fetch('/api/v1/ai-chats', {
      headers: {
        Accept: 'application/json',
        Authorization: `Bearer ${token}`,
      },
    });
    const data = await response.json();
    return data.data; // Array of chat objects
  } catch (error) {
    console.error('Error fetching chats:', error);
  }
};
```

### 2. Create New Chat

```javascript
// Create new chat when user taps "New Chat"
const createNewChat = async () => {
  try {
    const token = localStorage.getItem('auth_token');
    const response = await fetch('/api/v1/ai-chats', {
      method: 'POST',
      headers: {
        Accept: 'application/json',
        Authorization: `Bearer ${token}`,
        'Content-Type': 'application/json',
      },
    });
    const data = await response.json();
    return data.data; // New chat object with ID
  } catch (error) {
    console.error('Error creating chat:', error);
  }
};
```

### 3. Send Message

```javascript
// Send message to AI
const sendMessage = async (chatId, message) => {
  try {
    const token = localStorage.getItem('auth_token');
    const response = await fetch(`/api/v1/ai-chats/${chatId}/send`, {
      method: 'POST',
      headers: {
        Accept: 'application/json',
        Authorization: `Bearer ${token}`,
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ message }),
    });
    const data = await response.json();
    return data.data; // Contains both user_message and ai_message
  } catch (error) {
    console.error('Error sending message:', error);
  }
};
```

### 4. Load Chat History

```javascript
// Load specific chat with messages
const loadChatHistory = async (chatId) => {
  try {
    const token = localStorage.getItem('auth_token');
    const response = await fetch(`/api/v1/ai-chats/${chatId}`, {
      headers: {
        Accept: 'application/json',
        Authorization: `Bearer ${token}`,
      },
    });
    const data = await response.json();
    return data.data; // Contains chat info and messages array
  } catch (error) {
    console.error('Error loading chat:', error);
  }
};
```

### 5. Delete Chat

```javascript
// Delete a chat
const deleteChat = async (chatId) => {
  try {
    const token = localStorage.getItem('auth_token');
    const response = await fetch(`/api/v1/ai-chats/${chatId}`, {
      method: 'DELETE',
      headers: {
        Accept: 'application/json',
        Authorization: `Bearer ${token}`,
      },
    });
    const data = await response.json();
    return data.status === 'success';
  } catch (error) {
    console.error('Error deleting chat:', error);
  }
};
```

---

## UI/UX Implementation Notes

### Chat Message Display

- **User messages**: Display on the right side with darker background
- **AI messages**: Display on the left side with lighter background
- **Timestamps**: Show `created_at` time for each message
- **Message IDs**: Use for unique keys in React/Vue lists

### Chat List Display

- **Title**: Show truncated chat title (first 30 characters)
- **Last Updated**: Display `updated_at` for sorting
- **Message Count**: Show `message_count` for unread indicators
- **Order**: Sort by `updated_at` descending (most recent first)

### Input Field

- **Placeholder**: "Ask me anything..."
- **Send Button**: Paper airplane icon
- **Character Limit**: 1000 characters max
- **Loading State**: Show loading indicator while AI responds

---

## Testing Endpoints

You can test all endpoints using the provided test script:

```bash
./test_ai_chat.sh
```

Or manually with curl:

```bash
# Get all chats
curl -X GET "http://127.0.0.1:8000/api/v1/ai-chats" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"

# Create new chat
curl -X POST "http://127.0.0.1:8000/api/v1/ai-chats" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"

# Send message (replace {chat_id} with actual ID)
curl -X POST "http://127.0.0.1:8000/api/v1/ai-chats/{chat_id}/send" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{"message": "Hello AI!"}'
```

---

## AI Capabilities

The AI is powered by Google Gemini and provides **personalized responses** based on your profile data. It can help with:

- **Personalized Health Advice**: Tailored recommendations based on your age, gender, weight, and height
- **Customized Fitness Plans**: Workout suggestions that match your activity level and goals
- **Dietary Guidance**: Meal recommendations based on your dietary preferences, allergies, and caloric goals
- **Goal-Oriented Support**: Advice aligned with your specific fitness goals (lose weight, build muscle, etc.)
- **Wellness Coaching**: Stress management and lifestyle tips personalized to your situation

### Personalization Features

The AI automatically considers your:

- **Basic Info**: Name, age, gender
- **Physical Stats**: Weight, height, BMI calculations
- **Fitness Goals**: Primary goal (lose/gain/maintain weight, build muscle)
- **Activity Level**: Sedentary, light, moderate, or very active
- **Daily Targets**: Calorie goals, step goals, water intake goals
- **Dietary Preferences**: Vegetarian, vegan, allergies, meal type preferences
- **Cooking Preferences**: Time constraints, serving sizes

**Note**: The AI provides personalized, evidence-based advice but should not replace professional medical consultation. Always consult healthcare professionals for medical concerns.

---

## Personalization System

### How It Works

The AI chat system automatically gathers your profile information to provide personalized responses:

1. **Profile Data Collection**: When you send a message, the system retrieves your:
   - User profile information (name, age, gender, physical stats)
   - Fitness goals and activity level
   - Dietary preferences and allergies
   - Daily targets and preferences

2. **Context Building**: Your data is formatted into a personalized system prompt that tells the AI:
   - Who you are and your current situation
   - Your specific goals and preferences
   - Any dietary restrictions or preferences
   - Your activity level and capabilities

3. **Personalized Responses**: The AI uses this context to provide:
   - Tailored workout recommendations
   - Customized meal suggestions
   - Goal-specific advice
   - Appropriate difficulty levels
   - Relevant tips and motivation

### Example Personalization

If you're a 25-year-old female who wants to lose weight and is moderately active, the AI will:

- Suggest calorie-appropriate meal plans
- Recommend cardio-focused workouts
- Provide weight loss tips
- Consider your activity level for exercise intensity
- Use encouraging, supportive language

### Data Privacy

- Your personal data is only used to personalize AI responses
- No personal information is stored in chat messages
- All data remains within the FitHabs system
- You can update your profile anytime to get updated recommendations

---

## Rate Limiting

Currently, there are no rate limits on the AI chat endpoints, but consider implementing client-side throttling to prevent spam.

---

## Support

For any questions or issues with the API, contact the backend development team.
