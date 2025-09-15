# FormData Image Upload Guide for Recipes & Education Content

## ✅ **YES! You can upload images using FormData for both:**

### **🍽️ Recipes** - ✅ **Already Supported**

### **📚 Education Content** - ✅ **Now Supported** (Just Updated!)

---

## 🚀 **Frontend Implementation Examples**

### 1. **Create Recipe with FormData Image Upload**

```javascript
// Frontend: Upload recipe with image using FormData
const formData = new FormData();

// Add text data
formData.append('name', 'Grilled Chicken Breast with Quinoa');
formData.append('description', 'A healthy and protein-rich meal perfect for post-workout recovery');
formData.append('meal_type', 'dinner');
formData.append('prep_time_minutes', '15');
formData.append('cook_time_minutes', '25');
formData.append('servings', '2');
formData.append('calories_per_serving', '450');
formData.append('protein_per_serving', '35.5');
formData.append('carbs_per_serving', '28.0');
formData.append('fat_per_serving', '18.2');
formData.append('fiber_per_serving', '4.5');
formData.append('sugar_per_serving', '2.1');
formData.append(
  'ingredients',
  '2 chicken breasts (6 oz each)\n1 cup quinoa\n2 cups chicken broth\n1 tbsp olive oil\nSalt and pepper to taste\nFresh herbs for garnish'
);
formData.append(
  'instructions',
  '1. Season chicken breasts with salt and pepper\n2. Heat olive oil in a pan over medium-high heat\n3. Cook chicken for 6-7 minutes per side\n4. Meanwhile, cook quinoa in chicken broth\n5. Let chicken rest for 5 minutes before slicing\n6. Serve over quinoa and garnish with herbs'
);
formData.append('difficulty', 'easy');
formData.append('is_featured', 'true');
formData.append('is_active', 'true');
formData.append('created_by_admin', '1');

// Add dietary tags as array
formData.append('dietary_tags[]', 'high-protein');
formData.append('dietary_tags[]', 'low-carb');
formData.append('dietary_tags[]', 'gluten-free');

// Add allergen info as array
formData.append('allergen_info[]', 'none');

// Add image file
const imageFile = document.getElementById('recipe-image').files[0];
if (imageFile) {
  formData.append('image', imageFile);
}

// Send request
const response = await fetch('/api/v1/recipes', {
  method: 'POST',
  headers: {
    Authorization: `Bearer ${adminToken}`,
    // Don't set Content-Type - let browser set it with boundary
  },
  body: formData,
});

const result = await response.json();
console.log('Recipe created:', result);
```

### 2. **Create Education Content with FormData Image Upload**

```javascript
// Frontend: Upload education content with image using FormData
const formData = new FormData();

// Add text data
formData.append('title', 'Complete Guide to Strength Training');
formData.append(
  'description',
  'A comprehensive guide covering all aspects of strength training for beginners and advanced athletes.'
);
formData.append('category', 'training');
formData.append('is_featured', 'true');
formData.append('is_active', 'true');

// Add sections as JSON string (since FormData doesn't handle nested objects well)
const sections = [
  {
    heading: 'Introduction to Strength Training',
    content:
      'Strength training is a form of physical exercise specializing in the use of resistance to induce muscular contraction which builds the strength, anaerobic endurance, and size of skeletal muscles.',
  },
  {
    heading: 'Benefits of Strength Training',
    content:
      'Regular strength training provides numerous health benefits including increased muscle mass, improved bone density, enhanced metabolism, and better overall physical performance.',
  },
];
formData.append('sections', JSON.stringify(sections));

// Add tags as array
formData.append('tags[]', 'strength');
formData.append('tags[]', 'beginner');
formData.append('tags[]', 'muscle-building');
formData.append('tags[]', 'fitness');

// Add image file
const imageFile = document.getElementById('education-image').files[0];
if (imageFile) {
  formData.append('image', imageFile);
}

// Send request
const response = await fetch('/api/v1/education-contents', {
  method: 'POST',
  headers: {
    Authorization: `Bearer ${adminToken}`,
    // Don't set Content-Type - let browser set it with boundary
  },
  body: formData,
});

const result = await response.json();
console.log('Education content created:', result);
```

