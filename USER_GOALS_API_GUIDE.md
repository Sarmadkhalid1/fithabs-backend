# 🎯 User Goals API Guide

## Overview

The User Goals API allows users to set and manage their daily fitness goals including steps, calories, and water intake. This follows the KISS principle with simple CRUD operations.

## 🚀 API Endpoints

### **1. Get User Goals**

#### **Get Current Goals**

```bash
GET /api/v1/user-goals
Authorization: Bearer YOUR_TOKEN
```

**Description**: Retrieve the current user's goals.

**Example Request**:

```bash
curl -X GET "http://localhost:8000/api/v1/user-goals" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json"
```

**Success Response** (200):

```json
{
  "status": "success",
  "data": {
    "id": 1,
    "user_id": 4,
    "steps": 10000,
    "calories": 2000.5,
    "water": 2.5,
    "created_at": "2025-09-04T22:59:25.000000Z",
    "updated_at": "2025-09-04T22:59:25.000000Z"
  }
}
```

**No Goals Response** (200):

```json
{
  "status": "success",
  "data": null,
  "message": "No goals set yet"
}
```

### **2. Create/Update Goals**

#### **Set Goals**

```bash
POST /api/v1/user-goals
Authorization: Bearer YOUR_TOKEN
```

**Description**: Create or update user goals. If goals already exist, they will be updated.

**Request Body**:

```json
{
  "steps": 10000,
  "calories": 2000.5,
  "water": 2.5
}
```

**Example Request**:

```bash
curl -X POST "http://localhost:8000/api/v1/user-goals" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "steps": 10000,
    "calories": 2000.50,
    "water": 2.5
  }'
```

**Success Response** (200):

```json
{
  "status": "success",
  "message": "Goals saved successfully",
  "data": {
    "id": 1,
    "user_id": 4,
    "steps": 10000,
    "calories": 2000.5,
    "water": 2.5,
    "created_at": "2025-09-04T22:59:25.000000Z",
    "updated_at": "2025-09-04T22:59:25.000000Z"
  }
}
```

### **3. Update Goals**

#### **Update Existing Goals**

```bash
PUT /api/v1/user-goals/{id}
Authorization: Bearer YOUR_TOKEN
```

**Description**: Update specific fields of existing goals.

**Request Body** (all fields optional):

```json
{
  "steps": 15000,
  "calories": 2500.75,
  "water": 3.0
}
```

**Example Request**:

```bash
curl -X PUT "http://localhost:8000/api/v1/user-goals/1" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "steps": 15000,
    "calories": 2500.75,
    "water": 3.0
  }'
```

**Success Response** (200):

```json
{
  "status": "success",
  "message": "Goals updated successfully",
  "data": {
    "id": 1,
    "user_id": 4,
    "steps": 15000,
    "calories": 2500.75,
    "water": 3.0,
    "created_at": "2025-09-04T22:59:25.000000Z",
    "updated_at": "2025-09-04T23:05:30.000000Z"
  }
}
```

### **4. Delete Goals**

#### **Delete User Goals**

```bash
DELETE /api/v1/user-goals/{id}
Authorization: Bearer YOUR_TOKEN
```

**Description**: Delete user's goals.

**Example Request**:

```bash
curl -X DELETE "http://localhost:8000/api/v1/user-goals/1" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json"
```

**Success Response** (200):

```json
{
  "status": "success",
  "message": "Goals deleted successfully"
}
```

## 🎯 Data Structure

### **Goal Object**

- `id` - Unique goal identifier
- `user_id` - User ID (automatically set)
- `steps` - Daily step goal (1000-20000)
- `calories` - Daily calorie goal (64-643 kcal)
- `water` - Daily water goal in liters (1-5 liters)
- `created_at` - Creation timestamp
- `updated_at` - Last update timestamp

## ⚠️ Validation Rules

### **Steps**

- **Type**: Integer
- **Range**: 1000-20000
- **Required**: No (nullable)

### **Calories**

- **Type**: Decimal (2 decimal places)
- **Range**: 64-643 kcal
- **Required**: No (nullable)

### **Water**

- **Type**: Decimal (2 decimal places)
- **Range**: 1-5 liters
- **Required**: No (nullable)

## 🚨 Error Responses

### **Validation Error** (422)

```json
{
  "status": "error",
  "message": "Validation failed",
  "errors": {
    "steps": ["The steps field must be between 1000 and 20000."],
    "calories": ["The calories field must be between 64 and 643."],
    "water": ["The water field must be between 1 and 5."]
  }
}
```

