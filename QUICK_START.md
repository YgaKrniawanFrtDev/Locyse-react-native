# Quick Start Guide - Smart Attendance System

## Prerequisites
- PHP 8.1+
- Node.js 18+
- Composer
- SQLite atau MySQL
- Expo CLI (untuk React Native)

## 1. Backend Setup (5 minutes)

```bash
# Navigate to backend
cd backend

# Install dependencies
composer install

# Setup environment
cp .env.example .env
php artisan key:generate

# Setup database (SQLite is default)
php artisan migrate
php artisan db:seed

# Start server
php artisan serve
```

Server akan berjalan di `http://localhost:8000`

## 2. Frontend Setup (5 minutes)

```bash
# Navigate to frontend
cd frontend

# Install dependencies
npm install

# Start Expo
npm start

# For Android
npm run android

# For iOS
npm run ios
```

## 3. Quick Test

### Test API Endpoints

```bash
# Get today's attendance
curl http://localhost:8000/api/v1/attendance/today

# Get active QR codes
curl http://localhost:8000/api/v1/qr-codes?is_active=true
```

### Test Web Admin Page
1. Open `http://localhost:8000/admin/scan-barcode`
2. You should see "Waiting for scans..." message
3. Statistics should show 0 for all values

### Test Mobile App
1. Open Expo app on your phone
2. Scan QR code from terminal or use `npm start` to get the link
3. Grant camera permission
4. Try scanning a QR code (you can generate one from a token)

## 4. Generate Test QR Code

```bash
# Get a token from database
php artisan tinker

# In tinker shell:
>>> $qr = App\Models\qr_codes::first();
>>> $qr->token

# Copy the token and use it in mobile app
```

## 5. Test Attendance Scan

### Via Mobile App
1. Open scan screen
2. Tap settings icon to toggle between Check-In/Check-Out
3. Scan QR code (or use token)
4. Confirm attendance
5. See success message

### Via Web Admin
1. Open `http://localhost:8000/admin/scan-barcode`
2. Enter token in barcode input field
3. Press Enter
4. Enter User ID when prompted (1-5 for test users)
5. See attendance recorded in real-time list

## 6. Test Data

### Users (from seeder)
- ID 1: John Doe (john.doe / password123)
- ID 2: Jane Smith (jane.smith / password123)
- ID 3: Mike Johnson (mike.johnson / password123)
- ID 4: Sarah Williams (sarah.williams / password123)
- ID 5: Admin User (admin / admin123)

### QR Codes
- 5 active QR codes generated with 8-hour expiration
- All tokens are 32 random characters

## 7. Troubleshooting

### API Connection Failed
```bash
# Check if server is running
php artisan serve

# Check if port 8000 is available
# If not, use: php artisan serve --port=8001
```

### Camera Permission Issues
- iOS: Check Settings > Privacy > Camera
- Android: Check app permissions in Settings

### Database Errors
```bash
# Reset database
php artisan migrate:fresh --seed

# Check database file exists
ls database/database.sqlite
```

### CORS Errors
- Ensure CORS middleware is enabled (already configured)
- Check API_BASE_URL in mobile app matches server URL

## 8. API Endpoints Reference

### Attendance
- `POST /api/v1/attendance/scan` - Record attendance
- `GET /api/v1/attendance/today` - Get today's records
- `GET /api/v1/attendance/user/{userId}` - Get user history

### QR Codes
- `POST /api/v1/qr-codes/generate` - Generate new QR code
- `GET /api/v1/qr-codes` - List QR codes
- `GET /api/v1/qr-codes/{id}` - Get specific QR code

## 9. Next Steps

1. **Customize API Base URL**: Update in `frontend/app/(tabs-menu)/scan.tsx`
2. **Add Authentication**: Implement user login/logout
3. **Generate QR Images**: Use `endroid/qr-code` package
4. **Real-Time Updates**: Setup Laravel Echo + Pusher
5. **Mobile Notifications**: Add push notifications
6. **Offline Support**: Implement offline attendance caching

## 10. File Structure

```
Locyse-app/
├── backend/
│   ├── app/
│   │   ├── Http/Controllers/Api/
│   │   │   ├── AttendanceController.php
│   │   │   └── QrCodeController.php
│   │   ├── Events/
│   │   │   └── AttendanceScanned.php
│   │   └── Models/
│   │       ├── attendance.php
│   │       ├── qr_codes.php
│   │       └── users.php
│   ├── database/
│   │   ├── migrations/
│   │   └── seeders/
│   │       ├── UsersSeeder.php
│   │       └── QrCodesSeeder.php
│   ├── routes/
│   │   ├── api.php (NEW)
│   │   └── web.php
│   └── resources/views/admin/
│       └── scan-barcode.blade.php (UPDATED)
├── frontend/
│   └── app/(tabs-menu)/
│       └── scan.tsx (UPDATED)
└── SMART_ATTENDANCE_SETUP.md
```

## 11. Support

For detailed documentation, see `SMART_ATTENDANCE_SETUP.md`

Happy coding! 🚀
