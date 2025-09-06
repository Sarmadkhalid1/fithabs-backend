# 🥗 Nutrition Screen API Guide

This guide covers the endpoints needed for the Nutrition Screen that displays recommended meals in a structured way.

## 📱 Screen Layout

The Nutrition Screen consists of:

1. **Recipe of the Day** - A featured recipe at the top
2. **Recommendations** - Multiple recommended recipes organized in sections

## 🚀 API Endpoints

### **1. Recipe of the Day**

#### **Get Recipe of the Day**

```bash
GET /api/v1/recipes/recipe-of-the-day
Authorization: Bearer YOUR_TOKEN
```

**Description**: Get a featured recipe for the day.

**Example Request**:

```bash
curl -X GET "http://localhost:8000/api/v1/recipes/recipe-of-the-day" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json"
```

**Success Response** (200):

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

#### **Get Recommended Recipes**

```bash
GET /api/v1/recipes/recommendations
Authorization: Bearer YOUR_TOKEN
```

**Description**: Get recommended recipes organized in sections.

**Example Request**:

```bash
curl -X GET "http://localhost:8000/api/v1/recipes/recommendations" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json"
```

**Success Response** (200):

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

#### **Get Complete Nutrition Screen Data**

```bash
GET /api/v1/recipes/nutrition-screen
Authorization: Bearer YOUR_TOKEN
```

**Description**: Get all data needed for the nutrition screen in one request.

**Example Request**:

```bash
curl -X GET "http://localhost:8000/api/v1/recipes/nutrition-screen" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json"
```

**Success Response** (200):

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

### **4. Recipe Details**

#### **Get Recipe Details**

```bash
GET /api/v1/recipes/{recipe_id}
Authorization: Bearer YOUR_TOKEN
```

**Description**: Get complete details for a specific recipe.

**Example Request**:

```bash
curl -X GET "http://localhost:8000/api/v1/recipes/8" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json"
```

**Success Response** (200):

```json
{
  "status": "success",
  "data": {
    "id": 8,
    "name": "Avocado Toast",
    "description": "Whole grain toast topped with mashed avocado and microgreens",
    "image_url": "https://images.unsplash.com/photo-1541519227354-08fa5d50c44d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
    "meal_type": "breakfast",
    "prep_time_minutes": 8,
    "cook_time_minutes": 3,
    "servings": 1,
    "calories_per_serving": 220,
    "protein_per_serving": 6.8,
    "carbs_per_serving": 28.5,
    "fat_per_serving": 12.3,
    "fiber_per_serving": 8.9,
    "sugar_per_serving": 2.1,
    "ingredients": "2 slices whole grain bread, 1 ripe avocado, 1 tbsp olive oil, microgreens, salt and pepper",
    "instructions": "Toast bread, mash avocado with olive oil, spread on toast, top with microgreens",
    "dietary_tags": ["vegetarian", "vegan"],
    "allergen_info": [],
    "difficulty": "easy",
    "is_featured": true,
    "is_active": true,
    "created_by_admin": 3,
    "created_at": "2025-09-04T14:18:34.000000Z",
    "updated_at": "2025-09-04T14:18:34.000000Z"
  }
}
```

## 🎯 Data Structure

### **Recipe of the Day Object**

- `id` - Unique recipe identifier
- `name` - Recipe name (e.g., "Avocado and egg toast")
- `image_url` - High-quality image URL
- `calories_per_serving` - Calorie count (e.g., 120)
- `meal_type` - Meal type (breakfast, lunch, dinner, snack)
- `is_favorite` - Boolean for favorite status
- `tag` - Display tag (e.g., "Recipe of the day")
- `detail_url` - URL to get full recipe details

### **Recommendation Object**

- `id` - Unique recipe identifier
- `name` - Recipe name (e.g., "Fruit smoothie", "Salads with quinoa")
- `image_url` - High-quality image URL
- `calories_per_serving` - Calorie count (e.g., 120)
- `meal_type` - Meal type (breakfast, lunch, dinner, snack)
- `detail_url` - URL to get full recipe details

### **Recommendations Structure**

- `section_1` - Array of 2 recipes (Quick & Healthy)
- `section_2` - Array of 3 recipes (Featured & Popular)

