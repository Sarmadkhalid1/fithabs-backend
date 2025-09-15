# Education Content (Articles) API Documentation

## Overview

This guide provides complete documentation for creating, managing, and retrieving education content (articles) in the FitHabs application. The system supports structured articles with multiple sections, categories, and tags.

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

## 2. Create Education Content (Article)

### Endpoint

```
POST /education-contents
```

### Request Body

```json
{
  "title": "Complete Guide to Strength Training",
  "description": "A comprehensive guide covering all aspects of strength training for beginners and advanced athletes.",
  "cover_image": "https://example.com/cover-image.jpg",
  "sections": [
    {
      "heading": "Introduction to Strength Training",
      "content": "Strength training is a form of physical exercise specializing in the use of resistance to induce muscular contraction which builds the strength, anaerobic endurance, and size of skeletal muscles..."
    },
    {
      "heading": "Benefits of Strength Training",
      "content": "Regular strength training provides numerous health benefits including increased muscle mass, improved bone density, enhanced metabolism, and better overall physical performance..."
    },
    {
      "heading": "Getting Started",
      "content": "Before beginning any strength training program, it's important to assess your current fitness level, set realistic goals, and learn proper form to prevent injuries..."
    }
  ],
  "category": "training",
  "tags": ["strength", "beginner", "muscle-building", "fitness"],
  "is_featured": true,
  "is_active": true
}
```

### Required Fields

- `title`: Article title (string, max 255 chars)
- `description`: Article description (string)
- `sections`: Array of article sections (minimum 1 section)
  - `sections[].heading`: Section heading (string, max 255 chars)
  - `sections[].content`: Section content (string)
- `category`: Content category (see categories below)

### Optional Fields

- `cover_image`: Cover image URL (string, URL format)
- `tags`: Array of tags for categorization (array of strings)
- `is_featured`: Whether to feature this content (boolean, default: false)
- `is_active`: Whether content is active (boolean, default: true)

### Categories

- `training`: Workout and exercise related content
- `nutrition`: Food, diet, and nutrition content
- `wellness`: General health and wellness content
- `recovery`: Recovery, rest, and rehabilitation content
- `mental_health`: Mental health and motivation content

### Response

```json
{
  "status": "success",
  "message": "Education content created successfully",
  "data": {
    "id": 1,
    "coverImage": "https://example.com/cover-image.jpg",
    "title": "Complete Guide to Strength Training",
    "description": "A comprehensive guide covering all aspects of strength training...",
    "sections": [
      {
        "heading": "Introduction to Strength Training",
        "content": "Strength training is a form of physical exercise..."
      },
      {
        "heading": "Benefits of Strength Training",
        "content": "Regular strength training provides numerous health benefits..."
      },
      {
        "heading": "Getting Started",
        "content": "Before beginning any strength training program..."
      }
    ],
    "category": "training",
    "tags": ["strength", "beginner", "muscle-building", "fitness"],
    "is_featured": true,
    "created_at": "2025-09-15T14:30:00.000000Z",
    "updated_at": "2025-09-15T14:30:00.000000Z"
  }
}
```

## 3. Get All Education Content

### Endpoint

```
GET /education-contents
```

### Response

```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "coverImage": "https://example.com/cover-image.jpg",
      "title": "Complete Guide to Strength Training",
      "description": "A comprehensive guide covering all aspects...",
      "sections": [...],
      "category": "training",
      "tags": ["strength", "beginner", "muscle-building"],
      "is_featured": true,
      "created_at": "2025-09-15T14:30:00.000000Z",
      "updated_at": "2025-09-15T14:30:00.000000Z"
    }
  ],
  "count": 1
}
```

## 4. Get Single Education Content

### Endpoint

```
GET /education-contents/{id}
```

### Response

```json
{
  "status": "success",
  "data": {
    "id": 1,
    "coverImage": "https://example.com/cover-image.jpg",
    "title": "Complete Guide to Strength Training",
    "description": "A comprehensive guide covering all aspects...",
    "sections": [...],
    "category": "training",
    "tags": ["strength", "beginner", "muscle-building"],
    "is_featured": true,
    "created_at": "2025-09-15T14:30:00.000000Z",
    "updated_at": "2025-09-15T14:30:00.000000Z"
  }
}
```

## 5. Search Education Content

### Endpoint

```
GET /education-contents/search
```

### Query Parameters

- `category`: Filter by category (optional)
- `tags`: Filter by tags (optional)

### Example Request

```
GET /education-contents/search?category=training&tags[]=beginner&tags[]=strength
```

### Response

```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "title": "Complete Guide to Strength Training",
      "category": "training",
      "tags": ["strength", "beginner", "muscle-building"],
      ...
    }
  ],
  "count": 1
}
```

