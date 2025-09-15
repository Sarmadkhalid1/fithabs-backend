# Recipes CRUD API Documentation

## Overview

This guide provides complete documentation for creating, managing, and retrieving recipes in the FitHabs application. The system supports comprehensive recipe management with nutritional information, dietary tags, allergen information, and image uploads.

## API Endpoints

### Base URL

```
http://localhost:8000/api/v1
```

### Authentication

All admin endpoints require Bearer token authentication:

```javascript
const headers = {
  Authorization: `Bearer ${adminToken}`,
  'Content-Type': 'application/json',
};
```

## 1. Admin Login

### Endpoint

```
POST /admin-login
```

### Request Body

```json
{
  "email": "admin@example.com",
  "password": "admin123"
}
```

### Response

```json
{
  "user": {
    "id": 1,
    "name": "Super Admin",
    "email": "admin@example.com",
    "role": "super_admin"
  },
  "token": "108|mSOJBoRu5ivEfMpa8yAaIsRt69N9mUh3RGwyN8MX357cb412"
}
```

## 2. Create Recipe

### Endpoint

```
POST /recipes
```

### Request Body

```json
{
  "name": "Grilled Chicken Breast with Quinoa",
  "description": "A healthy and protein-rich meal perfect for post-workout recovery",
  "image_url": "https://example.com/chicken-quinoa.jpg",
  "meal_type": "dinner",
  "prep_time_minutes": 15,
  "cook_time_minutes": 25,
  "servings": 2,
  "calories_per_serving": 450,
  "protein_per_serving": 35.5,
  "carbs_per_serving": 28.0,
  "fat_per_serving": 18.2,
  "fiber_per_serving": 4.5,
  "sugar_per_serving": 2.1,
  "ingredients": "2 chicken breasts (6 oz each)\n1 cup quinoa\n2 cups chicken broth\n1 tbsp olive oil\nSalt and pepper to taste\nFresh herbs for garnish",
  "instructions": "1. Season chicken breasts with salt and pepper\n2. Heat olive oil in a pan over medium-high heat\n3. Cook chicken for 6-7 minutes per side\n4. Meanwhile, cook quinoa in chicken broth\n5. Let chicken rest for 5 minutes before slicing\n6. Serve over quinoa and garnish with herbs",
  "dietary_tags": ["high-protein", "low-carb", "gluten-free"],
  "allergen_info": ["none"],
  "difficulty": "easy",
  "is_featured": true,
  "is_active": true,
  "created_by_admin": 1
}
```

### Required Fields

- `name`: Recipe name (string, max 255 chars)
- `description`: Recipe description (string)
- `meal_type`: Meal type (breakfast, lunch, dinner, snack)
- `calories_per_serving`: Calories per serving (integer, min 0)
- `ingredients`: Recipe ingredients (string)
- `instructions`: Cooking instructions (string)
- `difficulty`: Difficulty level (easy, medium, hard)
- `created_by_admin`: Admin user ID (integer)

### Optional Fields

- `image_url`: Recipe image URL (string)
- `image`: Recipe image file (file upload, max 10MB)
- `prep_time_minutes`: Preparation time in minutes (integer, min 0)
- `cook_time_minutes`: Cooking time in minutes (integer, min 0)
- `servings`: Number of servings (integer, min 1)
- `protein_per_serving`: Protein per serving in grams (numeric, min 0)
- `carbs_per_serving`: Carbohydrates per serving in grams (numeric, min 0)
- `fat_per_serving`: Fat per serving in grams (numeric, min 0)
- `fiber_per_serving`: Fiber per serving in grams (numeric, min 0)
- `sugar_per_serving`: Sugar per serving in grams (numeric, min 0)
- `dietary_tags`: Array of dietary tags (array)
- `allergen_info`: Array of allergen information (array)
- `is_featured`: Whether recipe is featured (boolean)
- `is_active`: Whether recipe is active (boolean)

### Meal Types

- `breakfast`: Morning meals
- `lunch`: Midday meals
- `dinner`: Evening meals
- `snack`: Light meals and snacks

