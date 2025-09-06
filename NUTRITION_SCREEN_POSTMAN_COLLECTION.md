# 🥗 Nutrition Screen - Postman Collection

## 📋 Collection Overview

This collection contains all the endpoints needed for the Nutrition Screen functionality.

## 🔐 Authentication

**Base URL**: `http://localhost:8000/api/v1`

**Authentication**: Bearer Token (required for all endpoints)

### **Get Authentication Token**

```bash
POST /api/v1/login
Content-Type: application/json

{
  "email": "john@example.com",
  "password": "password123"
}
```

## 🚀 Endpoints

### **1. Recipe of the Day**

```bash
GET /api/v1/recipes/recipe-of-the-day
Authorization: Bearer YOUR_TOKEN
```

**Description**: Get a featured recipe for the day.

**Expected Response**:

```json
{
  "status": "success",
  "data": {
    "id": 3,
    "name": "Grilled Chicken Salad",
    "image_url": "https://example.com/images/grilled-chicken-salad.jpg",
    "calories_per_serving": 350,
    "meal_type": "lunch",
    "is_favorite": false,
    "tag": "Recipe of the day"
  }
}
```

### **2. Recommendations**

```bash
GET /api/v1/recipes/recommendations
Authorization: Bearer YOUR_TOKEN
```

**Description**: Get recommended recipes organized in sections.

**Expected Response**:

```json
{
  "status": "success",
  "data": {
    "section_1": [
      {
        "id": 13,
        "name": "Apple with Almond Butter",
        "image_url": "https://images.unsplash.com/photo-1560806887-1e4cd0b6cbd6?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
        "calories_per_serving": 150,
        "meal_type": "snack"
      },
      {
        "id": 7,
        "name": "Greek Yogurt Parfait",
        "image_url": "https://images.unsplash.com/photo-1488477181946-6428a02819d3?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
        "calories_per_serving": 180,
        "meal_type": "breakfast"
      }
    ],
    "section_2": [
      {
        "id": 4,
        "name": "Grilled Chicken Salad",
        "image_url": "https://example.com/images/grilled-chicken-salad.jpg",
        "calories_per_serving": 350,
        "meal_type": "lunch"
      },
      {
        "id": 11,
        "name": "Salmon with Roasted Vegetables",
        "image_url": "https://images.unsplash.com/photo-1467003909585-2f8a72700288?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
        "calories_per_serving": 380,
        "meal_type": "dinner"
      },
      {
        "id": 9,
        "name": "Quinoa Salad Bowl",
        "image_url": "https://images.unsplash.com/photo-1512621776951-a57141f2eefd?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
        "calories_per_serving": 320,
        "meal_type": "lunch"
      }
    ]
  }
}
```

### **3. Complete Nutrition Screen**

```bash
GET /api/v1/recipes/nutrition-screen
Authorization: Bearer YOUR_TOKEN
```

**Description**: Get all data needed for the nutrition screen in one request.

**Expected Response**:

```json
{
  "status": "success",
  "data": {
    "recipe_of_the_day": {
      "id": 9,
      "name": "Quinoa Salad Bowl",
      "image_url": "https://images.unsplash.com/photo-1512621776951-a57141f2eefd?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
      "calories_per_serving": 320,
      "meal_type": "lunch",
      "is_favorite": false,
      "tag": "Recipe of the day"
    },
    "recommendations": {
      "section_1": [
        {
          "id": 8,
          "name": "Avocado Toast",
          "image_url": "https://images.unsplash.com/photo-1541519227354-08fa5d50c44d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
          "calories_per_serving": 280,
          "meal_type": "breakfast"
        },
        {
          "id": 14,
          "name": "Greek Yogurt with Berries",
          "image_url": "https://images.unsplash.com/photo-1488477181946-6428a02819d3?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
          "calories_per_serving": 120,
          "meal_type": "snack"
        }
      ],
      "section_2": [
        {
          "id": 4,
          "name": "Grilled Chicken Salad",
          "image_url": "https://example.com/images/grilled-chicken-salad.jpg",
          "calories_per_serving": 350,
          "meal_type": "lunch"
        },
        {
          "id": 11,
          "name": "Salmon with Roasted Vegetables",
          "image_url": "https://images.unsplash.com/photo-1467003909585-2f8a72700288?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
          "calories_per_serving": 380,
          "meal_type": "dinner"
        },
        {
          "id": 9,
          "name": "Quinoa Salad Bowl",
          "image_url": "https://images.unsplash.com/photo-1512621776951-a57141f2eefd?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
          "calories_per_serving": 320,
          "meal_type": "lunch"
        }
      ]
    }
  }
}
```

## 🧪 Testing Steps

### **Step 1: Get Authentication Token**

1. Send POST request to `/api/v1/login`
2. Use credentials: `john@example.com` / `password123`
3. Copy the token from response

### **Step 2: Test Recipe of the Day**

1. Send GET request to `/api/v1/recipes/recipe-of-the-day`
2. Add Authorization header: `Bearer YOUR_TOKEN`
3. Verify response contains recipe data

### **Step 3: Test Recommendations**

1. Send GET request to `/api/v1/recipes/recommendations`
2. Add Authorization header: `Bearer YOUR_TOKEN`
3. Verify response contains two sections with recipes

### **Step 4: Test Complete Screen**

1. Send GET request to `/api/v1/recipes/nutrition-screen`
2. Add Authorization header: `Bearer YOUR_TOKEN`
3. Verify response contains both recipe of the day and recommendations

## 📱 Mobile App Integration

### **React Native**

```javascript
const fetchNutritionScreen = async () => {
  const response = await fetch('/api/v1/recipes/nutrition-screen', {
    headers: {
      Authorization: `Bearer ${token}`,
      'Content-Type': 'application/json',
    },
  });

  const data = await response.json();
  return data.data;
};
```

### **Flutter**

```dart
Future<Map<String, dynamic>> fetchNutritionScreen() async {
  final response = await http.get(
    Uri.parse('$baseUrl/api/v1/recipes/nutrition-screen'),
    headers: {
      'Authorization': 'Bearer $token',
      'Content-Type': 'application/json',
    },
  );

  final data = json.decode(response.body);
  return data['data'];
}
```

## ✅ Success Criteria

- [ ] Recipe of the day endpoint returns valid data
- [ ] Recommendations endpoint returns two sections
- [ ] Complete screen endpoint returns all data
- [ ] All endpoints require authentication
- [ ] Response format matches expected structure
- [ ] Images are accessible and high-quality
- [ ] Calorie information is present
- [ ] Meal types are correctly classified

## 🎯 Notes

- All endpoints require authentication
- Recipe of the day is randomly selected from featured recipes
- Recommendations are organized in two sections
- Complete screen endpoint provides all data in one request
- Follows KISS principle for simplicity
