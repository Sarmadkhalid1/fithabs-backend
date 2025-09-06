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

The AI is powered by Google Gemini and can help with:

- **Health Questions**: General health advice and tips
- **Fitness Guidance**: Exercise recommendations and form tips
- **Nutrition Advice**: Healthy eating and meal planning
- **Wellness Support**: Stress management and lifestyle tips
- **General Questions**: Any topic related to health and fitness

**Note**: The AI provides general advice and should not replace professional medical consultation.

---

## Rate Limiting

Currently, there are no rate limits on the AI chat endpoints, but consider implementing client-side throttling to prevent spam.

---

## Support

For any questions or issues with the API, contact the backend development team.