### Difficulty Levels

- `easy`: Simple recipes with basic techniques
- `medium`: Moderate complexity with some skill required
- `hard`: Advanced recipes requiring expertise

### Response

```json
{
  "status": "success",
  "message": "Recipe created successfully",
  "data": {
    "id": 1,
    "name": "Grilled Chicken Breast with Quinoa",
    "description": "A healthy and protein-rich meal perfect for post-workout recovery",
    "image_url": "https://example.com/chicken-quinoa.jpg",
    "meal_type": "dinner",
    "prep_time_minutes": 15,
    "cook_time_minutes": 25,
    "servings": 2,
    "calories_per_serving": 450,
    "protein_per_serving": 35.5,
    "carbs_per_serving": 28.0,
    "fat_per_serving": 18.2,
    "fiber_per_serving": 4.5,
    "sugar_per_serving": 2.1,
    "ingredients": "2 chicken breasts (6 oz each)\n1 cup quinoa...",
    "instructions": "1. Season chicken breasts with salt and pepper...",
    "dietary_tags": ["high-protein", "low-carb", "gluten-free"],
    "allergen_info": ["none"],
    "difficulty": "easy",
    "is_featured": true,
    "is_active": true,
    "created_by_admin": 1,
    "created_at": "2025-09-15T14:30:00.000000Z",
    "updated_at": "2025-09-15T14:30:00.000000Z"
  }
}
```

## 3. Get All Recipes

### Endpoint

```
GET /recipes
```

### Response

```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "name": "Grilled Chicken Breast with Quinoa",
      "description": "A healthy and protein-rich meal...",
      "image_url": "https://example.com/chicken-quinoa.jpg",
      "meal_type": "dinner",
      "prep_time_minutes": 15,
      "cook_time_minutes": 25,
      "servings": 2,
      "calories_per_serving": 450,
      "protein_per_serving": 35.5,
      "carbs_per_serving": 28.0,
      "fat_per_serving": 18.2,
      "fiber_per_serving": 4.5,
      "sugar_per_serving": 2.1,
      "ingredients": "2 chicken breasts (6 oz each)...",
      "instructions": "1. Season chicken breasts...",
      "dietary_tags": ["high-protein", "low-carb", "gluten-free"],
      "allergen_info": ["none"],
      "difficulty": "easy",
      "is_featured": true,
      "is_active": true,
      "created_by_admin": 1,
      "created_at": "2025-09-15T14:30:00.000000Z",
      "updated_at": "2025-09-15T14:30:00.000000Z"
    }
  ],
  "count": 1
}
```

## 4. Get Single Recipe

### Endpoint

```
GET /recipes/{id}
```

### Response

```json
{
  "status": "success",
  "data": {
    "id": 1,
    "name": "Grilled Chicken Breast with Quinoa",
    "description": "A healthy and protein-rich meal...",
    "image_url": "https://example.com/chicken-quinoa.jpg",
    "meal_type": "dinner",
    "prep_time_minutes": 15,
    "cook_time_minutes": 25,
    "servings": 2,
    "calories_per_serving": 450,
    "protein_per_serving": 35.5,
    "carbs_per_serving": 28.0,
    "fat_per_serving": 18.2,
    "fiber_per_serving": 4.5,
    "sugar_per_serving": 2.1,
    "ingredients": "2 chicken breasts (6 oz each)...",
    "instructions": "1. Season chicken breasts...",
    "dietary_tags": ["high-protein", "low-carb", "gluten-free"],
    "allergen_info": ["none"],
    "difficulty": "easy",
    "is_featured": true,
    "is_active": true,
    "created_by_admin": 1,
    "created_at": "2025-09-15T14:30:00.000000Z",
    "updated_at": "2025-09-15T14:30:00.000000Z"
  }
}
```

## 5. Search Recipes

### Endpoint

```
GET /recipes/search
```

### Query Parameters

- `query`: Search by name or description (optional)
- `meal_type`: Filter by meal type (optional)
- `dietary_tags`: Filter by dietary tags (optional)
- `allergen_info`: Filter by allergen info (optional)

