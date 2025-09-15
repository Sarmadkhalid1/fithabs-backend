# FitHabs Admin Dashboard API Documentation

## Overview

This comprehensive documentation provides all the necessary endpoints for building a dynamic admin dashboard for the FitHabs application. The admin dashboard allows administrators to manage users, content, analytics, and system operations.

## Base URL

```
http://127.0.0.1:8000/api/v1
```

## Authentication

All admin endpoints require **admin authentication** using Laravel Sanctum. Only users with `AdminUser` model can access these endpoints.

**Authentication Header Required:**

```
Authorization: Bearer {admin_token}
```

**Admin Login Endpoint:**

```http
POST /api/v1/admin-login
Content-Type: application/json

{
  "email": "admin@example.com",
  "password": "password"
}
```

---

## 1. ADMIN AUTHENTICATION & ACCESS

### 1.1 Admin Login

**Endpoint:** `POST /admin-login`

**Description:** Authenticate admin users and receive access token.

**Request:**

```http
POST /api/v1/admin-login
Content-Type: application/json

{
  "email": "admin@example.com",
  "password": "password123"
}
```

**Response:**

```json
{
  "status": "success",
  "message": "Admin login successful",
  "data": {
    "admin": {
      "id": 1,
      "name": "Admin User",
      "email": "admin@example.com",
      "role": "super_admin",
      "permissions": ["manage_users", "manage_content", "view_analytics"],
      "is_active": true,
      "created_at": "2025-01-01T00:00:00.000000Z"
    },
    "token": "1|abcdef123456789..."
  }
}
```

### 1.2 Check Admin Access

**Endpoint:** `GET /admin-check`

**Description:** Verify if the current user has admin access.

**Request:**

```http
GET /api/v1/admin-check
Authorization: Bearer {admin_token}
```

**Response:**

```json
{
  "message": "Admin access granted"
}
```

**Error Response (403):**

```json
{
  "error": "Admin access required"
}
```

---

## 2. USER MANAGEMENT

### 2.1 Get All Users

**Endpoint:** `GET /users`

**Description:** Retrieve all registered users with pagination and filtering options.

**Request:**

```http
GET /api/v1/users?page=1&per_page=20&search=john&status=active
Authorization: Bearer {admin_token}
```

**Query Parameters:**

- `page` (optional): Page number for pagination (default: 1)
- `per_page` (optional): Items per page (default: 20, max: 100)
- `search` (optional): Search by name or email
- `status` (optional): Filter by status (active, inactive)
- `created_from` (optional): Filter users created from date (YYYY-MM-DD)
- `created_to` (optional): Filter users created to date (YYYY-MM-DD)

**Response:**

```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "age": 25,
      "gender": "male",
      "weight": 70.5,
      "weight_unit": "kg",
      "height": 175,
      "height_unit": "cm",
      "goal": "weight_loss",
      "activity_level": "moderate",
      "daily_calorie_goal": 2000,
      "daily_steps_goal": 10000,
      "daily_water_goal": 8,
      "dob": "1998-05-15",
      "phone": "+1234567890",
      "email_verified_at": "2025-01-01T00:00:00.000000Z",
      "created_at": "2025-01-01T00:00:00.000000Z",
      "updated_at": "2025-01-01T00:00:00.000000Z",
      "stats": {
        "total_workouts": 15,
        "total_meal_plans": 8,
        "last_active": "2025-01-15T10:30:00.000000Z"
      }
    }
  ],
  "pagination": {
    "current_page": 1,
    "per_page": 20,
    "total": 150,
    "last_page": 8,
    "from": 1,
    "to": 20
  }
}
```

### 2.2 Get User Details

**Endpoint:** `GET /users/{id}`

**Description:** Get detailed information about a specific user.

**Request:**

```http
GET /api/v1/users/1
Authorization: Bearer {admin_token}
```

**Response:**

```json
{
  "status": "success",
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "age": 25,
    "gender": "male",
    "weight": 70.5,
    "weight_unit": "kg",
    "height": 175,
    "height_unit": "cm",
    "goal": "weight_loss",
    "activity_level": "moderate",
    "daily_calorie_goal": 2000,
    "daily_steps_goal": 10000,
    "daily_water_goal": 8,
    "dob": "1998-05-15",
    "phone": "+1234567890",
    "email_verified_at": "2025-01-01T00:00:00.000000Z",
    "created_at": "2025-01-01T00:00:00.000000Z",
    "updated_at": "2025-01-01T00:00:00.000000Z",
    "recent_activities": [
      {
        "id": 1,
        "type": "workout_completed",
        "description": "Completed 'Morning Cardio' workout",
        "created_at": "2025-01-15T08:00:00.000000Z"
      }
    ],
    "progress_summary": {
      "total_workouts_completed": 15,
      "total_calories_burned": 4500,
      "average_workout_rating": 4.2,
      "current_streak": 5,
      "longest_streak": 12
    }
  }
}
```

