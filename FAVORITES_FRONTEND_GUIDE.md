# ❤️ Favorites API - Frontend Integration Guide

## 📱 **Overview**

The Favorites API allows users to save and manage their favorite **Articles** and **Workouts** in the mobile app. This guide provides everything a frontend engineer needs to integrate the favorites functionality.

## 🔐 **Authentication**

All endpoints require user authentication via Bearer token:

```javascript
const headers = {
  Authorization: `Bearer ${userToken}`,
  'Content-Type': 'application/json',
};
```

## 🚀 **API Endpoints**

### **1. Get User's Favorites**

**Endpoint:** `GET /api/v1/user-favorites`

**Description:** Retrieves all favorites for the authenticated user

**Response:**

```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "type": "workout",
      "item": {
        "id": 1,
        "name": "Morning Cardio",
        "description": "Start your day with energy",
        "image_url": "workout_image.jpg",
        "difficulty": "beginner",
        "type": "cardio",
        "duration_minutes": 30,
        "calories_per_session": 250,
        "equipment_needed": ["mat"],
        "tags": ["morning", "cardio", "beginner"],
        "is_featured": true,
        "is_active": true,
        "created_by_admin": 1,
        "created_at": "2025-01-27T08:00:00Z",
        "updated_at": "2025-01-27T08:00:00Z",
        "exercises": [
          {
            "id": 1,
            "name": "Jumping Jacks",
            "description": "Full body cardio exercise",
            "sets": 3,
            "reps": 20,
            "duration_seconds": 30,
            "rest_seconds": 15,
            "order": 1
          }
        ],
        "total_exercises": 1,
        "total_sets": 3,
        "estimated_duration": 45
      },
      "created_at": "2025-01-27T10:00:00Z"
    },
    {
      "id": 2,
      "type": "education_content",
      "item": {
        "id": 5,
        "coverImage": "article_image.jpg",
        "title": "The Science Behind Effective Training Splits",
        "description": "Learn how to structure your weekly workouts for optimal results and recovery.",
        "sections": [
          {
            "title": "Introduction",
            "content": "Understanding training splits..."
          }
        ],
        "category": "training",
        "tags": ["training", "splits", "science"],
        "is_featured": true,
        "created_at": "2025-01-27T09:00:00Z",
        "updated_at": "2025-01-27T09:00:00Z"
      },
      "created_at": "2025-01-27T09:30:00Z"
    }
  ],
  "count": 2
}
```

**Frontend Implementation:**

```javascript
const getFavorites = async () => {
  try {
    const response = await fetch('/api/v1/user-favorites', {
      method: 'GET',
      headers: {
        Authorization: `Bearer ${userToken}`,
        'Content-Type': 'application/json',
      },
    });

    const data = await response.json();

    if (data.status === 'success') {
      return data.data;
    } else {
      throw new Error(data.message);
    }
  } catch (error) {
    console.error('Error fetching favorites:', error);
    throw error;
  }
};
```

### **2. Add to Favorites**

**Endpoint:** `POST /api/v1/user-favorites`

**Description:** Adds an item (workout or article) to user's favorites

**Request Body:**

```json
{
  "favoritable_type": "workout", // or "education_content"
  "favoritable_id": 1
}
```

**Response:**

```json
{
  "status": "success",
  "message": "Added to favorites",
  "data": {
    "id": 3,
    "user_id": 1,
    "favoritable_type": "workout",
    "favoritable_id": 1,
    "created_at": "2025-01-27T11:00:00Z",
    "updated_at": "2025-01-27T11:00:00Z"
  }
}
```

**Frontend Implementation:**

```javascript
const addToFavorites = async (type, itemId) => {
  try {
    const response = await fetch('/api/v1/user-favorites', {
      method: 'POST',
      headers: {
        Authorization: `Bearer ${userToken}`,
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        favoritable_type: type, // "workout" or "education_content"
        favoritable_id: itemId,
      }),
    });

    const data = await response.json();

    if (data.status === 'success') {
      return data.data;
    } else {
      throw new Error(data.message);
    }
  } catch (error) {
    console.error('Error adding to favorites:', error);
    throw error;
  }
};
```

### **3. Remove from Favorites (by Item)**