### **Not Found Error** (404)

```json
{
  "status": "error",
  "message": "No goals found. Please create goals first."
}
```

### **Server Error** (500)

```json
{
  "status": "error",
  "message": "Failed to save goals",
  "error": "Database connection error"
}
```

## 📱 Mobile App Integration

### **React Native Example**

```javascript
// Get user goals
const fetchUserGoals = async () => {
  try {
    const response = await fetch('/api/v1/user-goals', {
      headers: {
        Authorization: `Bearer ${token}`,
        'Content-Type': 'application/json',
      },
    });

    const data = await response.json();

    if (data.status === 'success') {
      setUserGoals(data.data);
    }
  } catch (error) {
    console.error('Error fetching user goals:', error);
  }
};

// Set user goals
const setUserGoals = async (goals) => {
  try {
    const response = await fetch('/api/v1/user-goals', {
      method: 'POST',
      headers: {
        Authorization: `Bearer ${token}`,
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(goals),
    });

    const data = await response.json();

    if (data.status === 'success') {
      // Goals saved successfully
      console.log('Goals saved:', data.data);
    }
  } catch (error) {
    console.error('Error setting user goals:', error);
  }
};

// Update specific goal
const updateGoal = async (goalId, updates) => {
  try {
    const response = await fetch(`/api/v1/user-goals/${goalId}`, {
      method: 'PUT',
      headers: {
        Authorization: `Bearer ${token}`,
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(updates),
    });

    const data = await response.json();

    if (data.status === 'success') {
      // Goal updated successfully
      console.log('Goal updated:', data.data);
    }
  } catch (error) {
    console.error('Error updating goal:', error);
  }
};
```

### **Flutter Example**

```dart
// Get user goals
Future<Map<String, dynamic>?> fetchUserGoals() async {
  try {
    final response = await http.get(
      Uri.parse('$baseUrl/api/v1/user-goals'),
      headers: {
        'Authorization': 'Bearer $token',
        'Content-Type': 'application/json',
      },
    );

    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      if (data['status'] == 'success') {
        return data['data'];
      }
    }
  } catch (e) {
    print('Error fetching user goals: $e');
  }
  return null;
}

// Set user goals
Future<bool> setUserGoals(Map<String, dynamic> goals) async {
  try {
    final response = await http.post(
      Uri.parse('$baseUrl/api/v1/user-goals'),
      headers: {
        'Authorization': 'Bearer $token',
        'Content-Type': 'application/json',
      },
      body: json.encode(goals),
    );

    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      return data['status'] == 'success';
    }
  } catch (e) {
    print('Error setting user goals: $e');
  }
  return false;
}
```

## 🧪 Testing

### **Test Script**

Run the comprehensive test script:

```bash
chmod +x test_user_goals_api.sh
./test_user_goals_api.sh
```

### **Manual Testing**

1. **Login** to get authentication token
2. **Get goals** (should be empty initially)
3. **Create goals** with valid data
4. **Update goals** with new values
5. **Test validation** with invalid data
6. **Delete goals** to clean up

## 🔧 Implementation Details

### **Database Schema**

```sql
CREATE TABLE user_goals (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NOT NULL,
    steps INT NULL COMMENT 'Daily step goal (1000-20000)',
    calories DECIMAL(8,2) NULL COMMENT 'Daily calorie goal (64-643 kcal)',
    water DECIMAL(4,2) NULL COMMENT 'Daily water goal in liters (1-5 liters)',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    UNIQUE KEY unique_user_goal (user_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

### **Model Relationships**

- **User** `hasOne` **UserGoal**
- **UserGoal** `belongsTo` **User**

### **Controller Methods**

- `show()` - Get user goals
- `store()` - Create/update goals
- `update()` - Update existing goals
- `destroy()` - Delete goals

## 🎯 Key Features

- ✅ **One goal per user** (unique constraint)
- ✅ **Flexible validation** (all fields optional)
- ✅ **Automatic timestamps**
- ✅ **Cascade deletion** (goals deleted when user deleted)
- ✅ **Decimal precision** for calories and water
- ✅ **Comprehensive error handling**
- ✅ **RESTful API design**

## 📱 Perfect for Mobile Apps

This API is designed specifically for mobile fitness apps where users need to:

1. **Set daily goals** for steps, calories, and water
2. **Update goals** as their fitness journey progresses
3. **Track progress** against their personal targets
4. **Reset goals** when starting new fitness programs

The simple, clean API follows the KISS principle and provides everything needed for goal management in your mobile app! 🎉