### Example Request

```
GET /recipes/search?query=chicken&meal_type=dinner&dietary_tags[]=high-protein
```

### Response

```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "name": "Grilled Chicken Breast with Quinoa",
      "meal_type": "dinner",
      "dietary_tags": ["high-protein", "low-carb", "gluten-free"],
      "calories_per_serving": 450,
      "difficulty": "easy"
    }
  ],
  "count": 1
}
```

## 6. Recipe of the Day

### Endpoint

```
GET /recipes/recipe-of-the-day
```

### Response

```json
{
  "status": "success",
  "data": {
    "id": 1,
    "name": "Grilled Chicken Breast with Quinoa",
    "image_url": "https://example.com/chicken-quinoa.jpg",
    "calories_per_serving": 450,
    "meal_type": "dinner",
    "is_favorite": false,
    "tag": "Recipe of the day",
    "detail_url": "/api/v1/recipes/1"
  }
}
```

## 7. Recipe Recommendations

### Endpoint

```
GET /recipes/recommendations
```

### Response

```json
{
  "status": "success",
  "data": {
    "section_1": [
      {
        "id": 2,
        "name": "Quick Oatmeal Bowl",
        "image_url": "https://example.com/oatmeal.jpg",
        "calories_per_serving": 280,
        "meal_type": "breakfast",
        "detail_url": "/api/v1/recipes/2"
      }
    ],
    "section_2": [
      {
        "id": 1,
        "name": "Grilled Chicken Breast with Quinoa",
        "image_url": "https://example.com/chicken-quinoa.jpg",
        "calories_per_serving": 450,
        "meal_type": "dinner",
        "detail_url": "/api/v1/recipes/1"
      }
    ]
  }
}
```

## 8. Nutrition Screen Data

### Endpoint

```
GET /recipes/nutrition-screen
```

### Response

```json
{
  "status": "success",
  "data": {
    "recipe_of_the_day": {
      "id": 1,
      "name": "Grilled Chicken Breast with Quinoa",
      "image_url": "https://example.com/chicken-quinoa.jpg",
      "calories_per_serving": 450,
      "meal_type": "dinner",
      "is_favorite": false,
      "tag": "Recipe of the day",
      "detail_url": "/api/v1/recipes/1"
    },
    "recommendations": {
      "section_1": [
        {
          "id": 2,
          "name": "Quick Oatmeal Bowl",
          "image_url": "https://example.com/oatmeal.jpg",
          "calories_per_serving": 280,
          "meal_type": "breakfast"
        }
      ],
      "section_2": [
        {
          "id": 1,
          "name": "Grilled Chicken Breast with Quinoa",
          "image_url": "https://example.com/chicken-quinoa.jpg",
          "calories_per_serving": 450,
          "meal_type": "dinner"
        }
      ]
    }
  }
}
```

## 9. Update Recipe

### Endpoint

```
PUT /recipes/{id}
```

### Request Body

```json
{
  "name": "Updated Grilled Chicken Breast with Quinoa",
  "description": "Updated description...",
  "calories_per_serving": 480,
  "protein_per_serving": 38.0,
  "dietary_tags": ["high-protein", "low-carb", "gluten-free", "keto"],
  "is_featured": false
}
```

### Response

```json
{
  "status": "success",
  "message": "Recipe updated successfully",
  "data": {
    "id": 1,
    "name": "Updated Grilled Chicken Breast with Quinoa",
    "description": "Updated description...",
    "calories_per_serving": 480,
    "protein_per_serving": 38.0,
    "dietary_tags": ["high-protein", "low-carb", "gluten-free", "keto"],
    "is_featured": false,
    "updated_at": "2025-09-15T15:30:00.000000Z"
  }
}
```

## 10. Delete Recipe

### Endpoint

```
DELETE /recipes/{id}
```

### Response

```json
{
  "status": "success",
  "message": "Recipe deleted successfully"
}
```

## Frontend Implementation Examples

### React Component Example

