# 📊 Progress Tracker API - Frontend Integration Guide

## 📱 **Overview**

The Progress Tracker API provides endpoints to manage and display user's daily health and fitness metrics including sleep time, calories, protein, carbs goals, steps, and overall daily progress. This guide covers everything needed to integrate the Progress Tracker screens into your mobile app.

## 🔐 **Authentication**

All endpoints require user authentication via Bearer token:

```javascript
const headers = {
  Authorization: `Bearer ${userToken}`,
  'Content-Type': 'application/json',
};
```

## 🚀 **API Endpoints**

### **1. Get Progress Summary**

**Endpoint:** `GET /api/v1/daily-activities/progress-summary`

**Description:** Retrieves comprehensive progress data for the authenticated user for a specific date

**Query Parameters:**

- `date` (optional): Date in YYYY-MM-DD format (defaults to today)

**Response:**

```json
{
  "status": "success",
  "data": {
    "date": "2025-01-27",
    "daily_progress": {
      "percentage": 80,
      "description": "Accumulating daily report"
    },
    "steps": {
      "current": 8500,
      "goal": 10000,
      "progress_percentage": 85
    },
    "calories_burned": {
      "current": 450,
      "goal": 500,
      "progress_percentage": 90
    },
    "sleep": {
      "current_hours": 7,
      "goal_hours": 8,
      "progress_percentage": 87.5
    },
    "calories_goal": {
      "current": 1600,
      "goal": 2145,
      "progress_percentage": 74.6
    },
    "protein_goal": {
      "current": 1600,
      "goal": 2145,
      "progress_percentage": 74.6
    },
    "carbs_goal": {
      "current": 1600,
      "goal": 2145,
      "progress_percentage": 74.6
    }
  }
}
```

**Frontend Implementation:**

```javascript
const getProgressSummary = async (date = null) => {
  try {
    const url = date
      ? `/api/v1/daily-activities/progress-summary?date=${date}`
      : '/api/v1/daily-activities/progress-summary';

    const response = await fetch(url, {
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
    console.error('Error fetching progress summary:', error);
    throw error;
  }
};
```

### **2. Update Daily Activity**

**Endpoint:** `POST /api/v1/daily-activities/update`

**Description:** Updates daily activity metrics for the authenticated user

**Request Body:**

```json
{
  "date": "2025-01-27",
  "steps": 8500,
  "calories_consumed": 1600,
  "calories_burned": 450,
  "water_intake": 2.5,
  "sleep_time": 7.5,
  "daily_progress_percentage": 80,
  "protein_goal": 2145,
  "carbs_goal": 2145
}
```

**Response:**

```json
{
  "status": "success",
  "message": "Daily activity updated successfully",
  "data": {
    "id": 1,
    "user_id": 1,
    "date": "2025-01-27",
    "steps": 8500,
    "calories_consumed": 1600,
    "calories_burned": 450,
    "water_intake": 2.5,
    "sleep_time": 7.5,
    "daily_progress_percentage": 80,
    "protein_goal": 2145,
    "carbs_goal": 2145,
    "created_at": "2025-01-27T10:00:00Z",
    "updated_at": "2025-01-27T15:30:00Z"
  }
}
```

**Frontend Implementation:**

```javascript
const updateDailyActivity = async (activityData) => {
  try {
    const response = await fetch('/api/v1/daily-activities/update', {
      method: 'POST',
      headers: {
        Authorization: `Bearer ${userToken}`,
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(activityData),
    });

    const data = await response.json();

    if (data.status === 'success') {
      return data.data;
    } else {
      throw new Error(data.message);
    }
  } catch (error) {
    console.error('Error updating daily activity:', error);
    throw error;
  }
};
```

### **3. Get Date Range Activities**

**Endpoint:** `GET /api/v1/daily-activities/date-range`

**Description:** Retrieves daily activities for a date range (useful for weekly/monthly views)

**Query Parameters:**

- `start_date` (optional): Start date in YYYY-MM-DD format (defaults to 7 days ago)
- `end_date` (optional): End date in YYYY-MM-DD format (defaults to today)

**Response:**

```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "user_id": 1,
      "date": "2025-01-27",
      "steps": 8500,
      "calories_consumed": 1600,
      "calories_burned": 450,
      "water_intake": 2.5,
      "sleep_time": 7.5,
      "daily_progress_percentage": 80,
      "protein_goal": 2145,
      "carbs_goal": 2145,
      "created_at": "2025-01-27T10:00:00Z",
      "updated_at": "2025-01-27T15:30:00Z"
    }
  ],
  "count": 1
}
```

