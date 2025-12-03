# Smart Attendance System - Setup Guide

## Overview
Sistem Smart Attendance mengintegrasikan QR Code/Barcode scanning antara mobile app (React Native) dan web admin (Laravel). Token yang di-generate di database akan di-format menjadi barcode yang dapat di-scan oleh users.

## Architecture

### Backend (Laravel)
- **API Endpoints**: `/api/v1/`
  - `POST /attendance/scan` - Record attendance scan
  - `GET /attendance/today` - Get today's attendance records
  - `GET /attendance/user/{userId}` - Get user's attendance history
  - `POST /qr-codes/generate` - Generate new QR code
  - `GET /qr-codes` - List QR codes
  - `GET /qr-codes/{id}` - Get specific QR code

### Frontend (React Native)
- **Scan Screen** (`app/(tabs-menu)/scan.tsx`)
  - Barcode scanning dengan camera
  - Toggle antara Check-In dan Check-Out
  - Real-time API submission
  - Success/Error feedback dengan Lottie animation

### Web Admin (Laravel Blade)
- **Scan Barcode Page** (`resources/views/admin/scan-barcode.blade.php`)
  - Real-time scan list dengan polling
  - Today's statistics (Check-In, Check-Out, Active QR Codes)
  - Manual barcode input untuk physical scanner
  - Live updates setiap 5 detik

## Database Schema

### Users Table
```sql
- id (Primary Key)
- nama (String)
- username (String, Unique)
- password (String)
- role (Enum: admin, users)
- timestamps
```

### QR Codes Table
```sql
- id (Primary Key)
- token (String, Unique) - Random 32 character token
- is_active (Boolean)
- expired_date (DateTime)
- timestamps
```

### Attendances Table
```sql
- id (Primary Key)
- user_id (Foreign Key -> users)
- qr_code_id (Foreign Key -> qr_codes)
- status (Enum: masuk, pulang)
- scanned_at (DateTime)
- timestamps
```

## Setup Instructions

### 1. Backend Setup

#### Install Dependencies
```bash
cd backend
composer install
```

#### Database Setup
```bash
# Copy .env.example to .env
cp .env.example .env

# Generate app key
php artisan key:generate

# Run migrations
php artisan migrate

# Seed test data
php artisan db:seed
```

#### Start Laravel Server
```bash
php artisan serve
# Server akan berjalan di http://localhost:8000
```

### 2. Frontend Setup

#### Install Dependencies
```bash
cd frontend
npm install
# atau
yarn install
```

#### Update API Base URL
Edit `app/(tabs-menu)/scan.tsx`:
```typescript
const API_BASE_URL = 'http://localhost:8000/api/v1';
```

#### Start Expo App
```bash
npm start
# atau
yarn start

# Untuk Android
npm run android

# Untuk iOS
npm run ios
```

## Test Data

Setelah menjalankan seeder, berikut test data yang tersedia:

### Users
| Username | Password | Role |
|----------|----------|------|
| john.doe | password123 | users |
| jane.smith | password123 | users |
| mike.johnson | password123 | users |
| sarah.williams | password123 | users |
| admin | admin123 | admin |

### QR Codes
- 5 QR codes dengan token random 32 karakter
- Semua aktif dan expired dalam 8 jam

## API Usage Examples

### Generate QR Code
```bash
curl -X POST http://localhost:8000/api/v1/qr-codes/generate \
  -H "Content-Type: application/json" \
  -d '{
    "expired_date": "2025-12-03 17:00:00"
  }'
```

### Scan Attendance
```bash
curl -X POST http://localhost:8000/api/v1/attendance/scan \
  -H "Content-Type: application/json" \
  -d '{
    "token": "random_token_here",
    "user_id": 1,
    "status": "masuk"
  }'
```

### Get Today's Attendance
```bash
curl http://localhost:8000/api/v1/attendance/today
```

## Mobile App Features

### Scan Screen
1. **Camera Permission**: Request camera access on first launch
2. **Barcode Scanning**: Automatic detection of QR/barcode
3. **Status Toggle**: Tap settings icon to switch between Check-In/Check-Out
4. **Confirmation**: Show scanned token before submitting
5. **Feedback**: Success animation dan error messages

### Key Functions
- `handleBarcodeScanned()` - Process barcode data
- `submitAttendance()` - Send to API
- `toggleStatus()` - Switch between masuk/pulang

## Web Admin Features

### Scan Barcode Page
1. **Real-Time List**: Updates setiap 5 detik
2. **Statistics**: Total Check-In, Check-Out, Active QR Codes
3. **Manual Input**: Untuk physical barcode scanner
4. **Status Indicators**: Green untuk masuk, Red untuk pulang

### Key Functions
- `loadTodayAttendance()` - Fetch attendance data
- `loadActiveQrCodes()` - Get active QR codes count
- `updateScanList()` - Update UI with latest scans
- `submitBarcodeScan()` - Process manual barcode input

## Real-Time Updates

### Current Implementation
- Polling API setiap 5 detik di web admin
- Automatic refresh setelah scan di mobile app

### Future Enhancement
- Implement Laravel Echo + Pusher untuk WebSocket real-time
- Browser notifications saat ada scan baru
- Server-sent events (SSE) sebagai alternatif

## Error Handling

### Mobile App
- Invalid token: "QR code not found"
- Expired QR code: "QR code has expired"
- Duplicate scan: "You have already scanned for [status] today"
- Network error: "Failed to connect to server"

### Web Admin
- API connection error: Displayed in status message
- Invalid user ID: Prompt user untuk input ulang
- Server error: Show error message dengan timeout

## Security Considerations

1. **Token Generation**: Random 32 character string
2. **Expiration**: QR codes expire sesuai `expired_date`
3. **Duplicate Prevention**: Check scan pada hari yang sama
4. **User Validation**: Verify user exists sebelum recording
5. **Status Validation**: Only accept 'masuk' atau 'pulang'

## Troubleshooting

### API Connection Failed
- Pastikan Laravel server berjalan: `php artisan serve`
- Check API_BASE_URL di mobile app
- Verify CORS configuration di Laravel

### Camera Permission Denied
- Grant camera permission di app settings
- Restart app setelah grant permission

### QR Code Not Scanning
- Ensure barcode scanner settings correct
- Check QR code is not expired
- Verify token exists di database

### Real-Time Updates Not Working
- Check network connection
- Verify API endpoints accessible
- Check browser console untuk errors

## Next Steps

1. **Authentication**: Integrate user login/logout
2. **Real-Time WebSocket**: Implement Laravel Echo + Pusher
3. **Attendance Reports**: Add detailed analytics dashboard
4. **Mobile Notifications**: Push notifications untuk attendance events
5. **Offline Support**: Cache attendance data untuk offline scanning
6. **QR Code Generation**: Generate actual QR code images
7. **Biometric Integration**: Add fingerprint/face recognition