## 🧪 Testing

### **Run Test Script**

```bash
chmod +x test_nutrition_screen.sh
./test_nutrition_screen.sh
```

### **Individual Tests**

#### **Test Recipe of the Day**

```bash
curl -X GET "http://localhost:8000/api/v1/recipes/recipe-of-the-day" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json"
```

#### **Test Recommendations**

```bash
curl -X GET "http://localhost:8000/api/v1/recipes/recommendations" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json"
```

#### **Test Complete Screen**

```bash
curl -X GET "http://localhost:8000/api/v1/recipes/nutrition-screen" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json"
```

## 📱 Mobile App Integration

### **React Native Example**

```javascript
// Get nutrition screen data
const fetchNutritionScreen = async () => {
  try {
    const response = await fetch('/api/v1/recipes/nutrition-screen', {
      headers: {
        Authorization: `Bearer ${token}`,
        'Content-Type': 'application/json',
      },
    });

    const data = await response.json();

    if (data.status === 'success') {
      const { recipe_of_the_day, recommendations } = data.data;

      // Update UI with recipe of the day
      setRecipeOfTheDay(recipe_of_the_day);

      // Update UI with recommendations
      setRecommendations(recommendations);
    }
  } catch (error) {
    console.error('Error fetching nutrition screen:', error);
  }
};

// Get recipe details when user taps on a recipe
const fetchRecipeDetails = async (recipeId) => {
  try {
    const response = await fetch(`/api/v1/recipes/${recipeId}`, {
      headers: {
        Authorization: `Bearer ${token}`,
        'Content-Type': 'application/json',
      },
    });

    const data = await response.json();

    if (data.status === 'success') {
      // Navigate to recipe detail screen
      navigation.navigate('RecipeDetail', { recipe: data.data });
    }
  } catch (error) {
    console.error('Error fetching recipe details:', error);
  }
};
```

### **Flutter Example**

```dart
// Get nutrition screen data
Future<void> fetchNutritionScreen() async {
  try {
    final response = await http.get(
      Uri.parse('$baseUrl/api/v1/recipes/nutrition-screen'),
      headers: {
        'Authorization': 'Bearer $token',
        'Content-Type': 'application/json',
      },
    );

    if (response.statusCode == 200) {
      final data = json.decode(response.body);

      if (data['status'] == 'success') {
        final nutritionData = data['data'];

        // Update UI with recipe of the day
        setState(() {
          recipeOfTheDay = nutritionData['recipe_of_the_day'];
          recommendations = nutritionData['recommendations'];
        });
      }
    }
  } catch (e) {
    print('Error fetching nutrition screen: $e');
  }
}
```

## 🔧 Implementation Details

### **Recipe Selection Logic**

#### **Recipe of the Day**

- Prioritizes featured recipes (`is_featured = true`)
- Falls back to any active recipe if no featured recipes
- Randomly selects from available options
- Returns 404 if no recipes available

#### **Recommendations**

- **Section 1**: Quick & Healthy recipes
  - Easy difficulty
  - ≤ 300 calories per serving
  - Random selection
  - Limit: 2 recipes

- **Section 2**: Featured & Popular recipes
  - Featured recipes (`is_featured = true`)
  - Random selection
  - Limit: 3 recipes

### **Error Handling**

- Proper HTTP status codes (200, 404, 500)
- Consistent error response format
- Graceful fallbacks for missing data

## 🎨 UI Integration Tips

1. **Recipe of the Day**: Display as large card with "Recipe of the day" tag
2. **Section 1**: Display as 2 horizontal cards
3. **Section 2**: Display as 3 vertical cards
4. **Calories**: Show with flame/energy icon (e.g., "🔥 120 Cal")
5. **Images**: Use high-quality Unsplash images
6. **Favorites**: Implement heart icon functionality (currently placeholder)

## ✅ Features

- ✅ Recipe of the day with tag
- ✅ Organized recommendations in sections
- ✅ Calorie information for each recipe
- ✅ High-quality images
- ✅ Meal type classification
- ✅ Favorite status (placeholder)
- ✅ Complete screen data in one request
- ✅ Proper error handling
- ✅ Authentication required
- ✅ Follows KISS principle