**Frontend Implementation:**

```javascript
const getDateRangeActivities = async (startDate, endDate) => {
  try {
    const params = new URLSearchParams();
    if (startDate) params.append('start_date', startDate);
    if (endDate) params.append('end_date', endDate);

    const response = await fetch(`/api/v1/daily-activities/date-range?${params}`, {
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
    console.error('Error fetching date range activities:', error);
    throw error;
  }
};
```

## 🎨 **UI Implementation Examples**

### **Progress Tracker Screen Component**

```javascript
import React, { useState, useEffect } from 'react';
import { View, Text, ScrollView, TouchableOpacity, RefreshControl } from 'react-native';

const ProgressTrackerScreen = () => {
  const [progressData, setProgressData] = useState(null);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);
  const [selectedDate, setSelectedDate] = useState(new Date().toISOString().split('T')[0]);

  useEffect(() => {
    loadProgressData();
  }, [selectedDate]);

  const loadProgressData = async () => {
    try {
      setLoading(true);
      const data = await getProgressSummary(selectedDate);
      setProgressData(data);
    } catch (error) {
      console.error('Error loading progress data:', error);
    } finally {
      setLoading(false);
    }
  };

  const onRefresh = async () => {
    setRefreshing(true);
    await loadProgressData();
    setRefreshing(false);
  };

  const updateMetric = async (metric, value) => {
    try {
      const updateData = { [metric]: value };
      await updateDailyActivity(updateData);
      await loadProgressData(); // Refresh data
    } catch (error) {
      console.error('Error updating metric:', error);
    }
  };

  const renderProgressCard = (title, data, icon, color) => (
    <View style={[styles.progressCard, { backgroundColor: color }]}>
      <View style={styles.cardHeader}>
        <Text style={styles.cardIcon}>{icon}</Text>
        <Text style={styles.cardTitle}>{title}</Text>
      </View>
      <View style={styles.cardContent}>
        <Text style={styles.cardValue}>{data.current}</Text>
        <Text style={styles.cardUnit}>
          {title.includes('Sleep') ? 'hours' : title.includes('Steps') ? 'steps' : 'Kcal'}
        </Text>
      </View>
      <View style={styles.progressIndicator}>
        <View style={styles.progressCircle}>
          <Text style={styles.progressText}>{Math.round(data.progress_percentage)}%</Text>
        </View>
      </View>
    </View>
  );

  if (loading) {
    return (
      <View style={styles.loadingContainer}>
        <Text style={styles.loadingText}>Loading progress data...</Text>
      </View>
    );
  }

  return (
    <ScrollView
      style={styles.container}
      refreshControl={<RefreshControl refreshing={refreshing} onRefresh={onRefresh} />}
    >
      {/* Header */}
      <View style={styles.header}>
        <TouchableOpacity style={styles.backButton}>
          <Text style={styles.backIcon}>←</Text>
        </TouchableOpacity>
        <Text style={styles.headerTitle}>Progress Tracker</Text>
        <TouchableOpacity style={styles.notificationButton}>
          <Text style={styles.notificationIcon}>🔔</Text>
        </TouchableOpacity>
      </View>

      {/* Date Selector */}
      <View style={styles.dateSelector}>
        <ScrollView horizontal showsHorizontalScrollIndicator={false}>
          {generateDateButtons().map((dateBtn) => (
            <TouchableOpacity
              key={dateBtn.date}
              style={[styles.dateButton, dateBtn.isSelected && styles.selectedDateButton]}
              onPress={() => setSelectedDate(dateBtn.date)}
            >
              <Text
                style={[styles.dateButtonText, dateBtn.isSelected && styles.selectedDateButtonText]}
              >
                {dateBtn.day}
              </Text>
              <Text
                style={[
                  styles.dateButtonNumber,
                  dateBtn.isSelected && styles.selectedDateButtonNumber,
                ]}
              >
                {dateBtn.number}
              </Text>
            </TouchableOpacity>
          ))}
        </ScrollView>
      </View>

      {/* Daily Progress Card */}
      <View style={[styles.progressCard, styles.dailyProgressCard]}>
        <Text style={styles.dailyProgressTitle}>Your daily progress</Text>
        <View style={styles.dailyProgressCircle}>
          <Text style={styles.dailyProgressPercentage}>
            {progressData?.daily_progress?.percentage || 0}%
          </Text>
        </View>
        <Text style={styles.dailyProgressDescription}>
          {progressData?.daily_progress?.description || 'Accumulating daily report'}
        </Text>
      </View>

      {/* Metrics Cards */}
      <View style={styles.metricsContainer}>
        <View style={styles.metricsRow}>
          {renderProgressCard(
            'Steps walk',
            progressData?.steps || { current: 0, progress_percentage: 0 },
            '🏃',
            '#FFE066'
          )}
          {renderProgressCard(
            'Kcal Burned',
            progressData?.calories_burned || { current: 0, progress_percentage: 0 },
            '🔥',
            '#FFE066'
          )}
        </View>
      </View>

      {/* Sleep Time Card */}
      {renderProgressCard(
        'Sleep Time',
        progressData?.sleep || { current_hours: 0, progress_percentage: 0 },
        '🕐',
        '#2ECC71'
      )}

      {/* Macro Goals Section */}
      <View style={styles.macroSection}>
        <Text style={styles.sectionTitle}>Overview</Text>

        {renderProgressCard(
          'Calories Goal',
          progressData?.calories_goal || { current: 0, goal: 0, progress_percentage: 0 },
          '🔥',
          '#2ECC71'
        )}

        {renderProgressCard(
          'Protein Goal',
          progressData?.protein_goal || { current: 0, goal: 0, progress_percentage: 0 },
          '🔥',
          '#2ECC71'
        )}

        {renderProgressCard(
          'Carbs Goal',
          progressData?.carbs_goal || { current: 0, goal: 0, progress_percentage: 0 },
          '🔥',
          '#2ECC71'
        )}
      </View>
    </ScrollView>
  );
};

const generateDateButtons = () => {
  const dates = [];
  const today = new Date();

  for (let i = -3; i <= 3; i++) {
    const date = new Date(today);
    date.setDate(today.getDate() + i);

    dates.push({
      date: date.toISOString().split('T')[0],
      day: date.toLocaleDateString('en', { weekday: 'short' }).charAt(0),
      number: date.getDate().toString().padStart(2, '0'),
      isSelected: i === 0,
    });
  }

  return dates;
};

const styles = {
  container: {
    flex: 1,
    backgroundColor: '#1A1A1A',
  },
  loadingContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: '#1A1A1A',
  },
  loadingText: {
    color: '#FFFFFF',
    fontSize: 16,
  },
  header: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    paddingHorizontal: 20,
    paddingVertical: 15,
    backgroundColor: '#2ECC71',
  },
  backButton: {
    padding: 8,
  },
  backIcon: {
    fontSize: 20,
    color: '#FFFFFF',
  },
  headerTitle: {
    fontSize: 20,
    fontWeight: 'bold',
    color: '#FFFFFF',
  },
  notificationButton: {
    padding: 8,
  },
  notificationIcon: {
    fontSize: 20,
    color: '#FFFFFF',
  },
  dateSelector: {
    paddingVertical: 15,
    backgroundColor: '#2A2A2A',
  },
  dateButton: {
    alignItems: 'center',
    paddingHorizontal: 15,
    paddingVertical: 10,
    marginHorizontal: 5,
    borderRadius: 20,
    backgroundColor: '#3A3A3A',
  },
  selectedDateButton: {
    backgroundColor: '#2ECC71',
  },
  dateButtonText: {
    color: '#CCCCCC',
    fontSize: 12,
    fontWeight: '500',
  },
  selectedDateButtonText: {
    color: '#FFFFFF',
  },
  dateButtonNumber: {
    color: '#CCCCCC',
    fontSize: 14,
    fontWeight: 'bold',
    marginTop: 2,
  },
  selectedDateButtonNumber: {
    color: '#FFFFFF',
  },
  progressCard: {
    backgroundColor: '#2ECC71',
    marginHorizontal: 15,
    marginVertical: 8,
    borderRadius: 12,
    padding: 20,
    flexDirection: 'row',
    alignItems: 'center',
  },
  dailyProgressCard: {
    backgroundColor: '#2ECC71',
    flexDirection: 'column',
    alignItems: 'center',
    paddingVertical: 30,
  },
  dailyProgressTitle: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#FFFFFF',
    marginBottom: 20,
  },
  dailyProgressCircle: {
    width: 120,
    height: 120,
    borderRadius: 60,
    backgroundColor: '#FFFFFF',
    justifyContent: 'center',
    alignItems: 'center',
    marginBottom: 15,
  },
  dailyProgressPercentage: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#2ECC71',
  },
  dailyProgressDescription: {
    fontSize: 14,
    color: '#FFFFFF',
    textAlign: 'center',
  },
  cardHeader: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 10,
  },
  cardIcon: {
    fontSize: 16,
    marginRight: 8,
  },
  cardTitle: {
    fontSize: 14,
    color: '#FFFFFF',
    fontWeight: '500',
  },
  cardContent: {
    flex: 1,
  },
  cardValue: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#FFFFFF',
    marginBottom: 5,
  },
  cardUnit: {
    fontSize: 12,
    color: '#FFFFFF',
    opacity: 0.8,
  },
  progressIndicator: {
    marginLeft: 15,
  },
  progressCircle: {
    width: 60,
    height: 60,
    borderRadius: 30,
    backgroundColor: '#FFFFFF',
    justifyContent: 'center',
    alignItems: 'center',
  },
  progressText: {
    fontSize: 12,
    fontWeight: 'bold',
    color: '#2ECC71',
  },
  metricsContainer: {
    paddingHorizontal: 15,
  },
  metricsRow: {
    flexDirection: 'row',
    justifyContent: 'space-between',
  },
  macroSection: {
    paddingHorizontal: 15,
    paddingBottom: 20,
  },
  sectionTitle: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#FFFFFF',
    marginBottom: 15,
    marginTop: 10,
  },
};

export default ProgressTrackerScreen;
```

