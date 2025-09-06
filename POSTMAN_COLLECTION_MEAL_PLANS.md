# 🍽️ FitHabs Meal Plan API - Postman Collection

## 📋 **Collection Overview**

This collection contains all the endpoints for testing the meal plan personalization system.

## 🔧 **Environment Variables**

Set these variables in your Postman environment:

- `baseUrl`: `http://localhost:8000/api/v1`
- `authToken`: Your authentication token

## 🚀 **Authentication**

All requests require Bearer token authentication:

```
Authorization: Bearer {{authToken}}
```

---

## 📝 **1. User Preferences Management**

### **1.1 Get User Preferences**

```bash
curl --location '{{baseUrl}}/user-preferences' \
--header 'Authorization: Bearer {{authToken}}' \
--header 'Content-Type: application/json'
```

**Method:** GET  
**URL:** `{{baseUrl}}/user-preferences`  
**Headers:**

- Authorization: Bearer {{authToken}}
- Content-Type: application/json

---

### **1.2 Create/Update User Preferences**

```bash
curl --location '{{baseUrl}}/user-preferences' \
--header 'Authorization: Bearer {{authToken}}' \
--header 'Content-Type: application/json' \
--data '{
    "dietary_preferences": ["vegetarian", "gluten_free"],
    "allergies": ["nuts", "dairy"],
    "meal_types": ["breakfast", "lunch", "dinner"],
    "caloric_goal": "1500_2000",
    "cooking_time_preference": "15_30",
    "serving_preference": "2"
}'
```

**Method:** POST  
**URL:** `{{baseUrl}}/user-preferences`  
**Headers:**

- Authorization: Bearer {{authToken}}
- Content-Type: application/json

**Body (raw JSON):**

```json
{
  "dietary_preferences": ["vegetarian", "gluten_free"],
  "allergies": ["nuts", "dairy"],
  "meal_types": ["breakfast", "lunch", "dinner"],
  "caloric_goal": "1500_2000",
  "cooking_time_preference": "15_30",
  "serving_preference": "2"
}
```

---

### **1.3 Update User Preferences**

```bash
curl --location --request PUT '{{baseUrl}}/user-preferences' \
--header 'Authorization: Bearer {{authToken}}' \
--header 'Content-Type: application/json' \
--data '{
    "caloric_goal": "less_than_1500",
    "cooking_time_preference": "less_than_15"
}'
```

**Method:** PUT  
**URL:** `{{baseUrl}}/user-preferences`  
**Headers:**

- Authorization: Bearer {{authToken}}
- Content-Type: application/json

**Body (raw JSON):**

```json
{
  "caloric_goal": "less_than_1500",
  "cooking_time_preference": "less_than_15"
}
```

---

## 🍽️ **2. Meal Plans**

### **2.1 Get All Meal Plans**

```bash
curl --location '{{baseUrl}}/meal-plans' \
--header 'Authorization: Bearer {{authToken}}' \
--header 'Content-Type: application/json'
```

**Method:** GET  
**URL:** `{{baseUrl}}/meal-plans`  
**Headers:**

- Authorization: Bearer {{authToken}}
- Content-Type: application/json

---

### **2.2 Get Specific Meal Plan**

```bash
curl --location '{{baseUrl}}/meal-plans/1' \
--header 'Authorization: Bearer {{authToken}}' \
--header 'Content-Type: application/json'
```

**Method:** GET  
**URL:** `{{baseUrl}}/meal-plans/1`  
**Headers:**

- Authorization: Bearer {{authToken}}
- Content-Type: application/json

---

### **2.3 Get Personalized Meal Plans**

```bash
curl --location '{{baseUrl}}/meal-plans/personalized' \
--header 'Authorization: Bearer {{authToken}}' \
--header 'Content-Type: application/json'
```

**Method:** GET  
**URL:** `{{baseUrl}}/meal-plans/personalized`  
**Headers:**

- Authorization: Bearer {{authToken}}
- Content-Type: application/json

---

### **2.4 Get Meal Plans by Meal Type**

```bash
curl --location '{{baseUrl}}/meal-plans/meal-type/breakfast' \
--header 'Authorization: Bearer {{authToken}}' \
--header 'Content-Type: application/json'
```

**Method:** GET  
**URL:** `{{baseUrl}}/meal-plans/meal-type/breakfast`  
**Headers:**

- Authorization: Bearer {{authToken}}
- Content-Type: application/json

**Supported meal types:** breakfast, lunch, dinner, snack

---

### **2.5 Filter Meal Plans**

```bash
curl --location '{{baseUrl}}/meal-plans/filter?difficulty=easy&goals[]=weight_loss' \
--header 'Authorization: Bearer {{authToken}}' \
--header 'Content-Type: application/json'
```

**Method:** GET  
**URL:** `{{baseUrl}}/meal-plans/filter?difficulty=easy&goals[]=weight_loss`  
**Headers:**

- Authorization: Bearer {{authToken}}
- Content-Type: application/json

---

## 👤 **3. User Meal Plans**

### **3.1 Get Current Active Meal Plan**

```bash
curl --location '{{baseUrl}}/user-meal-plans/current' \
--header 'Authorization: Bearer {{authToken}}' \
--header 'Content-Type: application/json'
```

**Method:** GET  
**URL:** `{{baseUrl}}/user-meal-plans/current`  
**Headers:**

- Authorization: Bearer {{authToken}}
- Content-Type: application/json

---

### **3.2 Get Meal Plan for Specific Date**

```bash
curl --location '{{baseUrl}}/user-meal-plans/date/2025-08-30' \
--header 'Authorization: Bearer {{authToken}}' \
--header 'Content-Type: application/json'
```

