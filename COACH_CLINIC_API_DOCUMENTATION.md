# Coach & Clinic Management API Documentation

## 📋 **Overview**

This documentation covers all endpoints for managing **Coaches** and **Clinics** in the FitHabs application. These endpoints are designed for both **admin users** (who can manage all coaches/clinics) and **professional users** (who can manage their own profiles).

## 🔐 **Authentication**

All endpoints require authentication using Laravel Sanctum. Include the Bearer token in the Authorization header:

```
Authorization: Bearer {your-token}
```

## 👥 **COACH MANAGEMENT**

### **1. List All Coaches (Public)**

**Endpoint:** `GET /api/v1/coaches`

**Description:** Retrieve all active coaches for public browsing

**Authentication:** Not required

**Query Parameters:**

- `page` (optional): Page number for pagination
- `per_page` (optional): Number of items per page (default: 15)
- `search` (optional): Search by name or specializations
- `specialization` (optional): Filter by specialization

**Request Example:**

```bash
curl -X GET "http://127.0.0.1:8000/api/v1/coaches?search=fitness&specialization=weight_loss" \
  -H "Accept: application/json"
```

**Response Example:**

```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "name": "John Smith",
      "bio": "Certified personal trainer with 10+ years experience",
      "profile_image": "https://example.com/images/coach1.jpg",
      "specializations": ["weight_loss", "muscle_gain", "cardio"],
      "certifications": ["NASM-CPT", "ACE-CPT"],
      "phone": "+1234567890",
      "chat_url": "/api/v1/coaches/1/chat"
    }
  ],
  "count": 1
}
```

### **2. Get Single Coach (Public)**

**Endpoint:** `GET /api/v1/coaches/{id}`

**Description:** Retrieve detailed information about a specific coach

**Authentication:** Not required

**Request Example:**

```bash
curl -X GET "http://127.0.0.1:8000/api/v1/coaches/1" \
  -H "Accept: application/json"
```

**Response Example:**

```json
{
  "id": 1,
  "name": "John Smith",
  "email": "john@example.com",
  "bio": "Certified personal trainer with 10+ years experience",
  "profile_image": "https://example.com/images/coach1.jpg",
  "specializations": ["weight_loss", "muscle_gain", "cardio"],
  "certifications": ["NASM-CPT", "ACE-CPT"],
  "phone": "+1234567890",
  "is_active": true,
  "created_at": "2024-01-15T10:30:00.000000Z",
  "updated_at": "2024-01-15T10:30:00.000000Z"
}
```

### **3. Create Coach (Admin Only)**

**Endpoint:** `POST /api/v1/coaches`

**Description:** Create a new coach profile (Admin access required)

**Authentication:** Required (Admin token)

**Request Body (JSON):**

```json
{
  "name": "Jane Doe",
  "email": "jane@example.com",
  "password": "securepassword123",
  "bio": "Experienced fitness coach specializing in women's health",
  "profile_image": "https://example.com/images/jane.jpg",
  "specializations": ["women_health", "prenatal", "postnatal"],
  "certifications": ["ACSM-CPT", "Precision Nutrition"],
  "phone": "+1234567891",
  "is_active": true
}
```

**Request Example:**

```bash
curl -X POST "http://127.0.0.1:8000/api/v1/coaches" \
  -H "Authorization: Bearer {admin-token}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "Jane Doe",
    "email": "jane@example.com",
    "password": "securepassword123",
    "bio": "Experienced fitness coach specializing in women'\''s health",
    "specializations": ["women_health", "prenatal", "postnatal"],
    "certifications": ["ACSM-CPT", "Precision Nutrition"],
    "phone": "+1234567891",
    "is_active": true
  }'
```

**Response Example:**

```json
{
  "success": true,
  "message": "Coach created successfully",
  "data": {
    "id": 2,
    "name": "Jane Doe",
    "email": "jane@example.com",
    "bio": "Experienced fitness coach specializing in women's health",
    "profile_image": "https://example.com/images/jane.jpg",
    "specializations": ["women_health", "prenatal", "postnatal"],
    "certifications": ["ACSM-CPT", "Precision Nutrition"],
    "phone": "+1234567891",
    "is_active": true,
    "created_at": "2024-01-15T11:00:00.000000Z",
    "updated_at": "2024-01-15T11:00:00.000000Z"
  }
}
```