## 6. Update Education Content

### Endpoint

```
PUT /education-contents/{id}
```

### Request Body

```json
{
  "title": "Updated Guide to Strength Training",
  "description": "Updated comprehensive guide...",
  "sections": [
    {
      "heading": "Updated Introduction",
      "content": "Updated content..."
    }
  ],
  "category": "training",
  "tags": ["strength", "advanced", "muscle-building"],
  "is_featured": false,
  "is_active": true
}
```

### Response

```json
{
  "status": "success",
  "message": "Education content updated successfully",
  "data": {
    "id": 1,
    "title": "Updated Guide to Strength Training",
    ...
  }
}
```

## 7. Delete Education Content

### Endpoint

```
DELETE /education-contents/{id}
```

### Response

```json
{
  "status": "success",
  "message": "Education content deleted successfully"
}
```

## Frontend Implementation Examples

### React Component Example

```jsx
import React, { useState } from 'react';

const EducationContentForm = () => {
  const [formData, setFormData] = useState({
    title: '',
    description: '',
    cover_image: '',
    sections: [{ heading: '', content: '' }],
    category: 'training',
    tags: [],
    is_featured: false,
    is_active: true,
  });
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  const addSection = () => {
    setFormData({
      ...formData,
      sections: [...formData.sections, { heading: '', content: '' }],
    });
  };

  const removeSection = (index) => {
    const newSections = formData.sections.filter((_, i) => i !== index);
    setFormData({ ...formData, sections: newSections });
  };

  const updateSection = (index, field, value) => {
    const newSections = [...formData.sections];
    newSections[index][field] = value;
    setFormData({ ...formData, sections: newSections });
  };

  const addTag = (tag) => {
    if (tag && !formData.tags.includes(tag)) {
      setFormData({
        ...formData,
        tags: [...formData.tags, tag],
      });
    }
  };

  const removeTag = (tagToRemove) => {
    setFormData({
      ...formData,
      tags: formData.tags.filter((tag) => tag !== tagToRemove),
    });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setError(null);

    const token = localStorage.getItem('adminToken');

    try {
      const response = await fetch('/api/v1/education-contents', {
        method: 'POST',
        headers: {
          Authorization: `Bearer ${token}`,
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(formData),
      });

      const data = await response.json();

      if (data.status === 'success') {
        alert('Education content created successfully!');
        // Reset form
        setFormData({
          title: '',
          description: '',
          cover_image: '',
          sections: [{ heading: '', content: '' }],
          category: 'training',
          tags: [],
          is_featured: false,
          is_active: true,
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
    <form onSubmit={handleSubmit} className="education-content-form">
      <h2>Create Education Content</h2>

      {error && <div className="error-message">{error}</div>}

      <div className="form-group">
        <label htmlFor="title">Title *</label>
        <input
          type="text"
          id="title"
          value={formData.title}
          onChange={(e) => setFormData({ ...formData, title: e.target.value })}
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
        <label htmlFor="cover_image">Cover Image URL</label>
        <input
          type="url"
          id="cover_image"
          value={formData.cover_image}
          onChange={(e) => setFormData({ ...formData, cover_image: e.target.value })}
        />
      </div>

      <div className="form-group">
        <label htmlFor="category">Category *</label>
        <select
          id="category"
          value={formData.category}
          onChange={(e) => setFormData({ ...formData, category: e.target.value })}
          required
        >
          <option value="training">Training</option>
          <option value="nutrition">Nutrition</option>
          <option value="wellness">Wellness</option>
          <option value="recovery">Recovery</option>
          <option value="mental_health">Mental Health</option>
        </select>
      </div>

      <div className="form-group">
        <label>Tags</label>
        <div className="tags-container">
          {formData.tags.map((tag, index) => (
            <span key={index} className="tag">
              {tag}
              <button type="button" onClick={() => removeTag(tag)}>
                ×
              </button>
            </span>
          ))}
          <input
            type="text"
            placeholder="Add tag"
            onKeyPress={(e) => {
              if (e.key === 'Enter') {
                e.preventDefault();
                addTag(e.target.value);
                e.target.value = '';
              }
            }}
          />
        </div>
      </div>

      <div className="form-group">
        <label>Sections *</label>
        {formData.sections.map((section, index) => (
          <div key={index} className="section">
            <div className="section-header">
              <h4>Section {index + 1}</h4>
              {formData.sections.length > 1 && (
                <button type="button" onClick={() => removeSection(index)}>
                  Remove Section
                </button>
              )}
            </div>
            <input
              type="text"
              placeholder="Section heading"
              value={section.heading}
              onChange={(e) => updateSection(index, 'heading', e.target.value)}
              required
              maxLength={255}
            />
            <textarea
              placeholder="Section content"
              value={section.content}
              onChange={(e) => updateSection(index, 'content', e.target.value)}
              required
              rows="6"
            />
          </div>
        ))}
        <button type="button" onClick={addSection} className="add-section-btn">
          Add Section
        </button>
      </div>

      <div className="form-group">
        <label>
          <input
            type="checkbox"
            checked={formData.is_featured}
            onChange={(e) => setFormData({ ...formData, is_featured: e.target.checked })}
          />
          Featured Content
        </label>
      </div>

      <div className="form-group">
        <label>
          <input
            type="checkbox"
            checked={formData.is_active}
            onChange={(e) => setFormData({ ...formData, is_active: e.target.checked })}
          />
          Active Content
        </label>
      </div>

      <button type="submit" disabled={loading}>
        {loading ? 'Creating...' : 'Create Education Content'}
      </button>
    </form>
  );
};

export default EducationContentForm;
```