```jsx
import React, { useState } from 'react';

const RecipeForm = () => {
  const [formData, setFormData] = useState({
    name: '',
    description: '',
    image_url: '',
    meal_type: 'dinner',
    prep_time_minutes: '',
    cook_time_minutes: '',
    servings: 1,
    calories_per_serving: '',
    protein_per_serving: '',
    carbs_per_serving: '',
    fat_per_serving: '',
    fiber_per_serving: '',
    sugar_per_serving: '',
    ingredients: '',
    instructions: '',
    dietary_tags: [],
    allergen_info: [],
    difficulty: 'easy',
    is_featured: false,
    is_active: true,
    created_by_admin: 1,
  });
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  const addDietaryTag = (tag) => {
    if (tag && !formData.dietary_tags.includes(tag)) {
      setFormData({
        ...formData,
        dietary_tags: [...formData.dietary_tags, tag],
      });
    }
  };

  const removeDietaryTag = (tagToRemove) => {
    setFormData({
      ...formData,
      dietary_tags: formData.dietary_tags.filter((tag) => tag !== tagToRemove),
    });
  };

  const addAllergen = (allergen) => {
    if (allergen && !formData.allergen_info.includes(allergen)) {
      setFormData({
        ...formData,
        allergen_info: [...formData.allergen_info, allergen],
      });
    }
  };

  const removeAllergen = (allergenToRemove) => {
    setFormData({
      ...formData,
      allergen_info: formData.allergen_info.filter((allergen) => allergen !== allergenToRemove),
    });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setError(null);

    const token = localStorage.getItem('adminToken');

    try {
      const response = await fetch('/api/v1/recipes', {
        method: 'POST',
        headers: {
          Authorization: `Bearer ${token}`,
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(formData),
      });

      const data = await response.json();

      if (data.status === 'success') {
        alert('Recipe created successfully!');
        // Reset form
        setFormData({
          name: '',
          description: '',
          image_url: '',
          meal_type: 'dinner',
          prep_time_minutes: '',
          cook_time_minutes: '',
          servings: 1,
          calories_per_serving: '',
          protein_per_serving: '',
          carbs_per_serving: '',
          fat_per_serving: '',
          fiber_per_serving: '',
          sugar_per_serving: '',
          ingredients: '',
          instructions: '',
          dietary_tags: [],
          allergen_info: [],
          difficulty: 'easy',
          is_featured: false,
          is_active: true,
          created_by_admin: 1,
        });
      } else {
        setError(data.message || 'Creation failed');
      }
    } catch (error) {
      setError('Network error: ' + error.message);
    } finally {
      setLoading(false);
    }
  };

  return (
    <form onSubmit={handleSubmit} className="recipe-form">
      <h2>Create Recipe</h2>

      {error && <div className="error-message">{error}</div>}

      <div className="form-group">
        <label htmlFor="name">Recipe Name *</label>
        <input
          type="text"
          id="name"
          value={formData.name}
          onChange={(e) => setFormData({ ...formData, name: e.target.value })}
          required
          maxLength={255}
        />
      </div>

      <div className="form-group">
        <label htmlFor="description">Description *</label>
        <textarea
          id="description"
          value={formData.description}
          onChange={(e) => setFormData({ ...formData, description: e.target.value })}
          required
          rows="4"
        />
      </div>

      <div className="form-group">
        <label htmlFor="image_url">Image URL</label>
        <input
          type="url"
          id="image_url"
          value={formData.image_url}
          onChange={(e) => setFormData({ ...formData, image_url: e.target.value })}
        />
      </div>

      <div className="form-row">
        <div className="form-group">
          <label htmlFor="meal_type">Meal Type *</label>
          <select
            id="meal_type"
            value={formData.meal_type}
            onChange={(e) => setFormData({ ...formData, meal_type: e.target.value })}
            required
          >
            <option value="breakfast">Breakfast</option>
            <option value="lunch">Lunch</option>
            <option value="dinner">Dinner</option>
            <option value="snack">Snack</option>
          </select>
        </div>

        <div className="form-group">
          <label htmlFor="difficulty">Difficulty *</label>
          <select
            id="difficulty"
            value={formData.difficulty}
            onChange={(e) => setFormData({ ...formData, difficulty: e.target.value })}
            required
          >
            <option value="easy">Easy</option>
            <option value="medium">Medium</option>
            <option value="hard">Hard</option>
          </select>
        </div>
      </div>

      <div className="form-row">
        <div className="form-group">
          <label htmlFor="prep_time_minutes">Prep Time (minutes)</label>
          <input
            type="number"
            id="prep_time_minutes"
            value={formData.prep_time_minutes}
            onChange={(e) => setFormData({ ...formData, prep_time_minutes: e.target.value })}
            min="0"
          />
        </div>

        <div className="form-group">
          <label htmlFor="cook_time_minutes">Cook Time (minutes)</label>
          <input
            type="number"
            id="cook_time_minutes"
            value={formData.cook_time_minutes}
            onChange={(e) => setFormData({ ...formData, cook_time_minutes: e.target.value })}
            min="0"
          />
        </div>

        <div className="form-group">
          <label htmlFor="servings">Servings</label>
          <input
            type="number"
            id="servings"
            value={formData.servings}
            onChange={(e) => setFormData({ ...formData, servings: e.target.value })}
            min="1"
          />
        </div>
      </div>

      <div className="nutrition-section">
        <h3>Nutritional Information</h3>
        <div className="form-row">
          <div className="form-group">
            <label htmlFor="calories_per_serving">Calories per Serving *</label>
            <input
              type="number"
              id="calories_per_serving"
              value={formData.calories_per_serving}
              onChange={(e) => setFormData({ ...formData, calories_per_serving: e.target.value })}
              required
              min="0"
            />
          </div>

          <div className="form-group">
            <label htmlFor="protein_per_serving">Protein (g)</label>
            <input
              type="number"
              id="protein_per_serving"
              value={formData.protein_per_serving}
              onChange={(e) => setFormData({ ...formData, protein_per_serving: e.target.value })}
              min="0"
              step="0.1"
            />
          </div>

          <div className="form-group">
            <label htmlFor="carbs_per_serving">Carbs (g)</label>
            <input
              type="number"
              id="carbs_per_serving"
              value={formData.carbs_per_serving}
              onChange={(e) => setFormData({ ...formData, carbs_per_serving: e.target.value })}
              min="0"
              step="0.1"
            />
          </div>
        </div>

        <div className="form-row">
          <div className="form-group">
            <label htmlFor="fat_per_serving">Fat (g)</label>
            <input
              type="number"
              id="fat_per_serving"
              value={formData.fat_per_serving}
              onChange={(e) => setFormData({ ...formData, fat_per_serving: e.target.value })}
              min="0"
              step="0.1"
            />
          </div>

          <div className="form-group">
            <label htmlFor="fiber_per_serving">Fiber (g)</label>
            <input
              type="number"
              id="fiber_per_serving"
              value={formData.fiber_per_serving}
              onChange={(e) => setFormData({ ...formData, fiber_per_serving: e.target.value })}
              min="0"
              step="0.1"
            />
          </div>

          <div className="form-group">
            <label htmlFor="sugar_per_serving">Sugar (g)</label>
            <input
              type="number"
              id="sugar_per_serving"
              value={formData.sugar_per_serving}
              onChange={(e) => setFormData({ ...formData, sugar_per_serving: e.target.value })}
              min="0"
              step="0.1"
            />
          </div>
        </div>
      </div>

      <div className="form-group">
        <label htmlFor="ingredients">Ingredients *</label>
        <textarea
          id="ingredients"
          value={formData.ingredients}
          onChange={(e) => setFormData({ ...formData, ingredients: e.target.value })}
          required
          rows="6"
          placeholder="List ingredients with quantities..."
        />
      </div>

      <div className="form-group">
        <label htmlFor="instructions">Instructions *</label>
        <textarea
          id="instructions"
          value={formData.instructions}
          onChange={(e) => setFormData({ ...formData, instructions: e.target.value })}
          required
          rows="8"
          placeholder="Step-by-step cooking instructions..."
        />
      </div>

      <div className="form-group">
        <label>Dietary Tags</label>
        <div className="tags-container">
          {formData.dietary_tags.map((tag, index) => (
            <span key={index} className="tag">
              {tag}
              <button type="button" onClick={() => removeDietaryTag(tag)}>
                ×
              </button>
            </span>
          ))}
          <input
            type="text"
            placeholder="Add dietary tag"
            onKeyPress={(e) => {
              if (e.key === 'Enter') {
                e.preventDefault();
                addDietaryTag(e.target.value);
                e.target.value = '';
              }
            }}
          />
        </div>
      </div>

      <div className="form-group">
        <label>Allergen Information</label>
        <div className="tags-container">
          {formData.allergen_info.map((allergen, index) => (
            <span key={index} className="tag allergen-tag">
              {allergen}
              <button type="button" onClick={() => removeAllergen(allergen)}>
                ×
              </button>
            </span>
          ))}
          <input
            type="text"
            placeholder="Add allergen info"
            onKeyPress={(e) => {
              if (e.key === 'Enter') {
                e.preventDefault();
                addAllergen(e.target.value);
                e.target.value = '';
              }
            }}
          />
        </div>
      </div>

      <div className="form-group">
        <label>
          <input
            type="checkbox"
            checked={formData.is_featured}
            onChange={(e) => setFormData({ ...formData, is_featured: e.target.checked })}
          />
          Featured Recipe
        </label>
      </div>

      <div className="form-group">
        <label>
          <input
            type="checkbox"
            checked={formData.is_active}
            onChange={(e) => setFormData({ ...formData, is_active: e.target.checked })}
          />
          Active Recipe
        </label>
      </div>

      <button type="submit" disabled={loading}>
        {loading ? 'Creating...' : 'Create Recipe'}
      </button>
    </form>
  );
};

export default RecipeForm;
```