### **4. Create Coach with Image Upload (Admin Only)**

**Endpoint:** `POST /api/v1/coaches`

**Description:** Create a new coach with profile image upload

**Authentication:** Required (Admin token)

**Request Body (FormData):**

```
name: Jane Doe
email: jane@example.com
password: securepassword123
bio: Experienced fitness coach specializing in women's health
specializations[]: women_health
specializations[]: prenatal
specializations[]: postnatal
certifications[]: ACSM-CPT
certifications[]: Precision Nutrition
phone: +1234567891
is_active: true
profile_image: [FILE] (jpeg, jpg, png, gif, webp - max 10MB)
```

**Request Example:**

```bash
curl -X POST "http://127.0.0.1:8000/api/v1/coaches" \
  -H "Authorization: Bearer {admin-token}" \
  -F "name=Jane Doe" \
  -F "email=jane@example.com" \
  -F "password=securepassword123" \
  -F "bio=Experienced fitness coach specializing in women's health" \
  -F "specializations[]=women_health" \
  -F "specializations[]=prenatal" \
  -F "specializations[]=postnatal" \
  -F "certifications[]=ACSM-CPT" \
  -F "certifications[]=Precision Nutrition" \
  -F "phone=+1234567891" \
  -F "is_active=true" \
  -F "profile_image=@/path/to/image.jpg"
```

**Response Example:**

```json
{
  "success": true,
  "message": "Coach created successfully",
  "data": {
    "id": 2,
    "name": "Jane Doe",
    "email": "jane@example.com",
    "bio": "Experienced fitness coach specializing in women's health",
    "profile_image": "http://127.0.0.1:8000/storage/images/uuid-generated-filename.jpg",
    "specializations": ["women_health", "prenatal", "postnatal"],
    "certifications": ["ACSM-CPT", "Precision Nutrition"],
    "phone": "+1234567891",
    "is_active": true,
    "created_at": "2024-01-15T11:00:00.000000Z",
    "updated_at": "2024-01-15T11:00:00.000000Z"
  }
}
```

### **5. Update Coach (Admin Only)**

**Endpoint:** `PUT /api/v1/coaches/{id}`

**Description:** Update an existing coach profile

**Authentication:** Required (Admin token)

**Request Body (JSON):**

```json
{
  "name": "Jane Smith",
  "bio": "Updated bio with more experience",
  "specializations": ["women_health", "prenatal", "postnatal", "senior_fitness"],
  "certifications": ["ACSM-CPT", "Precision Nutrition", "Senior Fitness Specialist"],
  "phone": "+1234567892",
  "is_active": true
}
```

**Request Example:**

```bash
curl -X PUT "http://127.0.0.1:8000/api/v1/coaches/2" \
  -H "Authorization: Bearer {admin-token}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "Jane Smith",
    "bio": "Updated bio with more experience",
    "specializations": ["women_health", "prenatal", "postnatal", "senior_fitness"],
    "certifications": ["ACSM-CPT", "Precision Nutrition", "Senior Fitness Specialist"],
    "phone": "+1234567892",
    "is_active": true
  }'
```

**Response Example:**

```json
{
  "success": true,
  "message": "Coach updated successfully",
  "data": {
    "id": 2,
    "name": "Jane Smith",
    "email": "jane@example.com",
    "bio": "Updated bio with more experience",
    "profile_image": "http://127.0.0.1:8000/storage/images/uuid-generated-filename.jpg",
    "specializations": ["women_health", "prenatal", "postnatal", "senior_fitness"],
    "certifications": ["ACSM-CPT", "Precision Nutrition", "Senior Fitness Specialist"],
    "phone": "+1234567892",
    "is_active": true,
    "created_at": "2024-01-15T11:00:00.000000Z",
    "updated_at": "2024-01-15T12:00:00.000000Z"
  }
}
```

### **6. Delete Coach (Admin Only)**

**Endpoint:** `DELETE /api/v1/coaches/{id}`