### Vanilla JavaScript Example

```javascript
class EducationContentManager {
  constructor(apiBaseUrl, token) {
    this.apiBaseUrl = apiBaseUrl;
    this.token = token;
  }

  async createContent(contentData) {
    try {
      const response = await fetch(`${this.apiBaseUrl}/education-contents`, {
        method: 'POST',
        headers: {
          Authorization: `Bearer ${this.token}`,
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(contentData),
      });

      const data = await response.json();

      if (data.status === 'success') {
        return {
          success: true,
          content: data.data,
        };
      } else {
        return {
          success: false,
          error: data.message,
          errors: data.errors,
        };
      }
    } catch (error) {
      return {
        success: false,
        error: 'Network error: ' + error.message,
      };
    }
  }

  async getAllContent() {
    try {
      const response = await fetch(`${this.apiBaseUrl}/education-contents`);
      const data = await response.json();

      if (data.status === 'success') {
        return {
          success: true,
          content: data.data,
          count: data.count,
        };
      } else {
        return {
          success: false,
          error: data.message,
        };
      }
    } catch (error) {
      return {
        success: false,
        error: 'Network error: ' + error.message,
      };
    }
  }

  async getContentById(id) {
    try {
      const response = await fetch(`${this.apiBaseUrl}/education-contents/${id}`);
      const data = await response.json();

      if (data.status === 'success') {
        return {
          success: true,
          content: data.data,
        };
      } else {
        return {
          success: false,
          error: data.message,
        };
      }
    } catch (error) {
      return {
        success: false,
        error: 'Network error: ' + error.message,
      };
    }
  }

  async searchContent(filters = {}) {
    try {
      const queryParams = new URLSearchParams();

      if (filters.category) {
        queryParams.append('category', filters.category);
      }

      if (filters.tags && filters.tags.length > 0) {
        filters.tags.forEach((tag) => {
          queryParams.append('tags[]', tag);
        });
      }

      const response = await fetch(`${this.apiBaseUrl}/education-contents/search?${queryParams}`);
      const data = await response.json();

      if (data.status === 'success') {
        return {
          success: true,
          content: data.data,
          count: data.count,
        };
      } else {
        return {
          success: false,
          error: data.message,
        };
      }
    } catch (error) {
      return {
        success: false,
        error: 'Network error: ' + error.message,
      };
    }
  }

  async updateContent(id, contentData) {
    try {
      const response = await fetch(`${this.apiBaseUrl}/education-contents/${id}`, {
        method: 'PUT',
        headers: {
          Authorization: `Bearer ${this.token}`,
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(contentData),
      });

      const data = await response.json();

      if (data.status === 'success') {
        return {
          success: true,
          content: data.data,
        };
      } else {
        return {
          success: false,
          error: data.message,
          errors: data.errors,
        };
      }
    } catch (error) {
      return {
        success: false,
        error: 'Network error: ' + error.message,
      };
    }
  }

  async deleteContent(id) {
    try {
      const response = await fetch(`${this.apiBaseUrl}/education-contents/${id}`, {
        method: 'DELETE',
        headers: {
          Authorization: `Bearer ${this.token}`,
        },
      });

      const data = await response.json();

      if (data.status === 'success') {
        return {
          success: true,
          message: data.message,
        };
      } else {
        return {
          success: false,
          error: data.message,
        };
      }
    } catch (error) {
      return {
        success: false,
        error: 'Network error: ' + error.message,
      };
    }
  }
}

// Usage Example
const contentManager = new EducationContentManager('/api/v1', localStorage.getItem('adminToken'));

// Create new content
const newContent = {
  title: 'Nutrition Basics for Athletes',
  description: 'Essential nutrition guidelines for athletic performance',
  cover_image: 'https://example.com/nutrition-cover.jpg',
  sections: [
    {
      heading: 'Macronutrients',
      content: 'Understanding proteins, carbohydrates, and fats...',
    },
    {
      heading: 'Hydration',
      content: 'The importance of proper hydration...',
    },
  ],
  category: 'nutrition',
  tags: ['nutrition', 'athletes', 'macronutrients'],
  is_featured: true,
  is_active: true,
};

const result = await contentManager.createContent(newContent);

if (result.success) {
  console.log('Content created:', result.content);
} else {
  console.error('Creation failed:', result.error);
}
```