## CSS Styling Example

```css
.recipe-form {
  max-width: 900px;
  margin: 0 auto;
  padding: 20px;
  border: 1px solid #ddd;
  border-radius: 8px;
}

.form-group {
  margin-bottom: 20px;
}

.form-group label {
  display: block;
  margin-bottom: 5px;
  font-weight: bold;
}

.form-group input,
.form-group textarea,
.form-group select {
  width: 100%;
  padding: 8px;
  border: 1px solid #ccc;
  border-radius: 4px;
  font-size: 14px;
}

.form-group textarea {
  resize: vertical;
  min-height: 100px;
}

.form-row {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 15px;
}

.nutrition-section {
  background-color: #f8f9fa;
  padding: 20px;
  border-radius: 8px;
  margin: 20px 0;
}

.nutrition-section h3 {
  margin-top: 0;
  color: #495057;
}

.tags-container {
  display: flex;
  flex-wrap: wrap;
  gap: 5px;
  align-items: center;
}

.tag {
  background-color: #007bff;
  color: white;
  padding: 4px 8px;
  border-radius: 4px;
  display: flex;
  align-items: center;
  gap: 5px;
}

.allergen-tag {
  background-color: #dc3545;
}

.tag button {
  background: none;
  border: none;
  color: white;
  cursor: pointer;
  font-weight: bold;
}

button[type='submit'] {
  background-color: #007bff;
  color: white;
  padding: 12px 24px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 16px;
  width: 100%;
}

button[type='submit']:disabled {
  background-color: #ccc;
  cursor: not-allowed;
}

button[type='submit']:hover:not(:disabled) {
  background-color: #0056b3;
}

.error-message {
  background-color: #fee;
  color: #c33;
  padding: 10px;
  border-radius: 4px;
  margin-bottom: 20px;
}
```