---

## 3. ADMIN USER MANAGEMENT

### 3.1 Get All Admin Users

**Endpoint:** `GET /admin-users`

**Description:** Retrieve all admin users in the system.

**Request:**

```http
GET /api/v1/admin-users
Authorization: Bearer {admin_token}
```

**Response:**

```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "name": "Super Admin",
      "email": "superadmin@example.com",
      "role": "super_admin",
      "permissions": ["manage_users", "manage_content", "view_analytics", "manage_admins"],
      "is_active": true,
      "created_at": "2025-01-01T00:00:00.000000Z",
      "updated_at": "2025-01-01T00:00:00.000000Z"
    },
    {
      "id": 2,
      "name": "Content Manager",
      "email": "content@example.com",
      "role": "editor",
      "permissions": ["manage_content"],
      "is_active": true,
      "created_at": "2025-01-02T00:00:00.000000Z",
      "updated_at": "2025-01-02T00:00:00.000000Z"
    }
  ]
}
```

### 3.2 Create Admin User

**Endpoint:** `POST /admin-users`

**Description:** Create a new admin user.

**Request:**

```http
POST /api/v1/admin-users
Authorization: Bearer {admin_token}
Content-Type: application/json

{
  "name": "New Admin",
  "email": "newadmin@example.com",
  "password": "securepassword123",
  "role": "admin",
  "permissions": ["manage_content", "view_analytics"],
  "is_active": true
}
```

**Response:**

```json
{
  "status": "success",
  "message": "Admin user created successfully",
  "data": {
    "id": 3,
    "name": "New Admin",
    "email": "newadmin@example.com",
    "role": "admin",
    "permissions": ["manage_content", "view_analytics"],
    "is_active": true,
    "created_at": "2025-01-15T12:00:00.000000Z",
    "updated_at": "2025-01-15T12:00:00.000000Z"
  }
}
```

### 3.3 Update Admin User

**Endpoint:** `PUT /admin-users/{id}`

**Description:** Update an existing admin user.

**Request:**

```http
PUT /api/v1/admin-users/3
Authorization: Bearer {admin_token}
Content-Type: application/json

{
  "name": "Updated Admin",
  "role": "super_admin",
  "permissions": ["manage_users", "manage_content", "view_analytics", "manage_admins"],
  "is_active": true
}
```

**Response:**

```json
{
  "status": "success",
  "message": "Admin user updated successfully",
  "data": {
    "id": 3,
    "name": "Updated Admin",
    "email": "newadmin@example.com",
    "role": "super_admin",
    "permissions": ["manage_users", "manage_content", "view_analytics", "manage_admins"],
    "is_active": true,
    "created_at": "2025-01-15T12:00:00.000000Z",
    "updated_at": "2025-01-15T14:30:00.000000Z"
  }
}
```

### 3.4 Delete Admin User

**Endpoint:** `DELETE /admin-users/{id}`

**Description:** Delete an admin user.

**Request:**

```http
DELETE /api/v1/admin-users/3
Authorization: Bearer {admin_token}
```

**Response:**

```json
{
  "status": "success",
  "message": "Admin user deleted successfully"
}
```

---

## 4. CONTENT MANAGEMENT

### 4.1 WORKOUT MANAGEMENT

#### 4.1.1 Create Workout

**Endpoint:** `POST /workouts`

**Description:** Create a new workout program. Supports both JSON and FormData with image upload.

**Request (JSON):**

```http
POST /api/v1/workouts
Authorization: Bearer {admin_token}
Content-Type: application/json

{
  "name": "Morning Cardio Blast",
  "description": "High-intensity cardio workout for morning energy",
  "image_url": "https://example.com/images/cardio.jpg",
  "difficulty": "medium",
  "type": "cardio",
  "duration_minutes": 30,
  "calories_per_session": 300,
  "equipment_needed": ["dumbbells", "mat"],
  "tags": ["cardio", "morning", "high-intensity"],
  "is_featured": true,
  "is_active": true,
  "created_by_admin": 1
}
```

**Request (FormData with Image Upload):**

```http
POST /api/v1/workouts
Authorization: Bearer {admin_token}
Content-Type: multipart/form-data

name: "Morning Cardio Blast"
description: "High-intensity cardio workout for morning energy"
difficulty: "medium"
type: "cardio"
duration_minutes: "30"
calories_per_session: "300"
equipment_needed[]: "dumbbells"
equipment_needed[]: "mat"
tags[]: "cardio"
tags[]: "morning"
tags[]: "high-intensity"
is_featured: "true"
is_active: "true"
created_by_admin: "1"
image: [FILE_UPLOAD]
```

**Response:**