## 🔄 **State Management**

### **Redux/Zustand Example**

```javascript
// Using Zustand for state management
import { create } from 'zustand';

const useProgressStore = create((set, get) => ({
  progressData: null,
  loading: false,
  selectedDate: new Date().toISOString().split('T')[0],

  // Actions
  loadProgressData: async (date = null) => {
    set({ loading: true });
    try {
      const data = await getProgressSummary(date || get().selectedDate);
      set({ progressData: data, loading: false });
    } catch (error) {
      set({ loading: false });
      throw error;
    }
  },

  updateMetric: async (metric, value) => {
    try {
      await updateDailyActivity({ [metric]: value });
      await get().loadProgressData(); // Refresh data
    } catch (error) {
      throw error;
    }
  },

  setSelectedDate: (date) => {
    set({ selectedDate: date });
    get().loadProgressData(date);
  },

  getProgressPercentage: (metric) => {
    const { progressData } = get();
    return progressData?.[metric]?.progress_percentage || 0;
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
    "steps": ["The steps field must be an integer."]
  }
}

// 500 - Server Error
{
  "status": "error",
  "message": "Failed to retrieve progress summary",
  "error": "Database connection failed"
}
```

### **Error Handling Utility**

```javascript
const handleProgressError = (error, defaultMessage = 'Something went wrong') => {
  if (error.response) {
    const { status, data } = error.response;

    switch (status) {
      case 401:
        return 'Please log in again';
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

```bash
#!/bin/bash