**Endpoint:** `POST /api/v1/user-favorites/remove-by-item`

**Description:** Removes an item from favorites using item type and ID (recommended for mobile)

**Request Body:**

```json
{
  "favoritable_type": "workout",
  "favoritable_id": 1
}
```

**Response:**

```json
{
  "status": "success",
  "message": "Removed from favorites"
}
```

**Frontend Implementation:**

```javascript
const removeFromFavorites = async (type, itemId) => {
  try {
    const response = await fetch('/api/v1/user-favorites/remove-by-item', {
      method: 'POST',
      headers: {
        Authorization: `Bearer ${userToken}`,
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        favoritable_type: type,
        favoritable_id: itemId,
      }),
    });

    const data = await response.json();

    if (data.status === 'success') {
      return true;
    } else {
      throw new Error(data.message);
    }
  } catch (error) {
    console.error('Error removing from favorites:', error);
    throw error;
  }
};
```

### **4. Remove from Favorites (by Favorite ID)**

**Endpoint:** `DELETE /api/v1/user-favorites/{id}`

**Description:** Removes a favorite using the favorite's ID

**Frontend Implementation:**

```javascript
const removeFavoriteById = async (favoriteId) => {
  try {
    const response = await fetch(`/api/v1/user-favorites/${favoriteId}`, {
      method: 'DELETE',
      headers: {
        Authorization: `Bearer ${userToken}`,
        'Content-Type': 'application/json',
      },
    });

    const data = await response.json();

    if (data.status === 'success') {
      return true;
    } else {
      throw new Error(data.message);
    }
  } catch (error) {
    console.error('Error removing favorite:', error);
    throw error;
  }
};
```

## 🎨 **UI Implementation Examples**

### **Heart Icon Toggle Component**

```javascript
import React, { useState, useEffect } from 'react';
import { TouchableOpacity, Text } from 'react-native';

const FavoriteButton = ({ type, itemId, itemTitle }) => {
  const [isFavorited, setIsFavorited] = useState(false);
  const [isLoading, setIsLoading] = useState(false);

  // Check if item is already favorited
  useEffect(() => {
    checkFavoriteStatus();
  }, [type, itemId]);

  const checkFavoriteStatus = async () => {
    try {
      const favorites = await getFavorites();
      const isFav = favorites.some((fav) => fav.type === type && fav.item.id === itemId);
      setIsFavorited(isFav);
    } catch (error) {
      console.error('Error checking favorite status:', error);
    }
  };

  const toggleFavorite = async () => {
    if (isLoading) return;

    setIsLoading(true);

    try {
      if (isFavorited) {
        await removeFromFavorites(type, itemId);
        setIsFavorited(false);
        // Show success message
        showToast(`${itemTitle} removed from favorites`);
      } else {
        await addToFavorites(type, itemId);
        setIsFavorited(true);
        // Show success message
        showToast(`${itemTitle} added to favorites`);
      }
    } catch (error) {
      // Show error message
      showToast('Failed to update favorites');
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <TouchableOpacity
      onPress={toggleFavorite}
      disabled={isLoading}
      style={{
        padding: 8,
        opacity: isLoading ? 0.5 : 1,
      }}
    >
      <Text
        style={{
          fontSize: 20,
          color: isFavorited ? '#FF6B6B' : '#CCCCCC',
        }}
      >
        {isFavorited ? '❤️' : '🤍'}
      </Text>
    </TouchableOpacity>
  );
};

export default FavoriteButton;
```

### **Favorites Screen Component**