## Postman Collection

### Create Recipe

**Method**: `POST`  
**URL**: `{{baseUrl}}/recipes`  
**Headers**: `Authorization: Bearer {{adminToken}}`  
**Body**: JSON

```json
{
  "name": "Grilled Chicken Breast with Quinoa",
  "description": "A healthy and protein-rich meal perfect for post-workout recovery",
  "image_url": "https://example.com/chicken-quinoa.jpg",
  "meal_type": "dinner",
  "prep_time_minutes": 15,
  "cook_time_minutes": 25,
  "servings": 2,
  "calories_per_serving": 450,
  "protein_per_serving": 35.5,
  "carbs_per_serving": 28.0,
  "fat_per_serving": 18.2,
  "fiber_per_serving": 4.5,
  "sugar_per_serving": 2.1,
  "ingredients": "2 chicken breasts (6 oz each)\n1 cup quinoa\n2 cups chicken broth\n1 tbsp olive oil\nSalt and pepper to taste\nFresh herbs for garnish",
  "instructions": "1. Season chicken breasts with salt and pepper\n2. Heat olive oil in a pan over medium-high heat\n3. Cook chicken for 6-7 minutes per side\n4. Meanwhile, cook quinoa in chicken broth\n5. Let chicken rest for 5 minutes before slicing\n6. Serve over quinoa and garnish with herbs",
  "dietary_tags": ["high-protein", "low-carb", "gluten-free"],
  "allergen_info": ["none"],
  "difficulty": "easy",
  "is_featured": true,
  "is_active": true,
  "created_by_admin": 1
}
```

