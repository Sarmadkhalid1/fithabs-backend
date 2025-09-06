# Meal Type Filtering for Personalized Meal Plans

## Overview

The `/api/v1/meal-plans/personalized` endpoint now supports optional meal type filtering. This allows users to get personalized meal plans filtered by specific meal types (breakfast, lunch, dinner, snack) or all meal types if none is specified.

## Feature Details

### Query Parameter

- **Parameter**: `meal_type`
- **Type**: Optional query parameter
- **Valid Values**: `breakfast`, `lunch`, `dinner`, `snack`
- **Default**: If not provided, returns all meal plans

### Response Enhancement

The response now includes a `meal_type_filter` field that indicates which filter was applied:

- If `meal_type` is provided: shows the specific meal type
- If no `meal_type` is provided: shows `"all"`

## API Usage Examples

### 1. Get All Personalized Meal Plans (No Filter)

```bash
curl -X GET "http://localhost:8000/api/v1/meal-plans/personalized" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json"
```

**Response:**

```json
{
  "status": "success",
  "data": [...],
  "count": 3,
  "meal_type_filter": "all"
}
```

### 2. Filter by Breakfast

```bash
curl -X GET "http://localhost:8000/api/v1/meal-plans/personalized?meal_type=breakfast" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json"
```

**Response:**

```json
{
  "status": "success",
  "data": [...],
  "count": 1,
  "meal_type_filter": "breakfast"
}
```

### 3. Filter by Lunch

```bash
curl -X GET "http://localhost:8000/api/v1/meal-plans/personalized?meal_type=lunch" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json"
```

### 4. Filter by Dinner

```bash
curl -X GET "http://localhost:8000/api/v1/meal-plans/personalized?meal_type=dinner" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json"
```

### 5. Filter by Snack

```bash
curl -X GET "http://localhost:8000/api/v1/meal-plans/personalized?meal_type=snack" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json"
```

## Error Handling

### Invalid Meal Type

If an invalid meal type is provided, the API returns a validation error:

```bash
curl -X GET "http://localhost:8000/api/v1/meal-plans/personalized?meal_type=invalid" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json"
```

**Response:**

```json
{
  "message": "The given data was invalid.",
  "errors": {
    "meal_type": ["The selected meal type is invalid."]
  }
}
```

## Implementation Details

### Controller Changes

The `MealPlanController::personalized()` method was enhanced to:

1. **Accept Request Parameter**: Changed method signature to accept `Request $request`
2. **Validate Meal Type**: Added validation for the `meal_type` query parameter
3. **Apply Filter**: Added `whereHas` query to filter meal plans by meal type
4. **Enhanced Response**: Added `meal_type_filter` field to response

### Filtering Logic

```php
// Filter by meal type if provided
if ($mealType) {
    $query->whereHas('mealPlanRecipes', function($q) use ($mealType) {
        $q->where('meal_type', $mealType);
    });
}
```

### User Preferences Integration

The meal type filtering works alongside existing user preferences:

- Dietary preferences are still applied
- Caloric goals are still considered
- Allergy filtering is still available (when enabled)
- The meal type filter is applied in addition to these preferences

## Testing

### Test Script

A comprehensive test script `test_meal_type_filtering.sh` is available to verify the functionality:

```bash
./test_meal_type_filtering.sh
```

### Test Cases Covered

1. ✅ All meal plans (no filter)
2. ✅ Breakfast filtering
3. ✅ Lunch filtering
4. ✅ Dinner filtering
5. ✅ Snack filtering
6. ✅ Invalid meal type validation
7. ✅ Detailed response verification

## Mobile App Integration

### URL Examples for Mobile App

```javascript
// Get all personalized meal plans
const allMealPlans = await fetch('/api/v1/meal-plans/personalized', {
  headers: { Authorization: `Bearer ${token}` },
});

// Get breakfast meal plans
const breakfastMealPlans = await fetch('/api/v1/meal-plans/personalized?meal_type=breakfast', {
  headers: { Authorization: `Bearer ${token}` },
});

// Get lunch meal plans
const lunchMealPlans = await fetch('/api/v1/meal-plans/personalized?meal_type=lunch', {
  headers: { Authorization: `Bearer ${token}` },
});
```

### Response Handling

```javascript
const response = await fetch('/api/v1/meal-plans/personalized?meal_type=breakfast', {
  headers: { Authorization: `Bearer ${token}` },
});

const data = await response.json();
console.log(`Found ${data.count} meal plans for ${data.meal_type_filter}`);
```

## Benefits

1. **Enhanced User Experience**: Users can quickly find meal plans for specific meal types
2. **Better Performance**: Reduced data transfer by filtering at the database level
3. **Flexible API**: Optional parameter maintains backward compatibility
4. **Clear Feedback**: `meal_type_filter` field provides transparency about applied filters
5. **Validation**: Proper error handling for invalid meal types

## Backward Compatibility

This enhancement is fully backward compatible:

- Existing API calls without the `meal_type` parameter continue to work
- No changes required for existing mobile app implementations
- All existing functionality is preserved