```json
{
  "status": "success",
  "message": "Workout created successfully",
  "data": {
    "id": 1,
    "name": "Morning Cardio Blast",
    "description": "High-intensity cardio workout for morning energy",
    "image_url": "https://example.com/images/cardio.jpg",
    "difficulty": "medium",
    "type": "cardio",
    "duration_minutes": 30,
    "calories_per_session": 300,
    "equipment_needed": ["dumbbells", "mat"],
    "tags": ["cardio", "morning", "high-intensity"],
    "is_featured": true,
    "is_active": true,
    "created_by_admin": 1,
    "created_at": "2025-01-15T12:00:00.000000Z",
    "updated_at": "2025-01-15T12:00:00.000000Z"
  }
}
```

#### 4.1.2 Update Workout

**Endpoint:** `PUT /workouts/{workout}`

**Description:** Update an existing workout. Supports both JSON and FormData with image upload.

**Request (JSON):**

```http
PUT /api/v1/workouts/1
Authorization: Bearer {admin_token}
Content-Type: application/json

{
  "name": "Updated Morning Cardio",
  "description": "Updated description",
  "difficulty": "hard",
  "duration_minutes": 45,
  "calories_per_session": 400,
  "is_featured": false
}
```

**Request (FormData with New Image):**

```http
PUT /api/v1/workouts/1
Authorization: Bearer {admin_token}
Content-Type: multipart/form-data

name: "Updated Morning Cardio"
description: "Updated description"
difficulty: "hard"
duration_minutes: "45"
calories_per_session: "400"
is_featured: "false"
image: [NEW_FILE_UPLOAD]
```

**Response:**

```json
{
  "status": "success",
  "message": "Workout updated successfully",
  "data": {
    "id": 1,
    "name": "Updated Morning Cardio",
    "description": "Updated description",
    "image_url": "https://example.com/images/cardio.jpg",
    "difficulty": "hard",
    "type": "cardio",
    "duration_minutes": 45,
    "calories_per_session": 400,
    "equipment_needed": ["dumbbells", "mat"],
    "tags": ["cardio", "morning", "high-intensity"],
    "is_featured": false,
    "is_active": true,
    "created_by_admin": 1,
    "created_at": "2025-01-15T12:00:00.000000Z",
    "updated_at": "2025-01-15T14:30:00.000000Z"
  }
}
```

#### 4.1.3 Delete Workout

**Endpoint:** `DELETE /workouts/{workout}`

**Description:** Delete a workout.

**Request:**

```http
DELETE /api/v1/workouts/1
Authorization: Bearer {admin_token}
```

**Response:**

```json
{
  "status": "success",
  "message": "Workout deleted successfully"
}
```

### 4.2 RECIPE MANAGEMENT

#### 4.2.1 Create Recipe

**Endpoint:** `POST /recipes`

**Description:** Create a new recipe. Supports both JSON and FormData with image upload.

**Request (JSON):**

```http
POST /api/v1/recipes
Authorization: Bearer {admin_token}
Content-Type: application/json

{
  "name": "Healthy Quinoa Bowl",
  "description": "Nutritious quinoa bowl with vegetables",
  "image_url": "https://example.com/images/quinoa.jpg",
  "meal_type": "lunch",
  "prep_time_minutes": 15,
  "cook_time_minutes": 20,
  "servings": 2,
  "calories_per_serving": 350,
  "protein_per_serving": 12.5,
  "carbs_per_serving": 45.2,
  "fat_per_serving": 8.1,
  "fiber_per_serving": 6.3,
  "sugar_per_serving": 4.2,
  "ingredients": "1 cup quinoa, 2 cups water, 1 bell pepper, 1 cucumber, 1 avocado",
  "instructions": "1. Cook quinoa according to package instructions\n2. Chop vegetables\n3. Mix everything together\n4. Serve immediately",
  "dietary_tags": ["vegetarian", "gluten-free", "high-protein"],
  "allergen_info": ["nuts"],
  "difficulty": "easy",
  "is_featured": true,
  "is_active": true,
  "created_by_admin": 1
}
```

**Request (FormData with Image Upload):**

```http
POST /api/v1/recipes
Authorization: Bearer {admin_token}
Content-Type: multipart/form-data

name: "Healthy Quinoa Bowl"
description: "Nutritious quinoa bowl with vegetables"
meal_type: "lunch"
prep_time_minutes: "15"
cook_time_minutes: "20"
servings: "2"
calories_per_serving: "350"
protein_per_serving: "12.5"
carbs_per_serving: "45.2"
fat_per_serving: "8.1"
fiber_per_serving: "6.3"
sugar_per_serving: "4.2"
ingredients: "1 cup quinoa, 2 cups water, 1 bell pepper, 1 cucumber, 1 avocado"
instructions: "1. Cook quinoa according to package instructions\n2. Chop vegetables\n3. Mix everything together\n4. Serve immediately"
dietary_tags[]: "vegetarian"
dietary_tags[]: "gluten-free"
dietary_tags[]: "high-protein"
allergen_info[]: "nuts"
difficulty: "easy"
is_featured: "true"
is_active: "true"
created_by_admin: "1"
image: [FILE_UPLOAD]
```

