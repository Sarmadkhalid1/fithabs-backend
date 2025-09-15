# Dashboard API Endpoint Documentation

## Overview

This endpoint provides comprehensive dashboard data including financial metrics, user statistics, content metrics, and recent user activity for the FitHabs admin dashboard.

## API Endpoint

### Get Dashboard Data

```
GET /api/v1/dashboard
```

### Authentication

Requires Bearer token authentication:

```javascript
const headers = {
  Authorization: `Bearer ${adminToken}`,
  'Content-Type': 'application/json',
};
```

### Response Structure

```json
{
  "status": "success",
  "data": {
    "financial_metrics": {
      "total_earned": {
        "value": 45231.89,
        "formatted": "$45,231.89",
        "change_percentage": 20.1,
        "change_text": "+20.1% from last month",
        "trend": "up"
      }
    },
    "user_metrics": {
      "total_users": {
        "value": 2350,
        "formatted": "+2,350",
        "change_percentage": 180.1,
        "change_text": "+180.1% from last month",
        "trend": "up"
      }
    },
    "content_metrics": {
      "meal_plans": {
        "value": 122,
        "formatted": "+122",
        "change_percentage": 19,
        "change_text": "+19% from last month",
        "trend": "up"
      },
      "workouts_created": {
        "value": 573,
        "formatted": "+573",
        "change_percentage": 201,
        "change_text": "+201 since last hour",
        "trend": "up"
      }
    },
    "recent_users": {
      "title": "Recent Users",
      "subtitle": "Recent users signed up",
      "users": [
        {
          "id": 1,
          "name": "Olivia Martin",
          "email": "olivia.martin@email.com",
          "initials": "OM",
          "avatar_color": "#3B82F6",
          "earnings": 1999.0,
          "earnings_formatted": "+$1,999.00",
          "signup_date": "2025-09-15T10:30:00.000000Z"
        },
        {
          "id": 2,
          "name": "Jackson Lee",
          "email": "jackson.lee@email.com",
          "initials": "JL",
          "avatar_color": "#10B981",
          "earnings": 39.0,
          "earnings_formatted": "+$39.00",
          "signup_date": "2025-09-15T09:15:00.000000Z"
        },
        {
          "id": 3,
          "name": "Isabella Nguyen",
          "email": "isabella.nguyen@email.com",
          "initials": "IN",
          "avatar_color": "#F59E0B",
          "earnings": 299.0,
          "earnings_formatted": "+$299.00",
          "signup_date": "2025-09-15T08:45:00.000000Z"
        }
      ]
    },
    "summary": {
      "total_revenue": 45231.89,
      "total_users": 2350,
      "total_meal_plans": 122,
      "total_workouts": 573,
      "recent_users_count": 3
    }
  }
}
```

## Frontend Implementation Examples

### React Component Example

```jsx
import React, { useState, useEffect } from 'react';

const Dashboard = () => {
  const [dashboardData, setDashboardData] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    fetchDashboardData();
  }, []);

  const fetchDashboardData = async () => {
    try {
      const token = localStorage.getItem('adminToken');
      const response = await fetch('/api/v1/dashboard', {
        headers: {
          Authorization: `Bearer ${token}`,
          'Content-Type': 'application/json',
        },
      });

      const data = await response.json();

      if (data.status === 'success') {
        setDashboardData(data.data);
      } else {
        setError(data.message || 'Failed to fetch dashboard data');
      }
    } catch (error) {
      setError('Network error: ' + error.message);
    } finally {
      setLoading(false);
    }
  };

  if (loading) {
    return <div className="dashboard-loading">Loading dashboard...</div>;
  }

  if (error) {
    return <div className="dashboard-error">Error: {error}</div>;
  }

  if (!dashboardData) {
    return <div className="dashboard-error">No dashboard data available</div>;
  }

  return (
    <div className="dashboard">
      <h1>Dashboard</h1>

      {/* Financial Metrics */}
      <div className="metrics-grid">
        <div className="metric-card">
          <h3>Total Earned</h3>
          <div className="metric-value">
            {dashboardData.financial_metrics.total_earned.formatted}
          </div>
          <div className="metric-change positive">
            {dashboardData.financial_metrics.total_earned.change_text}
          </div>
        </div>

        <div className="metric-card">
          <h3>Users</h3>
          <div className="metric-value">{dashboardData.user_metrics.total_users.formatted}</div>
          <div className="metric-change positive">
            {dashboardData.user_metrics.total_users.change_text}
          </div>
        </div>

        <div className="metric-card">
          <h3>Meal Plans</h3>
          <div className="metric-value">{dashboardData.content_metrics.meal_plans.formatted}</div>
          <div className="metric-change positive">
            {dashboardData.content_metrics.meal_plans.change_text}
          </div>
        </div>

        <div className="metric-card">
          <h3>Workouts Created</h3>
          <div className="metric-value">
            {dashboardData.content_metrics.workouts_created.formatted}
          </div>
          <div className="metric-change positive">
            {dashboardData.content_metrics.workouts_created.change_text}
          </div>
        </div>
      </div>

      {/* Recent Users */}
      <div className="recent-users-section">
        <div className="section-header">
          <h2>{dashboardData.recent_users.title}</h2>
          <p>{dashboardData.recent_users.subtitle}</p>
        </div>

        <div className="users-list">
          {dashboardData.recent_users.users.map((user) => (
            <div key={user.id} className="user-item">
              <div className="user-avatar" style={{ backgroundColor: user.avatar_color }}>
                {user.initials}
              </div>
              <div className="user-info">
                <div className="user-name">{user.name}</div>
                <div className="user-email">{user.email}</div>
              </div>
              <div className="user-earnings">{user.earnings_formatted}</div>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
};

export default Dashboard;
```