```javascript
import React, { useState, useEffect } from 'react';
import { View, Text, FlatList, TouchableOpacity, Image } from 'react-native';

const FavoritesScreen = () => {
  const [favorites, setFavorites] = useState([]);
  const [loading, setLoading] = useState(true);
  const [selectedTab, setSelectedTab] = useState('all'); // 'all', 'workout', 'education_content'

  useEffect(() => {
    loadFavorites();
  }, []);

  const loadFavorites = async () => {
    try {
      setLoading(true);
      const data = await getFavorites();
      setFavorites(data);
    } catch (error) {
      console.error('Error loading favorites:', error);
    } finally {
      setLoading(false);
    }
  };

  const filteredFavorites = favorites.filter((fav) => {
    if (selectedTab === 'all') return true;
    return fav.type === selectedTab;
  });

  const renderFavoriteItem = ({ item }) => (
    <TouchableOpacity style={styles.favoriteCard}>
      <Image
        source={{
          uri: item.type === 'workout' ? item.item.image_url : item.item.coverImage,
        }}
        style={styles.itemImage}
      />
      <View style={styles.itemContent}>
        <Text style={styles.itemTitle}>
          {item.type === 'workout' ? item.item.name : item.item.title}
        </Text>
        <Text style={styles.itemDescription}>{item.item.description}</Text>
        <Text style={styles.itemType}>{item.type === 'workout' ? '🏋️ Workout' : '📚 Article'}</Text>
        {item.type === 'workout' && (
          <Text style={styles.workoutDetails}>
            {item.item.difficulty} • {item.item.duration_minutes}min • {item.item.total_exercises}{' '}
            exercises
          </Text>
        )}
      </View>
      <FavoriteButton
        type={item.type}
        itemId={item.item.id}
        itemTitle={item.type === 'workout' ? item.item.name : item.item.title}
        onToggle={loadFavorites}
      />
    </TouchableOpacity>
  );

  return (
    <View style={styles.container}>
      {/* Header */}
      <View style={styles.header}>
        <Text style={styles.headerTitle}>Favorites</Text>
      </View>

      {/* Tab Selector */}
      <View style={styles.tabContainer}>
        <TouchableOpacity
          style={[styles.tab, selectedTab === 'all' && styles.activeTab]}
          onPress={() => setSelectedTab('all')}
        >
          <Text style={[styles.tabText, selectedTab === 'all' && styles.activeTabText]}>
            All ({favorites.length})
          </Text>
        </TouchableOpacity>
        <TouchableOpacity
          style={[styles.tab, selectedTab === 'education_content' && styles.activeTab]}
          onPress={() => setSelectedTab('education_content')}
        >
          <Text
            style={[styles.tabText, selectedTab === 'education_content' && styles.activeTabText]}
          >
            Articles ({favorites.filter((f) => f.type === 'education_content').length})
          </Text>
        </TouchableOpacity>
        <TouchableOpacity
          style={[styles.tab, selectedTab === 'workout' && styles.activeTab]}
          onPress={() => setSelectedTab('workout')}
        >
          <Text style={[styles.tabText, selectedTab === 'workout' && styles.activeTabText]}>
            Workouts ({favorites.filter((f) => f.type === 'workout').length})
          </Text>
        </TouchableOpacity>
      </View>

      {/* Favorites List */}
      <FlatList
        data={filteredFavorites}
        renderItem={renderFavoriteItem}
        keyExtractor={(item) => item.id.toString()}
        refreshing={loading}
        onRefresh={loadFavorites}
        ListEmptyComponent={
          <View style={styles.emptyState}>
            <Text style={styles.emptyText}>No favorites yet</Text>
            <Text style={styles.emptySubtext}>Start adding items to your favorites!</Text>
          </View>
        }
      />
    </View>
  );
};

const styles = {
  container: {
    flex: 1,
    backgroundColor: '#1A1A1A',
  },
  header: {
    padding: 20,
    backgroundColor: '#2ECC71',
  },
  headerTitle: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#FFFFFF',
  },
  tabContainer: {
    flexDirection: 'row',
    backgroundColor: '#2A2A2A',
    paddingHorizontal: 10,
    paddingVertical: 5,
  },
  tab: {
    flex: 1,
    paddingVertical: 10,
    paddingHorizontal: 15,
    borderRadius: 20,
    marginHorizontal: 5,
    alignItems: 'center',
  },
  activeTab: {
    backgroundColor: '#2ECC71',
  },
  tabText: {
    color: '#CCCCCC',
    fontSize: 14,
    fontWeight: '500',
  },
  activeTabText: {
    color: '#FFFFFF',
  },
  favoriteCard: {
    flexDirection: 'row',
    backgroundColor: '#2A2A2A',
    marginHorizontal: 15,
    marginVertical: 8,
    borderRadius: 12,
    padding: 15,
    alignItems: 'center',
  },
  itemImage: {
    width: 80,
    height: 80,
    borderRadius: 8,
    marginRight: 15,
  },
  itemContent: {
    flex: 1,
  },
  itemTitle: {
    fontSize: 16,
    fontWeight: 'bold',
    color: '#FFFFFF',
    marginBottom: 5,
  },
  itemDescription: {
    fontSize: 14,
    color: '#CCCCCC',
    marginBottom: 5,
  },
  itemType: {
    fontSize: 12,
    color: '#2ECC71',
  },
  workoutDetails: {
    fontSize: 12,
    color: '#888888',
    marginTop: 2,
  },
  emptyState: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    paddingVertical: 50,
  },
  emptyText: {
    fontSize: 18,
    color: '#CCCCCC',
    marginBottom: 10,
  },
  emptySubtext: {
    fontSize: 14,
    color: '#888888',
  },
};

export default FavoritesScreen;
```