**Description:** Delete a coach profile

**Authentication:** Required (Admin token)

**Request Example:**

```bash
curl -X DELETE "http://127.0.0.1:8000/api/v1/coaches/2" \
  -H "Authorization: Bearer {admin-token}" \
  -H "Accept: application/json"
```

**Response:** `204 No Content`

### **7. List All Coaches (Admin Only)**

**Endpoint:** `GET /api/v1/coaches`

**Description:** Retrieve all coaches including inactive ones (Admin access required)

**Authentication:** Required (Admin token)

**Query Parameters:**

- `page` (optional): Page number for pagination
- `per_page` (optional): Number of items per page (default: 15)
- `search` (optional): Search by name or specializations
- `specialization` (optional): Filter by specialization
- `status` (optional): Filter by active status (`active`, `inactive`, `all`)

**Request Example:**

```bash
curl -X GET "http://127.0.0.1:8000/api/v1/coaches?status=all&per_page=20" \
  -H "Authorization: Bearer {admin-token}" \
  -H "Accept: application/json"
```

**Response Example:**

```json
{
  "data": [
    {
      "id": 1,
      "name": "John Smith",
      "email": "john@example.com",
      "bio": "Certified personal trainer with 10+ years experience",
      "profile_image": "https://example.com/images/coach1.jpg",
      "specializations": ["weight_loss", "muscle_gain", "cardio"],
      "certifications": ["NASM-CPT", "ACE-CPT"],
      "phone": "+1234567890",
      "is_active": true,
      "created_at": "2024-01-15T10:30:00.000000Z",
      "updated_at": "2024-01-15T10:30:00.000000Z"
    },
    {
      "id": 2,
      "name": "Jane Smith",
      "email": "jane@example.com",
      "bio": "Updated bio with more experience",
      "profile_image": "http://127.0.0.1:8000/storage/images/uuid-generated-filename.jpg",
      "specializations": ["women_health", "prenatal", "postnatal", "senior_fitness"],
      "certifications": ["ACSM-CPT", "Precision Nutrition", "Senior Fitness Specialist"],
      "phone": "+1234567892",
      "is_active": false,
      "created_at": "2024-01-15T11:00:00.000000Z",
      "updated_at": "2024-01-15T12:00:00.000000Z"
    }
  ],
  "current_page": 1,
  "per_page": 20,
  "total": 2,
  "last_page": 1
}
```

## 🏥 **CLINIC MANAGEMENT**

### **1. List All Clinics (Public)**

**Endpoint:** `GET /api/v1/clinics`

**Description:** Retrieve all active clinics for public browsing

**Authentication:** Not required

**Query Parameters:**

- `page` (optional): Page number for pagination
- `per_page` (optional): Number of items per page (default: 15)
- `search` (optional): Search by name or services
- `service` (optional): Filter by service type
- `location` (optional): Filter by location/address

**Request Example:**

```bash
curl -X GET "http://127.0.0.1:8000/api/v1/clinics?search=physical&service=rehabilitation" \
  -H "Accept: application/json"
```

**Response Example:**

```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "name": "HealthPlus Physical Therapy",
      "description": "Comprehensive physical therapy and rehabilitation services",
      "logo": "https://example.com/images/clinic1.jpg",
      "phone": "+1234567890",
      "address": "123 Main St, City, State 12345",
      "website": "https://healthplus.com",
      "services": ["physical_therapy", "rehabilitation", "sports_medicine"],
      "chat_url": "/api/v1/clinics/1/chat"
    }
  ],
  "count": 1
}
```

### **2. Get Single Clinic (Public)**

**Endpoint:** `GET /api/v1/clinics/{id}`

**Description:** Retrieve detailed information about a specific clinic

**Authentication:** Not required

**Request Example:**

```bash
curl -X GET "http://127.0.0.1:8000/api/v1/clinics/1" \
  -H "Accept: application/json"
```

**Response Example:**