### Vanilla JavaScript Example

```javascript
class DashboardManager {
  constructor(apiBaseUrl, token) {
    this.apiBaseUrl = apiBaseUrl;
    this.token = token;
  }

  async getDashboardData() {
    try {
      const response = await fetch(`${this.apiBaseUrl}/dashboard`, {
        headers: {
          Authorization: `Bearer ${this.token}`,
          'Content-Type': 'application/json',
        },
      });

      const data = await response.json();

      if (data.status === 'success') {
        return {
          success: true,
          data: data.data,
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

  async renderDashboard() {
    const result = await this.getDashboardData();

    if (result.success) {
      this.displayMetrics(result.data);
      this.displayRecentUsers(result.data.recent_users);
    } else {
      console.error('Dashboard error:', result.error);
    }
  }

  displayMetrics(data) {
    // Display financial metrics
    const totalEarnedElement = document.getElementById('total-earned');
    if (totalEarnedElement) {
      totalEarnedElement.innerHTML = `
        <div class="metric-value">${data.financial_metrics.total_earned.formatted}</div>
        <div class="metric-change positive">${data.financial_metrics.total_earned.change_text}</div>
      `;
    }

    // Display user metrics
    const usersElement = document.getElementById('users');
    if (usersElement) {
      usersElement.innerHTML = `
        <div class="metric-value">${data.user_metrics.total_users.formatted}</div>
        <div class="metric-change positive">${data.user_metrics.total_users.change_text}</div>
      `;
    }

    // Display content metrics
    const mealPlansElement = document.getElementById('meal-plans');
    if (mealPlansElement) {
      mealPlansElement.innerHTML = `
        <div class="metric-value">${data.content_metrics.meal_plans.formatted}</div>
        <div class="metric-change positive">${data.content_metrics.meal_plans.change_text}</div>
      `;
    }

    const workoutsElement = document.getElementById('workouts');
    if (workoutsElement) {
      workoutsElement.innerHTML = `
        <div class="metric-value">${data.content_metrics.workouts_created.formatted}</div>
        <div class="metric-change positive">${data.content_metrics.workouts_created.change_text}</div>
      `;
    }
  }

  displayRecentUsers(recentUsers) {
    const usersListElement = document.getElementById('recent-users-list');
    if (usersListElement) {
      usersListElement.innerHTML = recentUsers.users
        .map(
          (user) => `
            <div class="user-item">
              <div class="user-avatar" style="background-color: ${user.avatar_color}">
                ${user.initials}
              </div>
              <div class="user-info">
                <div class="user-name">${user.name}</div>
                <div class="user-email">${user.email}</div>
              </div>
              <div class="user-earnings">${user.earnings_formatted}</div>
            </div>
          `
        )
        .join('');
    }
  }
}

// Usage
const dashboardManager = new DashboardManager('/api/v1', localStorage.getItem('adminToken'));
dashboardManager.renderDashboard();
```

## CSS Styling Example