### Get All Recipes

**Method**: `GET`  
**URL**: `{{baseUrl}}/recipes`

### Get Single Recipe

**Method**: `GET`  
**URL**: `{{baseUrl}}/recipes/1`

### Search Recipes

**Method**: `GET`  
**URL**: `{{baseUrl}}/recipes/search?query=chicken&meal_type=dinner`

### Recipe of the Day

**Method**: `GET`  
**URL**: `{{baseUrl}}/recipes/recipe-of-the-day`

### Recipe Recommendations

**Method**: `GET`  
**URL**: `{{baseUrl}}/recipes/recommendations`

### Nutrition Screen Data

**Method**: `GET`  
**URL**: `{{baseUrl}}/recipes/nutrition-screen`

### Update Recipe

**Method**: `PUT`  
**URL**: `{{baseUrl}}/recipes/1`  
**Headers**: `Authorization: Bearer {{adminToken}}`  
**Body**: JSON (same structure as create)

### Delete Recipe

**Method**: `DELETE`  
**URL**: `{{baseUrl}}/recipes/1`  
**Headers**: `Authorization: Bearer {{adminToken}}`

## Error Handling

### Common Error Responses

#### Validation Errors (422)

```json
{
  "message": "The given data was invalid.",
  "errors": {
    "name": ["The name field is required."],
    "calories_per_serving": ["The calories per serving field is required."],
    "meal_type": ["The selected meal type is invalid."]
  }
}
```

#### Not Found Error (404)

```json
{
  "status": "error",
  "message": "Recipe not found",
  "error": "No recipe found with the specified ID"
}
```

#### Server Error (500)

```json
{
  "status": "error",
  "message": "Failed to create recipe",
  "error": "Database connection error"
}
```

## Best Practices

### Recipe Content

1. **Clear Names**: Use descriptive, searchable recipe names
2. **Detailed Descriptions**: Write engaging descriptions that highlight key benefits
3. **Accurate Timing**: Provide realistic prep and cook times
4. **Precise Measurements**: Use consistent measurement units

### Nutritional Information

1. **Accurate Data**: Ensure nutritional information is precise
2. **Complete Macros**: Include all major macronutrients
3. **Per Serving Basis**: Always calculate per serving, not per recipe
4. **Regular Updates**: Keep nutritional data current

### Dietary Tags and Allergens

1. **Comprehensive Tags**: Include all relevant dietary information
2. **Allergen Safety**: Clearly mark all allergens
3. **Consistent Tagging**: Use standardized tag names
4. **User-Friendly**: Use terms users understand

### Image Management

1. **High Quality**: Use clear, appetizing images
2. **Consistent Sizing**: Maintain consistent image dimensions
3. **Fast Loading**: Optimize images for web performance
4. **Alt Text**: Include descriptive alt text for accessibility

This documentation provides everything needed to implement comprehensive recipe management in your FitHabs application!