## CSS Styling Example

```css
.education-content-form {
  max-width: 800px;
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

.tag button {
  background: none;
  border: none;
  color: white;
  cursor: pointer;
  font-weight: bold;
}

.section {
  border: 1px solid #eee;
  padding: 15px;
  margin-bottom: 15px;
  border-radius: 4px;
}

.section-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 10px;
}

.section-header h4 {
  margin: 0;
}

.add-section-btn {
  background-color: #28a745;
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 4px;
  cursor: pointer;
}

.add-section-btn:hover {
  background-color: #218838;
}

button[type='submit'] {
  background-color: #007bff;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 16px;
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

### Create Education Content

**Method**: `POST`  
**URL**: `{{baseUrl}}/education-contents`  
**Headers**: `Authorization: Bearer {{adminToken}}`  
**Body**: JSON

```json
{
  "title": "Complete Guide to Strength Training",
  "description": "A comprehensive guide covering all aspects of strength training for beginners and advanced athletes.",
  "cover_image": "https://example.com/cover-image.jpg",
  "sections": [
    {
      "heading": "Introduction to Strength Training",
      "content": "Strength training is a form of physical exercise specializing in the use of resistance to induce muscular contraction which builds the strength, anaerobic endurance, and size of skeletal muscles."
    },
    {
      "heading": "Benefits of Strength Training",
      "content": "Regular strength training provides numerous health benefits including increased muscle mass, improved bone density, enhanced metabolism, and better overall physical performance."
    }
  ],
  "category": "training",
  "tags": ["strength", "beginner", "muscle-building", "fitness"],
  "is_featured": true,
  "is_active": true
}
```

### Get All Education Content

**Method**: `GET`  
**URL**: `{{baseUrl}}/education-contents`

### Get Single Education Content

**Method**: `GET`  
**URL**: `{{baseUrl}}/education-contents/1`

### Search Education Content

**Method**: `GET`  
**URL**: `{{baseUrl}}/education-contents/search?category=training&tags[]=beginner`

### Update Education Content

**Method**: `PUT`  
**URL**: `{{baseUrl}}/education-contents/1`  
**Headers**: `Authorization: Bearer {{adminToken}}`  
**Body**: JSON (same structure as create)

### Delete Education Content

**Method**: `DELETE`  
**URL**: `{{baseUrl}}/education-contents/1`  
**Headers**: `Authorization: Bearer {{adminToken}}`

## Error Handling

### Common Error Responses

#### Validation Errors (422)

```json
{
  "status": "error",
  "message": "Validation failed",
  "errors": {
    "title": ["The title field is required."],
    "sections": ["The sections field is required."],
    "sections.0.heading": ["The sections.0.heading field is required."]
  }
}
```

#### Not Found Error (404)

```json
{
  "status": "error",
  "message": "Education content not found",
  "error": "No education content found with the specified ID"
}
```

#### Server Error (500)

```json
{
  "status": "error",
  "message": "Failed to create education content",
  "error": "Database connection error"
}
```

## Best Practices

### Content Structure

1. **Clear Titles**: Use descriptive, SEO-friendly titles
2. **Compelling Descriptions**: Write engaging descriptions that summarize the content
3. **Logical Sections**: Organize content into logical, digestible sections
4. **Consistent Formatting**: Use consistent heading and content formatting

### Categories and Tags

1. **Choose Appropriate Categories**: Select the most relevant category
2. **Use Relevant Tags**: Add 3-5 relevant tags for better discoverability
3. **Consistent Tagging**: Use consistent tag naming conventions

### Content Quality

1. **Accurate Information**: Ensure all information is accurate and up-to-date
2. **Proper Citations**: Include proper citations and references
3. **Regular Updates**: Keep content updated with latest information
4. **User-Friendly**: Write in a clear, accessible language

### Technical Considerations

1. **Image URLs**: Use reliable, accessible image URLs
2. **Content Length**: Keep sections at reasonable lengths
3. **Mobile-Friendly**: Ensure content displays well on mobile devices
4. **SEO Optimization**: Use relevant keywords in titles and descriptions

This documentation provides everything needed to implement education content management in your FitHabs application!
