# FitHabs AI Chat - Quick Reference Guide

## 🚀 Quick Start

**Base URL:** `http://127.0.0.1:8000/api/v1`

## 📱 Essential Endpoints

| Action       | Method   | Endpoint              | Description                       |
| ------------ | -------- | --------------------- | --------------------------------- |
| Get Chats    | `GET`    | `/ai-chats`           | Load sidebar with "Old chat" list |
| New Chat     | `POST`   | `/ai-chats`           | Create new conversation           |
| Send Message | `POST`   | `/ai-chats/{id}/send` | Send message to AI                |
| Load Chat    | `GET`    | `/ai-chats/{id}`      | Load specific chat history        |
| Delete Chat  | `DELETE` | `/ai-chats/{id}`      | Remove chat                       |

## 💬 Message Flow

```javascript
// 1. Create new chat
const newChat = await fetch('/api/v1/ai-chats', { method: 'POST' });
const { data: chat } = await newChat.json();

// 2. Send message
const response = await fetch(`/api/v1/ai-chats/${chat.id}/send`, {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({ message: 'Hello AI!' }),
});
const { data } = await response.json();

// 3. Display messages
data.user_message; // User's message
data.ai_message; // AI's response
```

## 🎨 UI Implementation

### Chat List (Sidebar)

```javascript
const chats = [
  {
    id: 1,
    title: 'Hey, I have been feeling low o...', // Truncated title
    message_count: 4, // For badges
    updated_at: '2025-09-05T19:38:25.000000Z', // For sorting
  },
];
```

### Chat Messages

```javascript
const messages = [
  {
    id: 1,
    role: 'user', // "user" or "assistant"
    content: 'Hello!',
    created_at: '2025-09-05T19:38:24.000000Z',
  },
];
```

## 🔧 Error Handling

```javascript
try {
  const response = await fetch('/api/v1/ai-chats');
  const data = await response.json();

  if (data.status === 'success') {
    // Handle success
    return data.data;
  } else {
    // Handle error
    console.error(data.message);
  }
} catch (error) {
  console.error('Network error:', error);
}
```

## 📋 Sample Implementation

```javascript
class AiChatService {
  constructor(baseUrl = 'http://127.0.0.1:8000/api/v1') {
    this.baseUrl = baseUrl;
  }

  async getChats() {
    const response = await fetch(`${this.baseUrl}/ai-chats`);
    return response.json();
  }

  async createChat() {
    const response = await fetch(`${this.baseUrl}/ai-chats`, {
      method: 'POST',
      headers: { Accept: 'application/json' },
    });
    return response.json();
  }

  async sendMessage(chatId, message) {
    const response = await fetch(`${this.baseUrl}/ai-chats/${chatId}/send`, {
      method: 'POST',
      headers: {
        Accept: 'application/json',
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ message }),
    });
    return response.json();
  }

  async getChatHistory(chatId) {
    const response = await fetch(`${this.baseUrl}/ai-chats/${chatId}`);
    return response.json();
  }
}

// Usage
const chatService = new AiChatService();

// Load chat list
const chats = await chatService.getChats();

// Create new chat
const newChat = await chatService.createChat();

// Send message
const response = await chatService.sendMessage(newChat.data.id, 'Hello!');
```

## 🎯 Key Points

- **No Authentication Required** - All endpoints are public
- **Real-time Responses** - AI responds immediately
- **Context Aware** - AI remembers conversation history
- **Auto Titles** - Chat titles generated from first message
- **Message Persistence** - All conversations saved

## 🧪 Testing

Test the API with curl:

```bash
# Get chats
curl http://127.0.0.1:8000/api/v1/ai-chats

# Create chat
curl -X POST http://127.0.0.1:8000/api/v1/ai-chats

# Send message
curl -X POST http://127.0.0.1:8000/api/v1/ai-chats/1/send \
  -H "Content-Type: application/json" \
  -d '{"message": "Hello AI!"}'
```

## 📞 Support

For questions or issues, contact the backend team or refer to the full documentation in `AI_CHAT_API_DOCUMENTATION.md`.