## 🔄 **State Management**

### **Redux/Zustand Example**

```javascript
// Using Zustand for state management
import { create } from 'zustand';

const useFavoritesStore = create((set, get) => ({
  favorites: [],
  loading: false,

  // Actions
  loadFavorites: async () => {
    set({ loading: true });
    try {
      const favorites = await getFavorites();
      set({ favorites, loading: false });
    } catch (error) {
      set({ loading: false });
      throw error;
    }
  },

  addFavorite: async (type, itemId) => {
    try {
      await addToFavorites(type, itemId);
      await get().loadFavorites(); // Refresh the list
    } catch (error) {
      throw error;
    }
  },

  removeFavorite: async (type, itemId) => {
    try {
      await removeFromFavorites(type, itemId);
      await get().loadFavorites(); // Refresh the list
    } catch (error) {
      throw error;
    }
  },

  isFavorited: (type, itemId) => {
    const { favorites } = get();
    return favorites.some((fav) => fav.type === type && fav.item.id === itemId);
  },
}));
```

## ⚠️ **Error Handling**

### **Common Error Responses**

```javascript
// 422 - Validation Error
{
  "status": "error",
  "message": "Validation failed",
  "errors": {
    "favoritable_type": ["The favoritable type field is required."]
  }
}

// 404 - Item Not Found
{
  "status": "error",
  "message": "Workout not found"
}

// 409 - Already Favorited
{
  "status": "error",
  "message": "Item already in favorites"
}

// 500 - Server Error
{
  "status": "error",
  "message": "Failed to add to favorites",
  "error": "Database connection failed"
}
```

### **Error Handling Utility**

```javascript
const handleApiError = (error, defaultMessage = 'Something went wrong') => {
  if (error.response) {
    const { status, data } = error.response;

    switch (status) {
      case 401:
        return 'Please log in again';
      case 404:
        return data.message || 'Item not found';
      case 409:
        return data.message || 'Already in favorites';
      case 422:
        return 'Invalid data provided';
      case 500:
        return 'Server error. Please try again later';
      default:
        return data.message || defaultMessage;
    }
  }

  return error.message || defaultMessage;
};
```

## 🧪 **Testing**

### **Test Script**

Use the provided test script to verify API functionality:

```bash
# Make sure to replace YOUR_TOKEN with actual token
./test_favorites_api.sh
```

### **Manual Testing Checklist**

- [ ] Get favorites returns user-specific data only
- [ ] Add workout to favorites works
- [ ] Add article to favorites works
- [ ] Duplicate favorites are prevented
- [ ] Remove by item type/ID works
- [ ] Remove by favorite ID works
- [ ] Authentication is required for all endpoints
- [ ] Error handling works for invalid data

## 📱 **Mobile App Integration Tips**

1. **Offline Support**: Cache favorites locally for offline viewing
2. **Optimistic Updates**: Update UI immediately, sync with server
3. **Pull to Refresh**: Allow users to refresh favorites list
4. **Search**: Implement search within favorites
5. **Categories**: Use the tab system to filter by type
6. **Empty States**: Show helpful messages when no favorites exist

## 🎯 **Next Steps**

1. Implement the FavoriteButton component in your workout/article cards
2. Create the FavoritesScreen with tab navigation
3. Add favorites to your navigation menu
4. Test thoroughly with real data
5. Add analytics to track favorite usage

---

**Need Help?** Check the test script or contact the backend team for any API-related questions.