```json
{
  "id": 1,
  "name": "HealthPlus Physical Therapy",
  "email": "info@healthplus.com",
  "description": "Comprehensive physical therapy and rehabilitation services",
  "logo": "https://example.com/images/clinic1.jpg",
  "phone": "+1234567890",
  "address": "123 Main St, City, State 12345",
  "website": "https://healthplus.com",
  "services": ["physical_therapy", "rehabilitation", "sports_medicine"],
  "is_active": true,
  "created_at": "2024-01-15T10:30:00.000000Z",
  "updated_at": "2024-01-15T10:30:00.000000Z"
}
```

### **3. Create Clinic (Admin Only)**

**Endpoint:** `POST /api/v1/clinics`

**Description:** Create a new clinic profile (Admin access required)

**Authentication:** Required (Admin token)

**Request Body (JSON):**

```json
{
  "name": "Wellness Center",
  "email": "info@wellnesscenter.com",
  "password": "securepassword123",
  "description": "Holistic wellness and therapy services",
  "logo": "https://example.com/images/wellness.jpg",
  "phone": "+1234567891",
  "address": "456 Oak Ave, City, State 12345",
  "website": "https://wellnesscenter.com",
  "services": ["massage_therapy", "acupuncture", "nutrition_counseling"],
  "is_active": true
}
```

**Request Example:**

```bash
curl -X POST "http://127.0.0.1:8000/api/v1/clinics" \
  -H "Authorization: Bearer {admin-token}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "Wellness Center",
    "email": "info@wellnesscenter.com",
    "password": "securepassword123",
    "description": "Holistic wellness and therapy services",
    "phone": "+1234567891",
    "address": "456 Oak Ave, City, State 12345",
    "website": "https://wellnesscenter.com",
    "services": ["massage_therapy", "acupuncture", "nutrition_counseling"],
    "is_active": true
  }'
```

**Response Example:**

```json
{
  "success": true,
  "message": "Clinic created successfully",
  "data": {
    "id": 2,
    "name": "Wellness Center",
    "email": "info@wellnesscenter.com",
    "description": "Holistic wellness and therapy services",
    "logo": "https://example.com/images/wellness.jpg",
    "phone": "+1234567891",
    "address": "456 Oak Ave, City, State 12345",
    "website": "https://wellnesscenter.com",
    "services": ["massage_therapy", "acupuncture", "nutrition_counseling"],
    "is_active": true,
    "created_at": "2024-01-15T11:00:00.000000Z",
    "updated_at": "2024-01-15T11:00:00.000000Z"
  }
}
```

### **4. Create Clinic with Logo Upload (Admin Only)**

**Endpoint:** `POST /api/v1/clinics`

**Description:** Create a new clinic with logo upload

**Authentication:** Required (Admin token)

**Request Body (FormData):**

```
name: Wellness Center
email: info@wellnesscenter.com
password: securepassword123
description: Holistic wellness and therapy services
phone: +1234567891
address: 456 Oak Ave, City, State 12345
website: https://wellnesscenter.com
services[]: massage_therapy
services[]: acupuncture
services[]: nutrition_counseling
is_active: true
logo: [FILE] (jpeg, jpg, png, gif, webp - max 10MB)
```

**Request Example:**

```bash
curl -X POST "http://127.0.0.1:8000/api/v1/clinics" \
  -H "Authorization: Bearer {admin-token}" \
  -F "name=Wellness Center" \
  -F "email=info@wellnesscenter.com" \
  -F "password=securepassword123" \
  -F "description=Holistic wellness and therapy services" \
  -F "phone=+1234567891" \
  -F "address=456 Oak Ave, City, State 12345" \
  -F "website=https://wellnesscenter.com" \
  -F "services[]=massage_therapy" \
  -F "services[]=acupuncture" \
  -F "services[]=nutrition_counseling" \
  -F "is_active=true" \
  -F "logo=@/path/to/logo.png"
```

**Response Example:**

```json
{
  "success": true,
  "message": "Clinic created successfully",
  "data": {
    "id": 2,
    "name": "Wellness Center",
    "email": "info@wellnesscenter.com",
    "description": "Holistic wellness and therapy services",
    "logo": "http://127.0.0.1:8000/storage/images/uuid-generated-filename.png",
    "phone": "+1234567891",
    "address": "456 Oak Ave, City, State 12345",
    "website": "https://wellnesscenter.com",
    "services": ["massage_therapy", "acupuncture", "nutrition_counseling"],
    "is_active": true,
    "created_at": "2024-01-15T11:00:00.000000Z",
    "updated_at": "2024-01-15T11:00:00.000000Z"
  }
}
```