**Response:**

```json
{
  "status": "success",
  "message": "Recipe created successfully",
  "data": {
    "id": 1,
    "name": "Healthy Quinoa Bowl",
    "description": "Nutritious quinoa bowl with vegetables",
    "image_url": "https://example.com/images/quinoa.jpg",
    "meal_type": "lunch",
    "prep_time_minutes": 15,
    "cook_time_minutes": 20,
    "servings": 2,
    "calories_per_serving": 350,
    "protein_per_serving": 12.5,
    "carbs_per_serving": 45.2,
    "fat_per_serving": 8.1,
    "fiber_per_serving": 6.3,
    "sugar_per_serving": 4.2,
    "ingredients": "1 cup quinoa, 2 cups water, 1 bell pepper, 1 cucumber, 1 avocado",
    "instructions": "1. Cook quinoa according to package instructions\n2. Chop vegetables\n3. Mix everything together\n4. Serve immediately",
    "dietary_tags": ["vegetarian", "gluten-free", "high-protein"],
    "allergen_info": ["nuts"],
    "difficulty": "easy",
    "is_featured": true,
    "is_active": true,
    "created_by_admin": 1,
    "created_at": "2025-01-15T12:00:00.000000Z",
    "updated_at": "2025-01-15T12:00:00.000000Z"
  }
}
```

#### 4.2.2 Update Recipe

**Endpoint:** `PUT /recipes/{recipe}`

**Description:** Update an existing recipe.

**Request:**

```http
PUT /api/v1/recipes/1
Authorization: Bearer {admin_token}
Content-Type: application/json

{
  "name": "Updated Quinoa Bowl",
  "calories_per_serving": 380,
  "protein_per_serving": 15.0,
  "is_featured": false
}
```

**Response:**

```json
{
  "status": "success",
  "message": "Recipe updated successfully",
  "data": {
    "id": 1,
    "name": "Updated Quinoa Bowl",
    "description": "Nutritious quinoa bowl with vegetables",
    "image_url": "https://example.com/images/quinoa.jpg",
    "meal_type": "lunch",
    "prep_time_minutes": 15,
    "cook_time_minutes": 20,
    "servings": 2,
    "calories_per_serving": 380,
    "protein_per_serving": 15.0,
    "carbs_per_serving": 45.2,
    "fat_per_serving": 8.1,
    "fiber_per_serving": 6.3,
    "sugar_per_serving": 4.2,
    "ingredients": "1 cup quinoa, 2 cups water, 1 bell pepper, 1 cucumber, 1 avocado",
    "instructions": "1. Cook quinoa according to package instructions\n2. Chop vegetables\n3. Mix everything together\n4. Serve immediately",
    "dietary_tags": ["vegetarian", "gluten-free", "high-protein"],
    "allergen_info": ["nuts"],
    "difficulty": "easy",
    "is_featured": false,
    "is_active": true,
    "created_by_admin": 1,
    "created_at": "2025-01-15T12:00:00.000000Z",
    "updated_at": "2025-01-15T14:30:00.000000Z"
  }
}
```

#### 4.2.3 Delete Recipe

**Endpoint:** `DELETE /recipes/{recipe}`

**Description:** Delete a recipe.

**Request:**

```http
DELETE /api/v1/recipes/1
Authorization: Bearer {admin_token}
```

**Response:**

```json
{
  "status": "success",
  "message": "Recipe deleted successfully"
}
```

### 4.3 MEAL PLAN MANAGEMENT

#### 4.3.1 Create Meal Plan

**Endpoint:** `POST /meal-plans`

**Description:** Create a new meal plan.

**Request:**

```http
POST /api/v1/meal-plans
Authorization: Bearer {admin_token}
Content-Type: application/json

{
  "name": "Weight Loss Meal Plan",
  "description": "7-day meal plan for weight loss",
  "image_url": "https://example.com/images/meal-plan.jpg",
  "duration_days": 7,
  "calories_per_day": 1500,
  "target_audience": "weight_loss",
  "dietary_preferences": ["low-carb", "high-protein"],
  "difficulty": "medium",
  "is_featured": true,
  "is_active": true
}
```

**Response:**

```json
{
  "status": "success",
  "message": "Meal plan created successfully",
  "data": {
    "id": 1,
    "name": "Weight Loss Meal Plan",
    "description": "7-day meal plan for weight loss",
    "image_url": "https://example.com/images/meal-plan.jpg",
    "duration_days": 7,
    "calories_per_day": 1500,
    "target_audience": "weight_loss",
    "dietary_preferences": ["low-carb", "high-protein"],
    "difficulty": "medium",
    "is_featured": true,
    "is_active": true,
    "created_by_admin": 1,
    "created_at": "2025-01-15T12:00:00.000000Z",
    "updated_at": "2025-01-15T12:00:00.000000Z"
  }
}
```

