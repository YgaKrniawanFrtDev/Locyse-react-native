# Barcode Generation Feature

## Overview
Token dari QR codes sekarang sudah di-format menjadi barcode images yang dapat di-display di web admin dan di-scan oleh mobile app.

## Installation

### Step 1: Install Barcode Generator Package
```bash
cd backend
composer require picqer/php-barcode-generator
```

### Step 2: Files Created
- `/backend/app/Http/Controllers/Api/BarcodeController.php`

### Step 3: Files Updated
- `/backend/routes/api.php` - Added barcode endpoints
- `/backend/resources/views/admin/scan-barcode.blade.php` - Display barcodes

## API Endpoints

### Generate Barcode Image (PNG)
```bash
GET /api/v1/barcodes/{id}/image
```

**Response:** PNG image file

**Example:**
```html
<img src="http://localhost:8000/api/v1/barcodes/1/image" alt="Barcode 1" />
```

### Generate Barcode HTML (SVG)
```bash
GET /api/v1/barcodes/{id}/html
```

**Response:** HTML SVG barcode

### Get Barcode as Base64
```bash
GET /api/v1/barcodes/{id}/base64
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "token": "aBcDeFgHiJkLmNoPqRsTuVwXyZ123456",
    "barcode_base64": "data:image/png;base64,iVBORw0KGgoAAAANS...",
    "barcode_url": "http://localhost:8000/api/v1/barcodes/1/image"
  }
}
```

## Web Admin Features

### Available QR Codes Section
- Display semua active QR codes dalam grid layout
- Setiap card menampilkan:
  - QR Code ID
  - Barcode image (CODE128 format)
  - Token string
  - Status (Active/Expired)
  - Copy Token button

### How to Use
1. Buka `http://localhost:8000/admin/scan-barcode`
2. Scroll ke "Available QR Codes" section
3. Lihat barcode images untuk setiap QR code
4. Click "Copy Token" untuk copy token ke clipboard
5. Gunakan token untuk manual scan atau print barcode

## Barcode Format

### Type: CODE128
- Format: CODE128 (standard barcode format)
- Content: Random 32-character token
- Scannable: Oleh physical barcode scanner
- Mobile: Dapat di-scan dengan mobile app

### Example Token
```
aBcDeFgHiJkLmNoPqRsTuVwXyZ123456
```

Akan di-format menjadi barcode yang dapat di-scan.

## Mobile App Integration

### Scanning Barcode
1. Mobile app camera akan detect barcode
2. Extract token dari barcode
3. Submit token ke API
4. Record attendance

### Barcode Scanning Settings
```typescript
barcodeScannerSettings={{
  barcodeTypes: ['qr', 'code128', 'code39', 'ean13'],
}}
```

Supported formats:
- QR codes
- CODE128 barcodes
- CODE39 barcodes
- EAN13 barcodes

## Testing Barcode

### Via Web Admin
1. Open `http://localhost:8000/admin/scan-barcode`
2. Scroll to "Available QR Codes"
3. Right-click barcode image → "Save image as"
4. Print barcode
5. Scan dengan physical barcode scanner

### Via Mobile App
1. Open mobile app scan screen
2. Point camera at barcode
3. App will detect and display token
4. Tap "Absen Sekarang" to submit

### Via API
```bash
# Get barcode image
curl -o barcode.png http://localhost:8000/api/v1/barcodes/1/image

# Get barcode as base64
curl http://localhost:8000/api/v1/barcodes/1/base64
```

## Troubleshooting

### Barcode Not Displaying
- Check if barcode package installed: `composer show picqer/php-barcode-generator`
- Verify API endpoint accessible: `http://localhost:8000/api/v1/barcodes/1/image`
- Check Laravel logs for errors

### Barcode Not Scanning
- Ensure barcode is clear and not damaged
- Try different barcode scanner app
- Verify token is valid and not expired
- Check mobile app barcode scanner settings

### Image Format Issues
- PNG format is standard
- Ensure browser supports image display
- Check Content-Type header: `image/png`

## Performance

### Image Generation
- Generated on-demand (not cached)
- Fast generation (~10ms per barcode)
- PNG format is optimized for web

### Optimization Tips
1. Cache barcode images if frequently accessed
2. Generate barcodes in background job
3. Use CDN for image delivery
4. Compress PNG images

## Future Enhancements

1. **QR Code Generation**: Generate actual QR codes instead of CODE128
2. **Barcode Caching**: Cache generated barcodes for performance
3. **Batch Export**: Export multiple barcodes as PDF
4. **Custom Branding**: Add logo/text to barcode
5. **Barcode Printing**: Direct print from web admin
6. **Mobile Barcode Display**: Show barcode on mobile screen for manual scanning

## Code Examples

### Display Barcode in HTML
```html
<img src="{{ route('api.barcode.image', $qrCode->id) }}" alt="Barcode" />
```

### Get Barcode Base64 in JavaScript
```javascript
fetch('/api/v1/barcodes/1/base64')
  .then(r => r.json())
  .then(d => {
    const img = new Image();
    img.src = d.data.barcode_base64;
    document.body.appendChild(img);
  });
```

### Generate Barcode in PHP
```php
use Picqer\Barcode\BarcodeGeneratorPNG;

$generator = new BarcodeGeneratorPNG();
$barcode = $generator->getBarcode('token_here', BarcodeGeneratorPNG::TYPE_CODE_128);
header('Content-Type: image/png');
echo $barcode;
```

## References

- Package: [picqer/php-barcode-generator](https://github.com/picqer/php-barcode-generator)
- Barcode Types: CODE128, CODE39, EAN13, UPC, etc.
- Mobile Scanner: Expo Barcode Scanner API
