# Profile Update Endpoint - Fixed for DOB and Phone Fields

## ✅ Problem Solved!

The `/profile` endpoint was returning "The given data is invalid" because it didn't include validation rules for the new `dob` and `phone` fields, and had overly strict validation for `weight_unit` and `height_unit`.

## 🔧 What Was Fixed

### 1. **Main Profile Endpoint** (`PUT /api/v1/profile`)

**Before (Validation Rules):**

```php
$validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
    'name' => 'sometimes|string|max:255',
    'email' => 'sometimes|string|email|max:255|unique:users,email,'.$user->id,
    'password' => 'sometimes|string|min:8',
    'age' => 'sometimes|integer|min:13|max:120',
    'gender' => 'nullable|in:male,female,other',
    'weight' => 'nullable|numeric|min:0',
    'weight_unit' => 'required_with:weight|in:kg,lb',        // ❌ Too strict
    'height' => 'nullable|numeric|min:0',
    'height_unit' => 'required_with:height|in:cm,ft',        // ❌ Too strict
    'goal' => 'nullable|in:lose_weight,gain_weight,maintain_weight,build_muscle',
    'activity_level' => 'nullable|in:sedentary,light,moderate,very_active',
    'daily_calorie_goal' => 'nullable|integer|min:0',
    'daily_steps_goal' => 'nullable|integer|min:0',
    'daily_water_goal' => 'nullable|numeric|min:0',
    // ❌ Missing dob and phone validation
]);
```

**After (Updated Validation Rules):**

```php
$validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
    'name' => 'sometimes|string|max:255',
    'email' => 'sometimes|string|email|max:255|unique:users,email,'.$user->id,
    'password' => 'sometimes|string|min:8',
    'age' => 'sometimes|integer|min:13|max:120',
    'gender' => 'nullable|in:male,female,other',
    'weight' => 'nullable|numeric|min:0',
    'weight_unit' => 'nullable|in:kg,lb',                    // ✅ Made nullable
    'height' => 'nullable|numeric|min:0',
    'height_unit' => 'nullable|in:cm,ft',                    // ✅ Made nullable
    'goal' => 'nullable|in:lose_weight,gain_weight,maintain_weight,build_muscle',
    'activity_level' => 'nullable|in:sedentary,light,moderate,very_active',
    'daily_calorie_goal' => 'nullable|integer|min:0',
    'daily_steps_goal' => 'nullable|integer|min:0',
    'daily_water_goal' => 'nullable|numeric|min:0',
    'dob' => 'nullable|date|before:today',                   // ✅ NEW
    'phone' => 'nullable|string|max:20',                     // ✅ NEW
]);
```

### 2. **Data Processing Updated**

**Before:**

```php
$data = $request->only([
    'name', 'email', 'age', 'gender', 'weight', 'weight_unit', 'height',
    'height_unit', 'goal', 'activity_level', 'daily_calorie_goal',
    'daily_steps_goal', 'daily_water_goal'
    // ❌ Missing dob and phone
]);
```

**After:**

```php
$data = $request->only([
    'name', 'email', 'age', 'gender', 'weight', 'weight_unit', 'height',
    'height_unit', 'goal', 'activity_level', 'daily_calorie_goal',
    'daily_steps_goal', 'daily_water_goal', 'dob', 'phone'    // ✅ Added
]);
```

### 3. **Basic Profile Endpoint** (`PUT /api/v1/profile/basic`)

**Updated to include new fields:**

```php
$validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
    'name' => 'sometimes|string|max:255',
    'email' => 'sometimes|string|email|max:255|unique:users,email,'.$user->id,
    'age' => 'sometimes|integer|min:13|max:120',
    'dob' => 'nullable|date|before:today',                   // ✅ NEW
    'phone' => 'nullable|string|max:20',                     // ✅ NEW
]);

$data = $request->only(['name', 'email', 'age', 'dob', 'phone']); // ✅ Added
```

### 4. **Physical Profile Endpoint** (`PUT /api/v1/profile/physical`)

**Made weight_unit and height_unit nullable:**

```php
$validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
    'gender' => 'nullable|in:male,female,other',
    'weight' => 'nullable|numeric|min:0',
    'weight_unit' => 'nullable|in:kg,lb',                    // ✅ Made nullable
    'height' => 'nullable|numeric|min:0',
    'height_unit' => 'nullable|in:cm,ft',                    // ✅ Made nullable
]);
```

## 🎯 Your Data Now Works!

**Your Request Data:**

```json
{
  "name": "Sarmad Khalid",
  "email": "sarmad@gmail.com",
  "phone": "+923015933519",
  "dob": "1998-11-26",
  "weight": 64,
  "height": 167
}
```

**Validation Rules Applied:**

- ✅ `name`: Valid string, max 255 chars
- ✅ `email`: Valid email format
- ✅ `phone`: Valid string, max 20 chars
- ✅ `dob`: Valid date, before today
- ✅ `weight`: Valid numeric, min 0
- ✅ `height`: Valid numeric, min 0

## 📍 Endpoints Updated

1. **`PUT /api/v1/profile`** - Full profile update ✅
2. **`PUT /api/v1/profile/basic`** - Basic info update ✅
3. **`PUT /api/v1/profile/physical`** - Physical stats update ✅

## 🚀 Ready to Use!

Your profile update endpoint now accepts the new `dob` and `phone` fields, and the validation errors have been resolved. You can now successfully update user profiles with:

- Date of birth (DOB)
- Phone number
- Weight and height (without requiring units)
- All existing fields

The endpoint will no longer return "The given data is invalid" for your data structure! 🎉