#### 4.3.2 Update Meal Plan

**Endpoint:** `PUT /meal-plans/{meal_plan}`

**Description:** Update an existing meal plan.

**Request:**

```http
PUT /api/v1/meal-plans/1
Authorization: Bearer {admin_token}
Content-Type: application/json

{
  "name": "Updated Weight Loss Plan",
  "calories_per_day": 1400,
  "difficulty": "easy"
}
```

**Response:**

```json
{
  "status": "success",
  "message": "Meal plan updated successfully",
  "data": {
    "id": 1,
    "name": "Updated Weight Loss Plan",
    "description": "7-day meal plan for weight loss",
    "image_url": "https://example.com/images/meal-plan.jpg",
    "duration_days": 7,
    "calories_per_day": 1400,
    "target_audience": "weight_loss",
    "dietary_preferences": ["low-carb", "high-protein"],
    "difficulty": "easy",
    "is_featured": true,
    "is_active": true,
    "created_by_admin": 1,
    "created_at": "2025-01-15T12:00:00.000000Z",
    "updated_at": "2025-01-15T14:30:00.000000Z"
  }
}
```

#### 4.3.3 Delete Meal Plan

**Endpoint:** `DELETE /meal-plans/{meal_plan}`

**Description:** Delete a meal plan.

**Request:**

```http
DELETE /api/v1/meal-plans/1
Authorization: Bearer {admin_token}
```

**Response:**

```json
{
  "status": "success",
  "message": "Meal plan deleted successfully"
}
```

### 4.4 EDUCATION CONTENT MANAGEMENT

#### 4.4.1 Create Education Content

**Endpoint:** `POST /education-contents`

**Description:** Create new educational content.

**Request:**

```http
POST /api/v1/education-contents
Authorization: Bearer {admin_token}
Content-Type: application/json

{
  "title": "Understanding Macronutrients",
  "content": "Macronutrients are the nutrients that provide calories or energy...",
  "type": "article",
  "category": "nutrition",
  "difficulty": "beginner",
  "estimated_read_time": 5,
  "tags": ["nutrition", "macronutrients", "beginner"],
  "is_featured": true,
  "is_active": true
}
```

**Response:**

```json
{
  "status": "success",
  "message": "Education content created successfully",
  "data": {
    "id": 1,
    "title": "Understanding Macronutrients",
    "content": "Macronutrients are the nutrients that provide calories or energy...",
    "type": "article",
    "category": "nutrition",
    "difficulty": "beginner",
    "estimated_read_time": 5,
    "tags": ["nutrition", "macronutrients", "beginner"],
    "is_featured": true,
    "is_active": true,
    "created_by_admin": 1,
    "created_at": "2025-01-15T12:00:00.000000Z",
    "updated_at": "2025-01-15T12:00:00.000000Z"
  }
}
```

#### 4.4.2 Update Education Content

**Endpoint:** `PUT /education-contents/{education_content}`

**Description:** Update existing education content.

**Request:**

```http
PUT /api/v1/education-contents/1
Authorization: Bearer {admin_token}
Content-Type: application/json

{
  "title": "Updated Macronutrients Guide",
  "difficulty": "intermediate",
  "estimated_read_time": 8
}
```

**Response:**

```json
{
  "status": "success",
  "message": "Education content updated successfully",
  "data": {
    "id": 1,
    "title": "Updated Macronutrients Guide",
    "content": "Macronutrients are the nutrients that provide calories or energy...",
    "type": "article",
    "category": "nutrition",
    "difficulty": "intermediate",
    "estimated_read_time": 8,
    "tags": ["nutrition", "macronutrients", "beginner"],
    "is_featured": true,
    "is_active": true,
    "created_by_admin": 1,
    "created_at": "2025-01-15T12:00:00.000000Z",
    "updated_at": "2025-01-15T14:30:00.000000Z"
  }
}
```

#### 4.4.3 Delete Education Content

**Endpoint:** `DELETE /education-contents/{education_content}`

**Description:** Delete education content.

**Request:**

```http
DELETE /api/v1/education-contents/1
Authorization: Bearer {admin_token}
```

**Response:**

```json
{
  "status": "success",
  "message": "Education content deleted successfully"
}
```

---

## 5. IMAGE MANAGEMENT

### 5.1 Upload Image

**Endpoint:** `POST /images`

**Description:** Upload a new image file for content or standalone use.

**Request:**