### 3. **React Component Example for Recipe Upload**

```jsx
import React, { useState } from 'react';

const RecipeUploadForm = () => {
  const [formData, setFormData] = useState({
    name: '',
    description: '',
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
  const [imageFile, setImageFile] = useState(null);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  const handleImageChange = (e) => {
    const file = e.target.files[0];
    if (file) {
      // Validate file type
      const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
      if (!allowedTypes.includes(file.type)) {
        setError('Please select a valid image file (JPEG, PNG, GIF, WebP)');
        return;
      }

      // Validate file size (10MB = 10485760 bytes)
      if (file.size > 10485760) {
        setError('Image file is too large. Maximum size: 10MB');
        return;
      }

      setImageFile(file);
      setError(null);
    }
  };

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

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setError(null);

    const token = localStorage.getItem('adminToken');
    const submitFormData = new FormData();

    // Add all form data
    Object.keys(formData).forEach((key) => {
      if (key === 'dietary_tags' || key === 'allergen_info') {
        // Add arrays as multiple values
        formData[key].forEach((item) => {
          submitFormData.append(`${key}[]`, item);
        });
      } else {
        submitFormData.append(key, formData[key]);
      }
    });

    // Add image file
    if (imageFile) {
      submitFormData.append('image', imageFile);
    }

    try {
      const response = await fetch('/api/v1/recipes', {
        method: 'POST',
        headers: {
          Authorization: `Bearer ${token}`,
          // Don't set Content-Type - let browser set it with boundary
        },
        body: submitFormData,
      });

      const data = await response.json();

      if (data.status === 'success') {
        alert('Recipe created successfully!');
        // Reset form
        setFormData({
          name: '',
          description: '',
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
        setImageFile(null);
        document.getElementById('recipe-image').value = '';
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
    <form onSubmit={handleSubmit} className="recipe-upload-form">
      <h2>Create Recipe with Image Upload</h2>

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
        <label htmlFor="recipe-image">Recipe Image</label>
        <input
          type="file"
          id="recipe-image"
          accept="image/jpeg,image/jpg,image/png,image/gif,image/webp"
          onChange={handleImageChange}
        />
        {imageFile && (
          <div className="image-preview">
            <p>
              Selected: {imageFile.name} ({(imageFile.size / 1024 / 1024).toFixed(2)} MB)
            </p>
          </div>
        )}
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

      <button type="submit" disabled={loading}>
        {loading ? 'Creating Recipe...' : 'Create Recipe'}
      </button>
    </form>
  );
};

export default RecipeUploadForm;
```

### 4. **React Component Example for Education Content Upload**

