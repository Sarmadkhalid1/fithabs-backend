# CORS Error Fix Guide for FitHabs Admin Login

## ✅ **Issues Fixed**

### 1. **CORS Configuration Updated**

- Changed `supports_credentials` from `false` to `true`
- This allows cookies and authentication headers to be sent

### 2. **Route Definition Fixed**

- Removed extra space from `/admin-login ` → `/admin-login`
- Route is now properly registered

### 3. **Cache Cleared**

- Configuration cache cleared
- Application cache cleared

## 🔧 **Current CORS Configuration**

```php
// config/cors.php
'paths' => ['api/*', 'sanctum/csrf-cookie'],
'allowed_methods' => ['*'],
'allowed_origins' => ['*'],
'allowed_headers' => ['*'],
'supports_credentials' => true,
```

## 🚀 **Testing the Fix**

### **Test with cURL (should work now):**

```bash
curl -X POST http://127.0.0.1:8000/api/v1/admin-login \
  -H "Content-Type: application/json" \
  -H "Origin: http://localhost:3000" \
  -d '{"email": "admin@example.com", "password": "password123"}'
```

### **Test with JavaScript (frontend):**

```javascript
// Make sure to include credentials
fetch('http://127.0.0.1:8000/api/v1/admin-login', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    Accept: 'application/json',
  },
  credentials: 'include', // Important for CORS with credentials
  body: JSON.stringify({
    email: 'admin@example.com',
    password: 'password123',
  }),
})
  .then((response) => response.json())
  .then((data) => console.log(data))
  .catch((error) => console.error('Error:', error));
```

## 🔍 **If CORS Still Persists**

### **Option 1: Restart Laravel Server**

```bash
# Stop current server (Ctrl+C)
# Then restart:
php artisan serve
```

### **Option 2: Check Frontend Origin**

Make sure your frontend is running on a proper origin:

- `http://localhost:3000` ✅
- `http://127.0.0.1:3000` ✅
- `file://` ❌ (won't work with CORS)

### **Option 3: Add Specific Origins (if needed)**

If you want to restrict origins instead of allowing all:

```php
// config/cors.php
'allowed_origins' => [
    'http://localhost:3000',
    'http://127.0.0.1:3000',
    'http://localhost:8080',
    'http://127.0.0.1:8080',
],
```

### **Option 4: Browser Developer Tools**

1. Open browser DevTools (F12)
2. Go to Network tab
3. Make the request
4. Check the Response Headers for CORS headers:
   - `Access-Control-Allow-Origin`
   - `Access-Control-Allow-Methods`
   - `Access-Control-Allow-Headers`

## 📋 **Common CORS Headers You Should See**

```
Access-Control-Allow-Origin: *
Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS
Access-Control-Allow-Headers: *
Access-Control-Allow-Credentials: true
```

## 🎯 **Next Steps**

1. **Restart your Laravel server** if it's running
2. **Test the endpoint** with the provided examples
3. **Check browser console** for any remaining errors
4. **Verify the admin user exists** in your database

## 🚨 **If Still Having Issues**

Run this diagnostic command:

```bash
curl -X OPTIONS http://127.0.0.1:8000/api/v1/admin-login \
  -H "Origin: http://localhost:3000" \
  -H "Access-Control-Request-Method: POST" \
  -H "Access-Control-Request-Headers: Content-Type" \
  -v
```

This will show you the preflight CORS response headers.