**Method:** GET  
**URL:** `{{baseUrl}}/user-meal-plans/date/2025-08-30`  
**Headers:**

- Authorization: Bearer {{authToken}}
- Content-Type: application/json

---

### **3.3 Assign Meal Plan to User**

```bash
curl --location '{{baseUrl}}/user-meal-plans' \
--header 'Authorization: Bearer {{authToken}}' \
--header 'Content-Type: application/json' \
--data '{
    "meal_plan_id": 1,
    "start_date": "2025-08-30",
    "end_date": "2025-09-05",
    "is_active": true
}'
```

**Method:** POST  
**URL:** `{{baseUrl}}/user-meal-plans`  
**Headers:**

- Authorization: Bearer {{authToken}}
- Content-Type: application/json

**Body (raw JSON):**

```json
{
  "meal_plan_id": 1,
  "start_date": "2025-08-30",
  "end_date": "2025-09-05",
  "is_active": true
}
```

---

## 🍳 **4. Recipes**

### **4.1 Get All Recipes**

```bash
curl --location '{{baseUrl}}/recipes' \
--header 'Authorization: Bearer {{authToken}}' \
--header 'Content-Type: application/json'
```

**Method:** GET  
**URL:** `{{baseUrl}}/recipes`  
**Headers:**

- Authorization: Bearer {{authToken}}
- Content-Type: application/json

---

### **4.2 Get Specific Recipe**

```bash
curl --location '{{baseUrl}}/recipes/1' \
--header 'Authorization: Bearer {{authToken}}' \
--header 'Content-Type: application/json'
```

**Method:** GET  
**URL:** `{{baseUrl}}/recipes/1`  
**Headers:**

- Authorization: Bearer {{authToken}}
- Content-Type: application/json

---

### **4.3 Search Recipes**

```bash
curl --location '{{baseUrl}}/recipes/search?query=oatmeal&meal_type=breakfast' \
--header 'Authorization: Bearer {{authToken}}' \
--header 'Content-Type: application/json'
```

**Method:** GET  
**URL:** `{{baseUrl}}/recipes/search?query=oatmeal&meal_type=breakfast`  
**Headers:**

- Authorization: Bearer {{authToken}}
- Content-Type: application/json

---

## 🧪 **Testing Flow**

### **Step 1: Set User Preferences**

1. Use **1.2 Create/Update User Preferences** to set initial preferences
2. Verify with **1.1 Get User Preferences**

### **Step 2: Test Personalized Meal Plans**

1. Use **2.3 Get Personalized Meal Plans** to see filtered results
2. Use **2.4 Get Meal Plans by Meal Type** to test meal type filtering

### **Step 3: Test User Meal Plan Management**

1. Use **3.3 Assign Meal Plan to User** to assign a meal plan
2. Use **3.1 Get Current Active Meal Plan** to verify assignment
3. Use **3.2 Get Meal Plan for Specific Date** to test date-based retrieval

### **Step 4: Test Recipe System**

1. Use **4.1 Get All Recipes** to see available recipes
2. Use **4.3 Search Recipes** to test recipe search functionality

---

## 📊 **Sample Data Available**

The database now contains:

- ✅ **9 Recipes** (breakfast, lunch, dinner, snacks)
- ✅ **4 Meal Plans** (vegetarian, high protein, gluten-free, vegan)
- ✅ **Complete meal plan assignments** with recipes for each day

### **Available Meal Plans:**

1. **Vegetarian Weight Loss Plan** (ID: 1) - 1500-1800 calories
2. **High Protein Muscle Building** (ID: 2) - 2200-2500 calories
3. **Gluten-Free Wellness Plan** (ID: 3) - 1800-2100 calories
4. **Vegan Energy Boost** (ID: 4) - 1600-1900 calories

### **Available Recipes:**

- **Breakfast**: Oatmeal with Berries, Greek Yogurt Parfait, Avocado Toast
- **Lunch**: Quinoa Salad Bowl, Grilled Chicken Salad
- **Dinner**: Salmon with Roasted Vegetables, Vegetarian Pasta Primavera
- **Snacks**: Apple with Almond Butter, Greek Yogurt with Berries

---

## 🎯 **Expected Responses**

### **Successful User Preferences Response:**

```json
{
  "status": "success",
  "message": "User preferences saved successfully",
  "data": {
    "id": 1,
    "user_id": 1,
    "dietary_preferences": ["vegetarian", "gluten_free"],
    "allergies": ["nuts", "dairy"],
    "meal_types": ["breakfast", "lunch", "dinner"],
    "caloric_goal": "1500_2000",
    "cooking_time_preference": "15_30",
    "serving_preference": "2",
    "created_at": "2025-08-30T10:00:00.000000Z",
    "updated_at": "2025-08-30T10:00:00.000000Z"
  }
}
```

### **Successful Personalized Meal Plans Response:**

```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "name": "Vegetarian Weight Loss Plan",
      "description": "7-day vegetarian meal plan designed for weight loss",
      "dietary_preferences": ["vegetarian"],
      "allergen_free": ["nuts", "dairy"],
      "target_calories_min": 1500,
      "target_calories_max": 1800,
      "meal_plan_recipes": [
        {
          "id": 1,
          "day_number": 1,
          "meal_type": "breakfast",
          "recipe": {
            "id": 1,
            "name": "Oatmeal with Berries",
            "calories_per_serving": 250,
            "image_url": "https://images.unsplash.com/photo-1517686469429-8bdb88b9f907?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
          }
        }
      ]
    }
  ],
  "count": 1
}
```

---

## 🚀 **Ready to Test!**

All endpoints are now ready for testing in Postman. The sample data provides realistic meal plans and recipes to test the personalization features effectively.