```css
.dashboard {
  padding: 20px;
  background-color: #1a1a1a;
  color: #ffffff;
  min-height: 100vh;
}

.dashboard h1 {
  color: #ffffff;
  margin-bottom: 30px;
  font-size: 2rem;
}

.metrics-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 20px;
  margin-bottom: 40px;
}

.metric-card {
  background-color: #2a2a2a;
  padding: 24px;
  border-radius: 12px;
  border: 1px solid #3a3a3a;
}

.metric-card h3 {
  color: #9ca3af;
  font-size: 0.875rem;
  font-weight: 500;
  margin-bottom: 8px;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.metric-value {
  color: #ffffff;
  font-size: 2rem;
  font-weight: 700;
  margin-bottom: 8px;
}

.metric-change {
  font-size: 0.875rem;
  font-weight: 500;
}

.metric-change.positive {
  color: #10b981;
}

.metric-change.negative {
  color: #ef4444;
}

.recent-users-section {
  background-color: #2a2a2a;
  padding: 24px;
  border-radius: 12px;
  border: 1px solid #3a3a3a;
}

.section-header {
  margin-bottom: 20px;
}

.section-header h2 {
  color: #ffffff;
  font-size: 1.25rem;
  font-weight: 600;
  margin-bottom: 4px;
}

.section-header p {
  color: #9ca3af;
  font-size: 0.875rem;
}

.users-list {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.user-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px;
  background-color: #1a1a1a;
  border-radius: 8px;
  border: 1px solid #3a3a3a;
}

.user-avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #ffffff;
  font-weight: 600;
  font-size: 0.875rem;
}

.user-info {
  flex: 1;
}

.user-name {
  color: #ffffff;
  font-weight: 500;
  margin-bottom: 2px;
}

.user-email {
  color: #9ca3af;
  font-size: 0.875rem;
}

.user-earnings {
  color: #10b981;
  font-weight: 600;
  font-size: 0.875rem;
}

.dashboard-loading {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 200px;
  color: #9ca3af;
}

.dashboard-error {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 200px;
  color: #ef4444;
  background-color: #2a2a2a;
  border-radius: 12px;
  margin: 20px;
}

/* Responsive Design */
@media (max-width: 768px) {
  .metrics-grid {
    grid-template-columns: 1fr;
  }

  .dashboard {
    padding: 16px;
  }

  .metric-card {
    padding: 20px;
  }

  .recent-users-section {
    padding: 20px;
  }
}
```

## Postman Collection

### Get Dashboard Data

**Method**: `GET`  
**URL**: `{{baseUrl}}/dashboard`  
**Headers**: `Authorization: Bearer {{adminToken}}`

### Example Response

```json
{
  "status": "success",
  "data": {
    "financial_metrics": {
      "total_earned": {
        "value": 45231.89,
        "formatted": "$45,231.89",
        "change_percentage": 20.1,
        "change_text": "+20.1% from last month",
        "trend": "up"
      }
    },
    "user_metrics": {
      "total_users": {
        "value": 2350,
        "formatted": "+2,350",
        "change_percentage": 180.1,
        "change_text": "+180.1% from last month",
        "trend": "up"
      }
    },
    "content_metrics": {
      "meal_plans": {
        "value": 122,
        "formatted": "+122",
        "change_percentage": 19,
        "change_text": "+19% from last month",
        "trend": "up"
      },
      "workouts_created": {
        "value": 573,
        "formatted": "+573",
        "change_percentage": 201,
        "change_text": "+201 since last hour",
        "trend": "up"
      }
    },
    "recent_users": {
      "title": "Recent Users",
      "subtitle": "Recent users signed up",
      "users": [
        {
          "id": 1,
          "name": "Olivia Martin",
          "email": "olivia.martin@email.com",
          "initials": "OM",
          "avatar_color": "#3B82F6",
          "earnings": 1999.0,
          "earnings_formatted": "+$1,999.00",
          "signup_date": "2025-09-15T10:30:00.000000Z"
        }
      ]
    }
  }
}
```

## Error Handling

### Common Error Responses

#### Unauthorized Error (401)

```json
{
  "status": "error",
  "message": "Unauthorized access",
  "error": "Invalid or missing authentication token"
}
```

#### Server Error (500)

```json
{
  "status": "error",
  "message": "Failed to fetch dashboard data",
  "error": "Database connection error"
}
```

## Key Features

### Financial Metrics

- **Total Earned**: Revenue tracking with percentage changes
- **Formatted Values**: Currency formatting for display
- **Trend Analysis**: Up/down trend indicators

### User Metrics

- **Total Users**: User count with growth percentages
- **Recent Users**: Latest user registrations with earnings

### Content Metrics

- **Meal Plans**: Content creation tracking
- **Workouts**: Workout creation statistics

### Recent Users

- **User Information**: Name, email, initials
- **Avatar Colors**: Dynamic color assignment
- **Earnings**: User contribution tracking
- **Signup Dates**: Registration timestamps

This dashboard endpoint provides all the key metrics and data you see in the dashboard image, excluding charts, with comprehensive frontend implementation examples!