### **5. Update Clinic (Admin Only)**

**Endpoint:** `PUT /api/v1/clinics/{id}`

**Description:** Update an existing clinic profile

**Authentication:** Required (Admin token)

**Request Body (JSON):**

```json
{
  "name": "Wellness & Therapy Center",
  "description": "Updated description with expanded services",
  "phone": "+1234567892",
  "address": "456 Oak Ave, Suite 200, City, State 12345",
  "website": "https://wellnesscenter.com",
  "services": ["massage_therapy", "acupuncture", "nutrition_counseling", "mental_health"],
  "is_active": true
}
```

**Request Example:**

```bash
curl -X PUT "http://127.0.0.1:8000/api/v1/clinics/2" \
  -H "Authorization: Bearer {admin-token}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "Wellness & Therapy Center",
    "description": "Updated description with expanded services",
    "phone": "+1234567892",
    "address": "456 Oak Ave, Suite 200, City, State 12345",
    "website": "https://wellnesscenter.com",
    "services": ["massage_therapy", "acupuncture", "nutrition_counseling", "mental_health"],
    "is_active": true
  }'
```

**Response Example:**

```json
{
  "success": true,
  "message": "Clinic updated successfully",
  "data": {
    "id": 2,
    "name": "Wellness & Therapy Center",
    "email": "info@wellnesscenter.com",
    "description": "Updated description with expanded services",
    "logo": "http://127.0.0.1:8000/storage/images/uuid-generated-filename.png",
    "phone": "+1234567892",
    "address": "456 Oak Ave, Suite 200, City, State 12345",
    "website": "https://wellnesscenter.com",
    "services": ["massage_therapy", "acupuncture", "nutrition_counseling", "mental_health"],
    "is_active": true,
    "created_at": "2024-01-15T11:00:00.000000Z",
    "updated_at": "2024-01-15T12:00:00.000000Z"
  }
}
```

### **6. Delete Clinic (Admin Only)**

**Endpoint:** `DELETE /api/v1/clinics/{id}`

**Description:** Delete a clinic profile

**Authentication:** Required (Admin token)

**Request Example:**

```bash
curl -X DELETE "http://127.0.0.1:8000/api/v1/clinics/2" \
  -H "Authorization: Bearer {admin-token}" \
  -H "Accept: application/json"
```

**Response:** `204 No Content`

### **7. List All Clinics (Admin Only)**

**Endpoint:** `GET /api/v1/clinics`

**Description:** Retrieve all clinics including inactive ones (Admin access required)

**Authentication:** Required (Admin token)

**Query Parameters:**

- `page` (optional): Page number for pagination
- `per_page` (optional): Number of items per page (default: 15)
- `search` (optional): Search by name or services
- `service` (optional): Filter by service type
- `location` (optional): Filter by location/address
- `status` (optional): Filter by active status (`active`, `inactive`, `all`)

**Request Example:**

```bash
curl -X GET "http://127.0.0.1:8000/api/v1/clinics?status=all&per_page=20" \
  -H "Authorization: Bearer {admin-token}" \
  -H "Accept: application/json"
```

**Response Example:**

```json
{
  "data": [
    {
      "id": 1,
      "name": "HealthPlus Physical Therapy",
      "email": "info@healthplus.com",
      "description": "Comprehensive physical therapy and rehabilitation services",
      "logo": "https://example.com/images/clinic1.jpg",
      "phone": "+1234567890",
      "address": "123 Main St, City, State 12345",
      "website": "https://healthplus.com",
      "services": ["physical_therapy", "rehabilitation", "sports_medicine"],
      "is_active": true,
      "created_at": "2024-01-15T10:30:00.000000Z",
      "updated_at": "2024-01-15T10:30:00.000000Z"
    },
    {
      "id": 2,
      "name": "Wellness & Therapy Center",
      "email": "info@wellnesscenter.com",
      "description": "Updated description with expanded services",
      "logo": "http://127.0.0.1:8000/storage/images/uuid-generated-filename.png",
      "phone": "+1234567892",
      "address": "456 Oak Ave, Suite 200, City, State 12345",
      "website": "https://wellnesscenter.com",
      "services": ["massage_therapy", "acupuncture", "nutrition_counseling", "mental_health"],
      "is_active": false,
      "created_at": "2024-01-15T11:00:00.000000Z",
      "updated_at": "2024-01-15T12:00:00.000000Z"
    }
  ],
  "current_page": 1,
  "per_page": 20,
  "total": 2,
  "last_page": 1
}
```