```http
POST /api/v1/images
Authorization: Bearer {admin_token}
Content-Type: multipart/form-data

title: "Workout Image"
description: "Image for workout display"
category: "workout"
image: [FILE_UPLOAD]
tags[]: "fitness"
tags[]: "exercise"
```

**Response:**

```json
{
  "success": true,
  "message": "Image uploaded successfully",
  "data": {
    "id": 1,
    "title": "Workout Image",
    "description": "Image for workout display",
    "filename": "original_image.jpg",
    "path": "uuid-generated-filename.jpg",
    "url": "http://127.0.0.1:8000/storage/images/uuid-generated-filename.jpg",
    "mime_type": "image/jpeg",
    "file_size": 1024000,
    "width": 1920,
    "height": 1080,
    "category": "workout",
    "tags": ["fitness", "exercise"],
    "is_active": true,
    "uploaded_by": 1,
    "created_at": "2025-01-15T12:00:00.000000Z",
    "updated_at": "2025-01-15T12:00:00.000000Z"
  }
}
```

### 5.2 Update Image

**Endpoint:** `PUT /images/{image}`

**Description:** Update image metadata.

**Request:**

```http
PUT /api/v1/images/1
Authorization: Bearer {admin_token}
Content-Type: application/json

{
  "title": "Updated Workout Image",
  "description": "Updated description",
  "category": "recipe",
  "tags": ["fitness", "exercise", "nutrition"]
}
```

**Response:**

```json
{
  "success": true,
  "message": "Image updated successfully",
  "data": {
    "id": 1,
    "title": "Updated Workout Image",
    "description": "Updated description",
    "filename": "original_image.jpg",
    "path": "uuid-generated-filename.jpg",
    "url": "http://127.0.0.1:8000/storage/images/uuid-generated-filename.jpg",
    "mime_type": "image/jpeg",
    "file_size": 1024000,
    "width": 1920,
    "height": 1080,
    "category": "recipe",
    "tags": ["fitness", "exercise", "nutrition"],
    "is_active": true,
    "uploaded_by": 1,
    "created_at": "2025-01-15T12:00:00.000000Z",
    "updated_at": "2025-01-15T14:30:00.000000Z"
  }
}
```

### 5.3 Delete Image

**Endpoint:** `DELETE /images/{image}`

**Description:** Delete an image and its file.

**Request:**

```http
DELETE /api/v1/images/1
Authorization: Bearer {admin_token}
```

**Response:**

```json
{
  "success": true,
  "message": "Image deleted successfully"
}
```

### 5.4 List Images

**Endpoint:** `GET /images`

**Description:** Get all images with filtering options.

**Request:**

```http
GET /api/v1/images?category=workout&search=fitness&per_page=20
Authorization: Bearer {admin_token}
```

**Query Parameters:**

- `category` (optional): Filter by category (workout, recipe, meal_plan, education, profile, other)
- `search` (optional): Search by title or description
- `per_page` (optional): Items per page (default: 15)

**Response:**

```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "Workout Image",
      "description": "Image for workout display",
      "url": "http://127.0.0.1:8000/storage/images/uuid-generated-filename.jpg",
      "category": "workout",
      "tags": ["fitness", "exercise"],
      "width": 1920,
      "height": 1080,
      "file_size": 1024000,
      "created_at": "2025-01-15T12:00:00.000000Z"
    }
  ],
  "pagination": {
    "current_page": 1,
    "per_page": 15,
    "total": 25,
    "last_page": 2
  }
}
```

---

## 6. VIDEO MANAGEMENT

### 6.1 Upload Video

**Endpoint:** `POST /videos`

**Description:** Upload a new video file for exercises or educational content.

**Request:**

```http
POST /api/v1/videos
Authorization: Bearer {admin_token}
Content-Type: multipart/form-data

title: "Push-up Tutorial"
description: "Learn proper push-up form"
category: "exercise"
video: [FILE_UPLOAD]
tags[]: "strength"
tags[]: "upper-body"
```

**Response:**

```json
{
  "status": "success",
  "message": "Video uploaded successfully",
  "data": {
    "id": 1,
    "title": "Push-up Tutorial",
    "description": "Learn proper push-up form",
    "category": "exercise",
    "file_path": "videos/pushup_tutorial.mp4",
    "file_size": 15728640,
    "duration": 120,
    "tags": ["strength", "upper-body"],
    "is_active": true,
    "created_by_admin": 1,
    "created_at": "2025-01-15T12:00:00.000000Z",
    "updated_at": "2025-01-15T12:00:00.000000Z"
  }
}
```

### 6.2 Update Video

**Endpoint:** `PUT /videos/{video}`

**Description:** Update video metadata.

**Request:**

```http
PUT /api/v1/videos/1
Authorization: Bearer {admin_token}
Content-Type: application/json

{
  "title": "Updated Push-up Tutorial",
  "description": "Updated description with better form tips",
  "tags": ["strength", "upper-body", "beginner"]
}
```