# Test script for Progress Tracker API endpoints
# Make sure to replace YOUR_TOKEN with actual authentication token


BASE_URL="http://localhost:8000/api/v1"
TOKEN="YOUR_TOKEN"

echo "🧪 Testing Progress Tracker API Endpoints"
echo "========================================"

# Test 1: Get progress summary
echo -e "\n1️⃣ Getting progress summary..."
curl -X GET "$BASE_URL/daily-activities/progress-summary" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -w "\nStatus: %{http_code}\n" | jq '.'

# Test 2: Update daily activity
echo -e "\n2️⃣ Updating daily activity..."
curl -X POST "$BASE_URL/daily-activities/update" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "steps": 8500,
    "calories_consumed": 1600,
    "calories_burned": 450,
    "sleep_time": 7.5,
    "daily_progress_percentage": 80
  }' \
  -w "\nStatus: %{http_code}\n" | jq '.'

# Test 3: Get date range activities
echo -e "\n3️⃣ Getting date range activities..."
curl -X GET "$BASE_URL/daily-activities/date-range?start_date=2025-01-20&end_date=2025-01-27" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -w "\nStatus: %{http_code}\n" | jq '.'

echo -e "\n✅ Progress Tracker API testing completed!"
echo "========================================"
```

## 📱 **Mobile App Integration Tips**

1. **Real-time Updates**: Use pull-to-refresh for manual updates
2. **Date Navigation**: Implement horizontal scrolling date selector
3. **Progress Visualization**: Use circular progress indicators
4. **Offline Support**: Cache progress data locally
5. **Goal Setting**: Allow users to update their daily goals
6. **Notifications**: Send reminders for goal completion
7. **Data Visualization**: Show trends over time with charts

## 🎯 **Next Steps**

1. Implement the ProgressTrackerScreen component
2. Add date picker functionality
3. Create progress visualization components
4. Add goal setting functionality
5. Implement data persistence
6. Add analytics and insights

---

**Need Help?** Check the test script or contact the backend team for any API-related questions.