## 📝 **Validation Rules**

### **Coach Validation:**

- `name`: Required, string, max 255 characters
- `email`: Required, valid email, max 255 characters, unique
- `password`: Required, string, minimum 8 characters
- `bio`: Optional, string
- `profile_image`: Optional, string (URL) or file upload
- `specializations`: Optional, array of strings
- `certifications`: Optional, array of strings
- `phone`: Optional, string
- `is_active`: Optional, boolean

### **Clinic Validation:**

- `name`: Required, string, max 255 characters
- `email`: Required, valid email, max 255 characters, unique
- `password`: Required, string, minimum 8 characters
- `description`: Optional, string
- `logo`: Optional, string (URL) or file upload
- `phone`: Optional, string
- `address`: Optional, string
- `website`: Optional, valid URL
- `services`: Optional, array of strings
- `is_active`: Optional, boolean

## 🖼️ **Image Upload Guidelines**

### **Supported Formats:**

- JPEG (.jpg, .jpeg)
- PNG (.png)
- GIF (.gif)
- WebP (.webp)

### **File Size Limits:**

- Maximum file size: 10MB per image

### **Image Storage:**

- Images are stored in `storage/app/public/images/`
- URLs are accessible via `http://your-domain/storage/images/filename`
- Filenames are automatically generated using UUID for uniqueness

## 🔧 **Frontend Implementation Examples**

### **JavaScript Fetch API Example:**

```javascript
// Create coach with image upload
const createCoach = async (coachData, imageFile) => {
  const formData = new FormData();

  // Add text fields
  Object.keys(coachData).forEach((key) => {
    if (Array.isArray(coachData[key])) {
      coachData[key].forEach((item) => formData.append(`${key}[]`, item));
    } else {
      formData.append(key, coachData[key]);
    }
  });

  // Add image file
  if (imageFile) {
    formData.append('profile_image', imageFile);
  }

  try {
    const response = await fetch('/api/v1/coaches', {
      method: 'POST',
      headers: {
        Authorization: `Bearer ${adminToken}`,
        Accept: 'application/json',
      },
      credentials: 'include',
      body: formData,
    });

    const data = await response.json();
    return data;
  } catch (error) {
    console.error('Error creating coach:', error);
    throw error;
  }
};

// Usage
const coachData = {
  name: 'John Doe',
  email: 'john@example.com',
  password: 'password123',
  bio: 'Experienced trainer',
  specializations: ['weight_loss', 'muscle_gain'],
  certifications: ['NASM-CPT'],
  phone: '+1234567890',
  is_active: true,
};

const imageFile = document.getElementById('profileImage').files[0];
createCoach(coachData, imageFile);
```

### **React Example:**

