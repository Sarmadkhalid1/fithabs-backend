# User Table Update - Added DOB and Phone Fields

## Overview

Successfully added two new fields to the users table:

- `dob` (date of birth) - Date field
- `phone` (phone number) - String field (20 characters)

## Migration Details

**Migration File:** `2025_08_29_185643_add_dob_and_phone_to_users_table.php`

**Changes Made:**

```php
Schema::table('users', function (Blueprint $table) {
    // Add date of birth field
    $table->date('dob')->nullable()->after('age');

    // Add phone number field
    $table->string('phone', 20)->nullable()->after('dob');
});
```

## Updated User Model

**New Fillable Fields:**

```php
protected $fillable = [
    'name',
    'email',
    'password',
    'age',
    'gender',
    'weight',
    'weight_unit',
    'height',
    'height_unit',
    'goal',
    'activity_level',
    'daily_calorie_goal',
    'daily_steps_goal',
    'daily_water_goal',
    'dob',        // ✅ NEW
    'phone',      // ✅ NEW
];
```

**New Casts:**

```php
protected $casts = [
    'email_verified_at' => 'datetime',
    'password' => 'hashed',
    'dob' => 'date',    // ✅ NEW
];
```

## Current User Table Structure

```
- id
- name
- email
- email_verified_at
- password
- remember_token
- created_at
- updated_at
- gender
- weight
- weight_unit
- height
- height_unit
- goal
- activity_level
- daily_calorie_goal
- daily_steps_goal
- daily_water_goal
- age
- dob          ✅ NEW
- phone        ✅ NEW
```

## Sample User Data

**Before Update:**

```json
{
  "id": 4,
  "name": "Sarmad Khalid",
  "email": "sarmad@gmail.com",
  "gender": "male",
  "weight": 64,
  "weight_unit": "kg",
  "height": 164,
  "height_unit": "cm",
  "goal": null,
  "activity_level": null,
  "daily_calorie_goal": 2000,
  "daily_steps_goal": 10000,
  "daily_water_goal": 2.5,
  "age": 30
}
```

**After Update:**

```json
{
    "id": 4,
    "name": "Sarmad Khalid",
    "email": "sarmad@gmail.com",
    "gender": "male",
    "weight": 64,
    "weight_unit": "kg",
    "height": 167,
    "height_unit": "cm",
    "goal": null,
    "activity_level": null,
    "daily_calorie_goal": 2000,
    "daily_steps_goal": 10000,
    "daily_water_goal": 2.5,
    "age": 30,
    "dob": "1995-01-15T00:00:00.000000Z",    ✅ NEW
    "phone": "+1234567890"                     ✅ NEW
}
```

## Field Specifications

### DOB Field

- **Type:** `date`
- **Nullable:** Yes
- **Position:** After `age` field
- **Format:** YYYY-MM-DD
- **Example:** "1995-01-15"

### Phone Field

- **Type:** `string(20)`
- **Nullable:** Yes
- **Position:** After `dob` field
- **Format:** Any string up to 20 characters
- **Example:** "+1234567890", "555-123-4567"

## Usage Examples

### Creating a User with New Fields

```php
User::create([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'password' => Hash::make('password'),
    'age' => 25,
    'dob' => '2000-05-20',
    'phone' => '+1555123456',
    // ... other fields
]);
```

### Updating User Fields

```php
$user->update([
    'dob' => '1990-12-25',
    'phone' => '+1234567890'
]);
```

### Querying by New Fields

```php
// Find users born in 1995
$users = User::whereYear('dob', 1995)->get();

// Find users with specific phone number
$user = User::where('phone', '+1234567890')->first();
```

## Migration Status

✅ **Migration Applied Successfully**  
✅ **User Model Updated**  
✅ **Fields Added to Database**  
✅ **Sample Data Updated**  
✅ **Ready for Use**

The users table now includes the new `dob` and `phone` fields and is ready for your application to use! 🎉
