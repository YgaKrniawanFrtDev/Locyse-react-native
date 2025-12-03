# API Testing Guide

## Base URL
```
http://localhost:8000/api/v1
```

## Prerequisites
- Laravel server running: `php artisan serve`
- Database seeded: `php artisan db:seed`
- curl or Postman installed

## 1. QR Code Endpoints

### Generate New QR Code
```bash
curl -X POST http://localhost:8000/api/v1/qr-codes/generate \
  -H "Content-Type: application/json" \
  -d '{
    "expired_date": "2025-12-03 17:00:00"
  }'
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 6,
    "token": "aBcDeFgHiJkLmNoPqRsTuVwXyZ123456",
    "is_active": true,
    "expired_date": "2025-12-03 17:00:00",
    "created_at": "2025-12-03T09:30:00.000000Z",
    "updated_at": "2025-12-03T09:30:00.000000Z"
  },
  "message": "QR code generated successfully"
}
```

### List All QR Codes
```bash
curl http://localhost:8000/api/v1/qr-codes
```

**Response:**
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "token": "token_here",
        "is_active": true,
        "expired_date": "2025-12-03 17:00:00",
        "created_at": "2025-12-03T09:00:00.000000Z",
        "updated_at": "2025-12-03T09:00:00.000000Z"
      }
    ],
    "total": 5
  }
}
```

### List Active QR Codes Only
```bash
curl "http://localhost:8000/api/v1/qr-codes?is_active=true"
```

### Get Specific QR Code
```bash
curl http://localhost:8000/api/v1/qr-codes/1
```

## 2. Attendance Endpoints

### Scan Attendance (Check-In)
```bash
curl -X POST http://localhost:8000/api/v1/attendance/scan \
  -H "Content-Type: application/json" \
  -d '{
    "token": "aBcDeFgHiJkLmNoPqRsTuVwXyZ123456",
    "user_id": 1,
    "status": "masuk"
  }'
```

**Response (Success):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "user_id": 1,
    "qr_code_id": 1,
    "status": "masuk",
    "scanned_at": "2025-12-03 09:30:00",
    "user": {
      "id": 1,
      "nama": "John Doe",
      "username": "john.doe",
      "role": "users"
    },
    "qrCode": {
      "id": 1,
      "token": "aBcDeFgHiJkLmNoPqRsTuVwXyZ123456",
      "is_active": true,
      "expired_date": "2025-12-03 17:00:00"
    }
  },
  "message": "Attendance recorded successfully"
}
```

**Response (Error - Duplicate Scan):**
```json
{
  "success": false,
  "message": "You have already scanned for masuk today"
}
```

### Scan Attendance (Check-Out)
```bash
curl -X POST http://localhost:8000/api/v1/attendance/scan \
  -H "Content-Type: application/json" \
  -d '{
    "token": "aBcDeFgHiJkLmNoPqRsTuVwXyZ123456",
    "user_id": 1,
    "status": "pulang"
  }'
```

### Get Today's Attendance
```bash
curl http://localhost:8000/api/v1/attendance/today
```

**Response:**
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "user_id": 1,
        "user_name": "John Doe",
        "status": "masuk",
        "scanned_at": "2025-12-03 09:30:00",
        "qr_code_id": 1
      },
      {
        "id": 2,
        "user_id": 2,
        "user_name": "Jane Smith",
        "status": "masuk",
        "scanned_at": "2025-12-03 09:32:00",
        "qr_code_id": 1
      }
    ],
    "total": 2
  }
}
```

### Get User Attendance History
```bash
curl http://localhost:8000/api/v1/attendance/user/1
```

**Response:**
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "user_id": 1,
        "qr_code_id": 1,
        "status": "masuk",
        "scanned_at": "2025-12-03 09:30:00",
        "qrCode": {
          "id": 1,
          "token": "aBcDeFgHiJkLmNoPqRsTuVwXyZ123456",
          "is_active": true,
          "expired_date": "2025-12-03 17:00:00"
        }
      }
    ],
    "total": 1
  }
}
```

## 3. Error Scenarios

### Invalid Token
```bash
curl -X POST http://localhost:8000/api/v1/attendance/scan \
  -H "Content-Type: application/json" \
  -d '{
    "token": "invalid_token",
    "user_id": 1,
    "status": "masuk"
  }'
```