```jsx
import React, { useState } from 'react';

const CoachForm = () => {
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    password: '',
    bio: '',
    specializations: [],
    certifications: [],
    phone: '',
    is_active: true,
  });
  const [imageFile, setImageFile] = useState(null);
  const [loading, setLoading] = useState(false);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);

    const formDataToSend = new FormData();

    // Add form data
    Object.keys(formData).forEach((key) => {
      if (Array.isArray(formData[key])) {
        formData[key].forEach((item) => formDataToSend.append(`${key}[]`, item));
      } else {
        formDataToSend.append(key, formData[key]);
      }
    });

    // Add image
    if (imageFile) {
      formDataToSend.append('profile_image', imageFile);
    }

    try {
      const response = await fetch('/api/v1/coaches', {
        method: 'POST',
        headers: {
          Authorization: `Bearer ${localStorage.getItem('adminToken')}`,
          Accept: 'application/json',
        },
        credentials: 'include',
        body: formDataToSend,
      });

      const result = await response.json();

      if (response.ok) {
        console.log('Coach created successfully:', result);
        // Handle success
      } else {
        console.error('Error:', result);
        // Handle error
      }
    } catch (error) {
      console.error('Network error:', error);
    } finally {
      setLoading(false);
    }
  };

  return (
    <form onSubmit={handleSubmit}>
      <input
        type="text"
        placeholder="Coach Name"
        value={formData.name}
        onChange={(e) => setFormData({ ...formData, name: e.target.value })}
        required
      />

      <input
        type="email"
        placeholder="Email"
        value={formData.email}
        onChange={(e) => setFormData({ ...formData, email: e.target.value })}
        required
      />

      <input
        type="password"
        placeholder="Password"
        value={formData.password}
        onChange={(e) => setFormData({ ...formData, password: e.target.value })}
        required
      />

      <textarea
        placeholder="Bio"
        value={formData.bio}
        onChange={(e) => setFormData({ ...formData, bio: e.target.value })}
      />

      <input type="file" accept="image/*" onChange={(e) => setImageFile(e.target.files[0])} />

      <button type="submit" disabled={loading}>
        {loading ? 'Creating...' : 'Create Coach'}
      </button>
    </form>
  );
};
```

## ⚠️ **Error Handling**

### **Common Error Responses:**

**422 Validation Error:**

```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "email": ["The email has already been taken."],
    "password": ["The password must be at least 8 characters."]
  }
}
```

**403 Forbidden (Admin Access Required):**

```json
{
  "error": "Admin access required"
}
```

**404 Not Found:**

```json
{
  "message": "No query results for model [App\\Models\\Coach] 1"
}
```

**500 Server Error:**

```json
{
  "success": false,
  "message": "Failed to create coach: Database connection failed"
}
```

## 🧪 **Testing Endpoints**

### **Test Admin Login:**

```bash
curl -X POST "http://127.0.0.1:8000/api/v1/admin-login" \
  -H "Content-Type: application/json" \
  -d '{"email": "admin@example.com", "password": "adminpassword"}'
```

### **Test Coach Creation:**

```bash
curl -X POST "http://127.0.0.1:8000/api/v1/coaches" \
  -H "Authorization: Bearer {your-admin-token}" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test Coach",
    "email": "test@coach.com",
    "password": "password123",
    "bio": "Test bio",
    "specializations": ["test"],
    "certifications": ["test-cert"],
    "phone": "+1234567890",
    "is_active": true
  }'
```

### **Test Clinic Creation:**

```bash
curl -X POST "http://127.0.0.1:8000/api/v1/clinics" \
  -H "Authorization: Bearer {your-admin-token}" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test Clinic",
    "email": "test@clinic.com",
    "password": "password123",
    "description": "Test description",
    "phone": "+1234567890",
    "address": "Test Address",
    "website": "https://test.com",
    "services": ["test_service"],
    "is_active": true
  }'
```

## 📊 **Pagination**

All list endpoints support pagination with the following parameters:

- `page`: Page number (default: 1)
- `per_page`: Items per page (default: 15, max: 100)

**Pagination Response Format:**

```json
{
  "data": [...],
  "current_page": 1,
  "per_page": 15,
  "total": 50,
  "last_page": 4,
  "from": 1,
  "to": 15
}
```

## 🔍 **Search and Filtering**

### **Coach Search Parameters:**

- `search`: Search by name or specializations
- `specialization`: Filter by specific specialization
- `status`: Filter by active status (`active`, `inactive`, `all`)

### **Clinic Search Parameters:**

- `search`: Search by name or services
- `service`: Filter by specific service
- `location`: Filter by location/address
- `status`: Filter by active status (`active`, `inactive`, `all`)

---

## 📞 **Support**

For any questions or issues with these endpoints, please refer to the main API documentation or contact the development team.

**Base URL:** `http://127.0.0.1:8000/api/v1`

**Admin Endpoints:** All admin endpoints require authentication with an admin token obtained from `/api/v1/admin-login`