**Response:**

```json
{
  "status": "success",
  "message": "Video updated successfully",
  "data": {
    "id": 1,
    "title": "Updated Push-up Tutorial",
    "description": "Updated description with better form tips",
    "category": "exercise",
    "file_path": "videos/pushup_tutorial.mp4",
    "file_size": 15728640,
    "duration": 120,
    "tags": ["strength", "upper-body", "beginner"],
    "is_active": true,
    "created_by_admin": 1,
    "created_at": "2025-01-15T12:00:00.000000Z",
    "updated_at": "2025-01-15T14:30:00.000000Z"
  }
}
```

### 6.3 Delete Video

**Endpoint:** `DELETE /videos/{video}`

**Description:** Delete a video and its file.

**Request:**

```http
DELETE /api/v1/videos/1
Authorization: Bearer {admin_token}
```

**Response:**

```json
{
  "status": "success",
  "message": "Video deleted successfully"
}
```

---

## 7. ANALYTICS & DASHBOARD STATISTICS

### 7.1 Dashboard Overview Statistics

**Endpoint:** `GET /admin/dashboard/stats`

**Description:** Get comprehensive dashboard statistics for admin overview.

**Request:**

```http
GET /api/v1/admin/dashboard/stats?period=month
Authorization: Bearer {admin_token}
```

**Query Parameters:**

- `period` (optional): Time period (day, week, month, year) - default: month

**Response:**

```json
{
  "status": "success",
  "data": {
    "users": {
      "total_users": 1250,
      "new_users_this_period": 45,
      "active_users_this_period": 890,
      "user_growth_percentage": 12.5
    },
    "content": {
      "total_workouts": 85,
      "total_recipes": 120,
      "total_meal_plans": 25,
      "total_education_content": 60,
      "total_videos": 45
    },
    "engagement": {
      "total_workouts_completed": 2150,
      "total_calories_burned": 125000,
      "average_workout_rating": 4.3,
      "total_ai_chats": 890,
      "total_search_queries": 3200
    },
    "recent_activity": [
      {
        "type": "user_registration",
        "description": "New user registered: john@example.com",
        "timestamp": "2025-01-15T10:30:00.000000Z"
      },
      {
        "type": "workout_completed",
        "description": "User completed 'Morning Cardio' workout",
        "timestamp": "2025-01-15T09:15:00.000000Z"
      }
    ],
    "period": "month"
  }
}
```

### 7.2 User Analytics

**Endpoint:** `GET /admin/analytics/users`

**Description:** Get detailed user analytics and insights.

**Request:**

```http
GET /api/v1/admin/analytics/users?period=month&group_by=day
Authorization: Bearer {admin_token}
```

**Query Parameters:**

- `period` (optional): Time period (day, week, month, year)
- `group_by` (optional): Group data by (day, week, month)

**Response:**

```json
{
  "status": "success",
  "data": {
    "user_registrations": [
      {
        "date": "2025-01-01",
        "count": 5
      },
      {
        "date": "2025-01-02",
        "count": 8
      }
    ],
    "user_activity": [
      {
        "date": "2025-01-01",
        "active_users": 45,
        "new_users": 5,
        "returning_users": 40
      }
    ],
    "user_demographics": {
      "age_groups": {
        "18-25": 300,
        "26-35": 450,
        "36-45": 350,
        "46+": 150
      },
      "gender_distribution": {
        "male": 600,
        "female": 650
      },
      "goals_distribution": {
        "weight_loss": 400,
        "muscle_gain": 350,
        "general_fitness": 500
      }
    },
    "retention_metrics": {
      "day_1_retention": 85.5,
      "day_7_retention": 65.2,
      "day_30_retention": 45.8
    }
  }
}
```

### 7.3 Content Analytics

**Endpoint:** `GET /admin/analytics/content`

**Description:** Get analytics for content performance and engagement.

**Request:**

```http
GET /api/v1/admin/analytics/content?content_type=workouts&period=month
Authorization: Bearer {admin_token}
```

**Query Parameters:**

- `content_type` (optional): Type of content (workouts, recipes, meal_plans, education_content)
- `period` (optional): Time period (day, week, month, year)

**Response:**

```json
{
  "status": "success",
  "data": {
    "content_performance": [
      {
        "id": 1,
        "name": "Morning Cardio Blast",
        "type": "workout",
        "total_completions": 150,
        "average_rating": 4.5,
        "total_calories_burned": 45000,
        "completion_rate": 85.2
      },
      {
        "id": 2,
        "name": "Healthy Quinoa Bowl",
        "type": "recipe",
        "total_views": 320,
        "total_favorites": 45,
        "average_rating": 4.2
      }
    ],
    "popular_categories": {
      "workouts": {
        "cardio": 45,
        "strength": 35,
        "yoga": 20
      },
      "recipes": {
        "breakfast": 30,
        "lunch": 40,
        "dinner": 25,
        "snack": 5
      }
    },
    "engagement_metrics": {
      "average_session_duration": 25.5,
      "content_interaction_rate": 78.3,
      "user_satisfaction_score": 4.2
    }
  }
}
```

