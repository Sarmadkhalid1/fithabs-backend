# 🔐 **Getting Authentication Token for Testing**

## **Step 1: Create a Test User (if needed)**

First, you might need to create a test user. You can use the existing user registration endpoint:

```bash
curl --location 'http://localhost:8000/api/v1/register' \
--header 'Content-Type: application/json' \
--data '{
    "name": "Test User",
    "email": "test@fithabs.com",
    "password": "password123",
    "gender": "male",
    "weight": 70,
    "weight_unit": "kg",
    "height": 175,
    "height_unit": "cm",
    "goal": "lose_weight",
    "activity_level": "moderate"
}'
```

## **Step 2: Login to Get Token**

```bash
curl --location 'http://localhost:8000/api/v1/login' \
--header 'Content-Type: application/json' \
--data '{
    "email": "test@fithabs.com",
    "password": "password123"
}'
```

**Expected Response:**

```json
{
  "status": "success",
  "message": "Login successful",
  "data": {
    "user": {
      "id": 1,
      "name": "Test User",
      "email": "test@fithabs.com"
    },
    "token": "1|abc123def456ghi789..."
  }
}
```

## **Step 3: Use the Token**

Copy the `token` value from the login response and use it in your Postman requests:

```
Authorization: Bearer 1|abc123def456ghi789...
```

## **Quick Test Commands**

### **Test User Registration:**

```bash
curl --location 'http://localhost:8000/api/v1/register' \
--header 'Content-Type: application/json' \
--data '{
    "name": "Test User",
    "email": "test@fithabs.com",
    "password": "password123",
    "gender": "male",
    "weight": 70,
    "weight_unit": "kg",
    "height": 175,
    "height_unit": "cm",
    "goal": "lose_weight",
    "activity_level": "moderate"
}'
```

### **Test Login:**

```bash
curl --location 'http://localhost:8000/api/v1/login' \
--header 'Content-Type: application/json' \
--data '{
    "email": "test@fithabs.com",
    "password": "password123"
}'
```

### **Test API with Token:**

```bash
curl --location 'http://localhost:8000/api/v1/meal-plans' \
--header 'Authorization: Bearer YOUR_TOKEN_HERE' \
--header 'Content-Type: application/json'
```

## **Postman Environment Setup**

1. Create a new environment in Postman
2. Add variable `baseUrl` = `http://localhost:8000/api/v1`
3. Add variable `authToken` = `YOUR_TOKEN_HERE` (from login response)
4. Use `{{baseUrl}}` and `{{authToken}}` in your requests

## **Complete Test Flow**

1. **Register/Login** to get token
2. **Set User Preferences** using the token
3. **Test Personalized Meal Plans**
4. **Assign Meal Plan** to user
5. **Verify Current Meal Plan**

This will give you a complete end-to-end test of the meal plan personalization system!
