# Education Content API - New Structure Guide

## Overview

The education content API has been updated to support a more flexible structure with multiple sections per article, exactly as you requested.

## New Database Structure

### Fields:

- `id` - Primary key
- `title` - Article title
- `description` - Brief description
- `cover_image` - Cover image URL (renamed from image_url)
- `sections` - JSON array of sections with heading and content
- `category` - training, nutrition, wellness, recovery, mental_health
- `tags` - JSON array of tags
- `is_featured` - Boolean
- `is_active` - Boolean
- `created_by_admin` - Foreign key to admin_users
- `created_at`, `updated_at` - Timestamps

## API Endpoints

### GET /api/v1/education-contents

Returns all active education content in the new format.

**Response Format:**

```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "coverImage": "https://images.unsplash.com/photo-1517836357463-d25dfeac3438?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80",
      "title": "The Complete Guide to Strength Training",
      "description": "Master the fundamentals of strength training with proper form, progressive overload, and effective workout programming.",
      "sections": [
        {
          "heading": "What is Strength Training?",
          "content": "Strength training is one of the most effective ways to build muscle, increase bone density, and improve overall health. It involves exercises that make your muscles work against resistance."
        },
        {
          "heading": "Benefits of Strength Training",
          "content": "Regular strength training provides numerous benefits including increased muscle mass and strength, improved bone density, enhanced metabolism, and better functional movement for daily activities."
        },
        {
          "heading": "Getting Started",
          "content": "Begin with bodyweight exercises or light weights, focusing on proper form. Start with 2-3 sessions per week and gradually increase intensity as you build strength and confidence."
        }
      ],
      "category": "training",
      "tags": ["strength training", "muscle building", "beginner"],
      "is_featured": true
    }
  ],
  "count": 3
}
```

### GET /api/v1/education-contents/{id}

Returns a single education content item in the same format.

### POST /api/v1/education-contents (Admin Only)

Create new education content with multiple sections.

**Request Format:**

```json
{
  "title": "Introduction to Artificial Intelligence",
  "description": "Learn how Artificial intelligence can help you with your health and fitness",
  "coverImage": "https://placekitten.com/800/400",
  "sections": [
    {
      "heading": "What is AI?",
      "content": "Artificial Intelligence (AI) is the simulation of human intelligence by machines."
    },
    {
      "heading": "Applications of AI",
      "content": "AI is used in healthcare, finance, education, autonomous driving, and robotics."
    },
    {
      "heading": "Challenges in AI",
      "content": "Bias, explainability, and ethical concerns are major challenges facing AI development."
    }
  ],
  "category": "wellness",
  "tags": ["AI", "technology", "health"],
  "is_featured": false
}
```

**Validation Rules:**

- `title`: Required, string, max 255 characters
- `description`: Required, string
- `cover_image`: Optional, valid URL
- `sections`: Required, array with at least 1 section
- `sections.*.heading`: Required, string, max 255 characters
- `sections.*.content`: Required, string
- `category`: Required, one of: training, nutrition, wellness, recovery, mental_health
- `tags`: Optional, array
- `is_featured`: Optional, boolean
- `is_active`: Optional, boolean (defaults to true)

## Current Sample Data

The system now contains 3 sample articles:

1. **"The Complete Guide to Strength Training"** (training)
   - 3 sections covering basics, benefits, and getting started
   - Featured article with gym weightlifting image

2. **"Yoga for Beginners: Mind, Body, and Soul"** (wellness)
   - 3 sections covering what yoga is, benefits, and essential poses
   - Featured article with yoga pose image

3. **"Cardio Training: Heart Health and Endurance"** (training)
   - 3 sections covering importance, types, and routine building
   - Regular article with treadmill running image

## Key Changes Made

✅ **Database Migration**: Added `sections` JSON column, renamed `image_url` to `cover_image`  
✅ **Model Updates**: Updated fillable fields and casts for new structure  
✅ **Controller Updates**: Updated validation and response formatting  
✅ **Seeder Updates**: New sample data with sections structure  
✅ **API Response**: Matches your exact requested format with `coverImage` and `sections`

## Benefits of New Structure

- **Flexible Content**: Add unlimited sections per article
- **Better Organization**: Each section has clear heading and content
- **Easy Management**: Simple JSON structure for frontend consumption
- **Scalable**: Easy to add more fields to sections if needed
- **Clean API**: Consistent response format across all endpoints

Your education content API now supports the exact structure you requested! 🎉