**Response:**
```json
{
  "success": false,
  "message": "QR code not found"
}
```

### Expired QR Code
```json
{
  "success": false,
  "message": "QR code has expired"
}
```

### Inactive QR Code
```json
{
  "success": false,
  "message": "QR code is not active"
}
```

### Invalid User
```json
{
  "success": false,
  "message": "User not found"
}
```

### Invalid Status
```json
{
  "success": false,
  "message": "The status field must be one of: masuk, pulang."
}
```

### Missing Required Fields
```json
{
  "success": false,
  "message": "The token field is required. (and 2 more errors)"
}
```

## 4. Testing Workflow

### Step 1: Get Active QR Code Token
```bash
curl http://localhost:8000/api/v1/qr-codes?is_active=true | jq '.data.data[0].token'
```

### Step 2: Record Check-In
```bash
TOKEN="<token_from_step_1>"
curl -X POST http://localhost:8000/api/v1/attendance/scan \
  -H "Content-Type: application/json" \
  -d "{
    \"token\": \"$TOKEN\",
    \"user_id\": 1,
    \"status\": \"masuk\"
  }"
```

### Step 3: Record Check-Out
```bash
TOKEN="<token_from_step_1>"
curl -X POST http://localhost:8000/api/v1/attendance/scan \
  -H "Content-Type: application/json" \
  -d "{
    \"token\": \"$TOKEN\",
    \"user_id\": 1,
    \"status\": \"pulang\"
  }"
```

### Step 4: View Today's Records
```bash
curl http://localhost:8000/api/v1/attendance/today
```

## 5. Postman Collection

### Import to Postman

1. Create new collection: "Smart Attendance API"
2. Add requests:

**Request 1: Get QR Codes**
- Method: GET
- URL: `{{base_url}}/qr-codes`
- Headers: `Content-Type: application/json`

**Request 2: Generate QR Code**
- Method: POST
- URL: `{{base_url}}/qr-codes/generate`
- Headers: `Content-Type: application/json`
- Body:
```json
{
  "expired_date": "2025-12-03 17:00:00"
}
```

**Request 3: Scan Attendance**
- Method: POST
- URL: `{{base_url}}/attendance/scan`
- Headers: `Content-Type: application/json`
- Body:
```json
{
  "token": "{{token}}",
  "user_id": 1,
  "status": "masuk"
}
```

**Request 4: Get Today's Attendance**
- Method: GET
- URL: `{{base_url}}/attendance/today`

**Request 5: Get User Attendance**
- Method: GET
- URL: `{{base_url}}/attendance/user/1`

### Set Variables in Postman
- `base_url`: `http://localhost:8000/api/v1`
- `token`: Copy from QR code response

## 6. Performance Testing

### Load Test - Multiple Scans
```bash
for i in {1..10}; do
  curl -X POST http://localhost:8000/api/v1/attendance/scan \
    -H "Content-Type: application/json" \
    -d "{
      \"token\": \"aBcDeFgHiJkLmNoPqRsTuVwXyZ123456\",
      \"user_id\": $((i % 5 + 1)),
      \"status\": \"masuk\"
    }" &
done
wait
```

## 7. Debugging Tips

### Enable Query Logging
In `backend/.env`:
```
DB_QUERY_LOG=true
```

### Check Laravel Logs
```bash
tail -f backend/storage/logs/laravel.log
```

### Test Database Connection
```bash
php artisan tinker
>>> DB::connection()->getPdo()
>>> App\Models\qr_codes::count()
```

### Verify API Routes
```bash
php artisan route:list | grep api
```

## 8. Common Issues & Solutions

### CORS Error
- Check `config/cors.php` is configured
- Verify middleware is enabled in `bootstrap/app.php`
- Ensure API URL matches in mobile app

### 404 Not Found
- Verify API routes are registered
- Check URL spelling and parameters
- Ensure server is running

### 500 Internal Server Error
- Check Laravel logs: `storage/logs/laravel.log`
- Verify database is migrated
- Check model relationships

### Validation Errors
- Ensure all required fields are provided
- Check data types (user_id must be integer)
- Verify enum values: status must be 'masuk' or 'pulang'

## 9. Next Steps

1. Implement authentication tokens
2. Add rate limiting
3. Setup WebSocket for real-time updates
4. Add request logging/auditing
5. Implement caching for frequently accessed data