### 7.4 System Analytics

**Endpoint:** `GET /admin/analytics/system`

**Description:** Get system performance and technical analytics.

**Request:**

```http
GET /api/v1/admin/analytics/system?period=week
Authorization: Bearer {admin_token}
```

**Response:**

```json
{
  "status": "success",
  "data": {
    "api_performance": {
      "average_response_time": 245,
      "total_requests": 12500,
      "error_rate": 0.8,
      "success_rate": 99.2
    },
    "storage_usage": {
      "total_storage": "2.5GB",
      "video_storage": "1.8GB",
      "image_storage": "0.5GB",
      "database_size": "0.2GB"
    },
    "ai_chat_usage": {
      "total_conversations": 890,
      "average_messages_per_chat": 4.2,
      "most_common_topics": ["nutrition", "exercise", "weight_loss"]
    },
    "search_analytics": {
      "total_searches": 3200,
      "most_searched_terms": ["cardio", "protein", "weight loss"],
      "search_success_rate": 92.5
    }
  }
}
```

---

## 8. ERROR HANDLING

### Common Error Responses

#### 401 Unauthorized

```json
{
  "message": "Unauthenticated."
}
```

#### 403 Forbidden (Admin Access Required)

```json
{
  "error": "Admin access required"
}
```

#### 422 Validation Error

```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": ["The email field is required."],
    "password": ["The password must be at least 8 characters."]
  }
}
```

#### 404 Not Found

```json
{
  "status": "error",
  "message": "Resource not found"
}
```

#### 500 Internal Server Error

```json
{
  "status": "error",
  "message": "Internal server error",
  "error": "Detailed error message"
}
```

---

## 9. IMPLEMENTATION NOTES

### Frontend Integration Tips

1. **Authentication Flow:**
   - Store admin token securely (localStorage/sessionStorage)
   - Implement token refresh logic
   - Handle 401/403 errors by redirecting to login

2. **Data Management:**
   - Implement proper loading states
   - Use pagination for large datasets
   - Cache frequently accessed data

3. **Real-time Updates:**
   - Consider implementing WebSocket connections for real-time dashboard updates
   - Use polling for analytics data refresh

4. **File Uploads:**
   - Implement progress indicators for video uploads
   - Validate file types and sizes on frontend
   - Handle upload errors gracefully

### Security Considerations

1. **Admin Access Control:**
   - All admin endpoints verify AdminUser model
   - Role-based permissions system in place
   - Token-based authentication with Sanctum

2. **Data Validation:**
   - Server-side validation for all inputs
   - File upload restrictions (size, type)
   - SQL injection protection via Eloquent ORM

3. **Rate Limiting:**
   - Implement rate limiting for admin endpoints
   - Protect against brute force attacks
   - Monitor for suspicious activity

---

## 10. TESTING ENDPOINTS

### Using cURL Examples

#### Admin Login

```bash
curl -X POST http://127.0.0.1:8000/api/v1/admin-login \
  -H "Content-Type: application/json" \
  -d '{"email": "admin@example.com", "password": "password123"}'
```

#### Get Dashboard Stats

```bash
curl -X GET http://127.0.0.1:8000/api/v1/admin/dashboard/stats \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN"
```

#### Create Workout with Image Upload

```bash
curl -X POST http://127.0.0.1:8000/api/v1/workouts \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN" \
  -F "name=Test Workout" \
  -F "description=Test description" \
  -F "difficulty=medium" \
  -F "type=cardio" \
  -F "duration_minutes=30" \
  -F "calories_per_session=200" \
  -F "equipment_needed[]=dumbbells" \
  -F "tags[]=test" \
  -F "is_active=true" \
  -F "created_by_admin=1" \
  -F "image=@/path/to/image.jpg"
```

#### Upload Video

```bash
curl -X POST http://127.0.0.1:8000/api/v1/videos \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN" \
  -F "title=Test Video" \
  -F "description=Test video description" \
  -F "category=exercise" \
  -F "video=@/path/to/video.mp4"
```

#### Upload Image

```bash
curl -X POST http://127.0.0.1:8000/api/v1/images \
  -H "Authorization: Bearer YOUR_ADMIN_TOKEN" \
  -F "title=Test Image" \
  -F "description=Test image description" \
  -F "category=workout" \
  -F "image=@/path/to/image.jpg"
```

---

This documentation provides comprehensive coverage of all admin dashboard endpoints. Each endpoint includes detailed request/response examples, error handling, and implementation guidance to ensure smooth frontend integration.