```jsx
import React, { useState } from 'react';

const EducationContentUploadForm = () => {
  const [formData, setFormData] = useState({
    title: '',
    description: '',
    category: 'training',
    tags: [],
    is_featured: false,
    is_active: true,
  });
  const [sections, setSections] = useState([{ heading: '', content: '' }]);
  const [imageFile, setImageFile] = useState(null);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  const handleImageChange = (e) => {
    const file = e.target.files[0];
    if (file) {
      // Validate file type
      const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
      if (!allowedTypes.includes(file.type)) {
        setError('Please select a valid image file (JPEG, PNG, GIF, WebP)');
        return;
      }

      // Validate file size (10MB = 10485760 bytes)
      if (file.size > 10485760) {
        setError('Image file is too large. Maximum size: 10MB');
        return;
      }

      setImageFile(file);
      setError(null);
    }
  };

  const addSection = () => {
    setSections([...sections, { heading: '', content: '' }]);
  };

  const removeSection = (index) => {
    if (sections.length > 1) {
      const newSections = sections.filter((_, i) => i !== index);
      setSections(newSections);
    }
  };

  const updateSection = (index, field, value) => {
    const newSections = [...sections];
    newSections[index][field] = value;
    setSections(newSections);
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
    const submitFormData = new FormData();

    // Add basic form data
    submitFormData.append('title', formData.title);
    submitFormData.append('description', formData.description);
    submitFormData.append('category', formData.category);
    submitFormData.append('is_featured', formData.is_featured);
    submitFormData.append('is_active', formData.is_active);

    // Add sections as JSON string
    submitFormData.append('sections', JSON.stringify(sections));

    // Add tags as array
    formData.tags.forEach((tag) => {
      submitFormData.append('tags[]', tag);
    });

    // Add image file
    if (imageFile) {
      submitFormData.append('image', imageFile);
    }

    try {
      const response = await fetch('/api/v1/education-contents', {
        method: 'POST',
        headers: {
          Authorization: `Bearer ${token}`,
          // Don't set Content-Type - let browser set it with boundary
        },
        body: submitFormData,
      });

      const data = await response.json();

      if (data.status === 'success') {
        alert('Education content created successfully!');
        // Reset form
        setFormData({
          title: '',
          description: '',
          category: 'training',
          tags: [],
          is_featured: false,
          is_active: true,
        });
        setSections([{ heading: '', content: '' }]);
        setImageFile(null);
        document.getElementById('education-image').value = '';
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
    <form onSubmit={handleSubmit} className="education-upload-form">
      <h2>Create Education Content with Image Upload</h2>

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
        <label htmlFor="education-image">Cover Image</label>
        <input
          type="file"
          id="education-image"
          accept="image/jpeg,image/jpg,image/png,image/gif,image/webp"
          onChange={handleImageChange}
        />
        {imageFile && (
          <div className="image-preview">
            <p>
              Selected: {imageFile.name} ({(imageFile.size / 1024 / 1024).toFixed(2)} MB)
            </p>
          </div>
        )}
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
        <label>Sections *</label>
        {sections.map((section, index) => (
          <div key={index} className="section">
            <div className="section-header">
              <h4>Section {index + 1}</h4>
              {sections.length > 1 && (
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
        <label>
          <input
            type="checkbox"
            checked={formData.is_featured}
            onChange={(e) => setFormData({ ...formData, is_featured: e.target.checked })}
          />
          Featured Content
        </label>
      </div>

      <button type="submit" disabled={loading}>
        {loading ? 'Creating Content...' : 'Create Education Content'}
      </button>
    </form>
  );
};

export default EducationContentUploadForm;
```

---

## 📋 **API Endpoints Summary**

### **Recipes**

- **Create**: `POST /api/v1/recipes`
- **Update**: `PUT /api/v1/recipes/{id}`
- **Image Field**: `image` (file)
- **Max Size**: 10MB
- **Formats**: JPEG, JPG, PNG, GIF, WebP

### **Education Content**

- **Create**: `POST /api/v1/education-contents`
- **Update**: `PUT /api/v1/education-contents/{id}`
- **Image Field**: `image` (file)
- **Max Size**: 10MB
- **Formats**: JPEG, JPG, PNG, GIF, WebP

---

## 🔧 **Backend Implementation Details**

### **Image Upload Process**

1. **File Validation**: Checks file type, size, and format
2. **Unique Naming**: Generates UUID-based filenames
3. **Storage**: Saves to `storage/images/` directory
4. **Database Record**: Creates Image model entry
5. **URL Generation**: Returns full URL for frontend use

### **FormData Handling**

- **Text Fields**: Added as strings
- **Arrays**: Added with `[]` notation (e.g., `tags[]`)
- **Files**: Added as File objects
- **JSON Data**: Stringified for complex objects (sections)

---

## ✅ **Key Points**

1. **✅ Both Controllers Support FormData**: Recipes and Education Content
2. **✅ Image Upload**: 10MB max, multiple formats supported
3. **✅ Validation**: File type, size, and format validation
4. **✅ URL Generation**: Full URLs returned for frontend use
5. **✅ Database Tracking**: Image records created for management
6. **✅ Error Handling**: Comprehensive error responses

**You can now upload images using FormData for both recipes and education content!** 🚀
