@extends('layouts.admin')

@section('title', 'Scan Barcode - Admin')
@section('page_title', 'Scan Barcode')

@section('content')
    <div class="dashboard-grid">
        <section class="card card-scan">
            <h2>Barcode Scanner</h2>
            <div class="scan-input-wrapper">
                <input type="text" id="barcode-input" placeholder="Scan barcode here..." class="barcode-input" autofocus />
            </div>
            <p class="muted" style="margin-top: 12px;">Arahkan scanner ke barcode karyawan untuk mencatat kehadiran.</p>
            <div id="scan-status" style="margin-top: 12px; padding: 12px; border-radius: 8px; background-color: #f0f0f0; display: none;">
                <p id="status-message"></p>
            </div>
        </section>

        <section class="card card-qrcodes">
            <h2>Available QR Codes</h2>
            <div class="qrcodes-grid" id="qrcodes-grid">
                <p class="muted" style="text-align: center; padding: 20px;">Loading QR codes...</p>
            </div>
        </section>

        <section class="card card-recent">
            <h2>Real-Time Scans</h2>
            <div class="scan-list" id="scan-list">
                <p class="muted" style="text-align: center; padding: 20px;">Waiting for scans...</p>
            </div>
        </section>

        <section class="card card-stats">
            <h2>Today's Statistics</h2>
            <div class="stats-grid">
                <div class="stat-item">
                    <span class="stat-label">Total Check-In</span>
                    <span class="stat-value" id="total-checkin">0</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Total Check-Out</span>
                    <span class="stat-value" id="total-checkout">0</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Active QR Codes</span>
                    <span class="stat-value" id="active-qrcodes">0</span>
                </div>
            </div>
        </section>
    </div>

    <style>
        .qrcodes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }

        .qrcode-card {
            background-color: #f9f9f9;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .qrcode-card:hover {
            border-color: #7c3aed;
            box-shadow: 0 4px 12px rgba(124, 58, 237, 0.15);
        }

        .qrcode-card h4 {
            margin: 0 0 10px 0;
            color: #2c3e50;
            font-size: 14px;
        }

        .qrcode-barcode {
            background-color: white;
            padding: 10px;
            border-radius: 4px;
            margin: 10px 0;
            min-height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .qrcode-barcode img {
            max-width: 100%;
            height: auto;
        }

        .qrcode-token {
            font-size: 11px;
            color: #7c8da3;
            word-break: break-all;
            margin: 10px 0;
            font-family: monospace;
            background-color: #f0f0f0;
            padding: 8px;
            border-radius: 4px;
        }

        .qrcode-status {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            margin-top: 8px;
        }

        .qrcode-status.active {
            background-color: #d4edda;
            color: #155724;
        }

        .qrcode-status.expired {
            background-color: #f8d7da;
            color: #721c24;
        }

        .qrcode-copy-btn {
            background-color: #7c3aed;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 12px;
            cursor: pointer;
            margin-top: 8px;
            transition: background-color 0.3s;
        }

        .qrcode-copy-btn:hover {
            background-color: #6d28d9;
        }

        .scan-list {
            max-height: 400px;
            overflow-y: auto;
        }

        .scan-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px;
            border-bottom: 1px solid #e0e0e0;
            animation: slideIn 0.3s ease-in-out;
        }

        .scan-item:last-child {
            border-bottom: none;
        }

        .scan-item.checkin {
            background-color: #d4edda;
        }

        .scan-item.checkout {
            background-color: #f8d7da;
        }

        .scan-name {
            font-weight: 600;
            color: #2c3e50;
        }

        .scan-time {
            color: #7c8da3;
            font-size: 14px;
        }

        .scan-status {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            margin-left: 10px;
        }

        .scan-status.checkin {
            background-color: #28a745;
            color: white;
        }

        .scan-status.checkout {
            background-color: #dc3545;
            color: white;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-top: 15px;
        }

        .stat-item {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            border-left: 4px solid #7c3aed;
        }

        .stat-label {
            display: block;
            color: #7c8da3;
            font-size: 12px;
            margin-bottom: 8px;
        }

        .stat-value {
            display: block;
            font-size: 28px;
            font-weight: bold;
            color: #2c3e50;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        #scan-status {
            border-left: 4px solid #7c3aed;
        }

        #scan-status.success {
            background-color: #d4edda;
            border-left-color: #28a745;
        }

        #scan-status.error {
            background-color: #f8d7da;
            border-left-color: #dc3545;
        }

        #status-message {
            margin: 0;
            color: #2c3e50;
        }
    </style>

    <script>
        const API_BASE_URL = '{{ config('app.url') }}/api/v1';
        let recentScans = [];
        let stats = {
            checkin: 0,
            checkout: 0,
            activeQrcodes: 0
        };

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            loadQrCodes();
            loadTodayAttendance();
            loadActiveQrCodes();
            setupWebSocket();
        });

        // Setup WebSocket for real-time updates
        function setupWebSocket() {
            // Using Pusher/Laravel Echo for real-time updates
            // For now, we'll poll the API
            setInterval(loadTodayAttendance, 5000);
        }

        // Load today's attendance
        async function loadTodayAttendance() {
            try {
                const response = await fetch(`${API_BASE_URL}/attendance/today`);
                const data = await response.json();

                if (data.success && data.data.data) {
                    recentScans = data.data.data;
                    updateScanList();
                    updateStats();
                }
            } catch (error) {
                console.error('Error loading attendance:', error);
            }
        }

        // Load QR codes with barcodes
        async function loadQrCodes() {
            try {
                const response = await fetch(`${API_BASE_URL}/qr-codes?is_active=true`);
                const data = await response.json();

                if (data.success && data.data.data) {
                    const qrcodesGrid = document.getElementById('qrcodes-grid');
                    qrcodesGrid.innerHTML = data.data.data.map(qr => `
                        <div class="qrcode-card">
                            <h4>QR Code #${qr.id}</h4>
                            <div class="qrcode-barcode">
                                <img src="${API_BASE_URL}/barcodes/${qr.id}/image" alt="Barcode ${qr.id}" />
                            </div>
                            <div class="qrcode-token">Token: ${qr.token}</div>
                            <span class="qrcode-status ${isExpired(qr.expired_date) ? 'expired' : 'active'}">
                                ${isExpired(qr.expired_date) ? 'Expired' : 'Active'}
                            </span>
                            <button class="qrcode-copy-btn" onclick="copyToken('${qr.token}')">Copy Token</button>
                        </div>
                    `).join('');
                }
            } catch (error) {
                console.error('Error loading QR codes:', error);
                document.getElementById('qrcodes-grid').innerHTML = '<p class="muted">Error loading QR codes</p>';
            }
        }

        // Load active QR codes
        async function loadActiveQrCodes() {
            try {
                const response = await fetch(`${API_BASE_URL}/qr-codes?is_active=true`);
                const data = await response.json();

                if (data.success) {
                    stats.activeQrcodes = data.data.total || 0;
                    document.getElementById('active-qrcodes').textContent = stats.activeQrcodes;
                }
            } catch (error) {
                console.error('Error loading QR codes:', error);
            }
        }

        // Check if QR code is expired
        function isExpired(expiredDate) {
            return new Date(expiredDate) < new Date();
        }

        // Copy token to clipboard
        function copyToken(token) {
            navigator.clipboard.writeText(token).then(() => {
                alert('Token copied to clipboard!');
            }).catch(err => {
                console.error('Failed to copy:', err);
            });
        }

        // Update scan list UI
        function updateScanList() {
            const scanList = document.getElementById('scan-list');
            
            if (recentScans.length === 0) {
                scanList.innerHTML = '<p class="muted" style="text-align: center; padding: 20px;">Waiting for scans...</p>';
                return;
            }

            scanList.innerHTML = recentScans.map(scan => `
                <div class="scan-item ${scan.status}">
                    <div>
                        <span class="scan-name">${scan.user_name}</span>
                        <span class="scan-status ${scan.status}">${scan.status === 'masuk' ? 'Check In' : 'Check Out'}</span>
                    </div>
                    <span class="scan-time">${formatTime(scan.scanned_at)}</span>
                </div>
            `).join('');
        }

        // Update statistics
        function updateStats() {
            stats.checkin = recentScans.filter(s => s.status === 'masuk').length;
            stats.checkout = recentScans.filter(s => s.status === 'pulang').length;

            document.getElementById('total-checkin').textContent = stats.checkin;
            document.getElementById('total-checkout').textContent = stats.checkout;
        }

        // Format time
        function formatTime(dateString) {
            const date = new Date(dateString);
            return date.toLocaleTimeString('id-ID', { 
                hour: '2-digit', 
                minute: '2-digit',
                second: '2-digit'
            });
        }

        // Handle barcode input (for physical barcode scanner)
        document.getElementById('barcode-input').addEventListener('keypress', async function(e) {
            if (e.key === 'Enter') {
                const token = this.value.trim();
                if (token) {
                    await submitBarcodeScan(token);
                    this.value = '';
                }
            }
        });

        // Submit barcode scan
        async function submitBarcodeScan(token) {
            const statusDiv = document.getElementById('scan-status');
            const statusMsg = document.getElementById('status-message');

            try {
                // For admin scanning, we need to get user ID from form or use a default
                const userId = prompt('Enter User ID:');
                if (!userId) return;

                const response = await fetch(`${API_BASE_URL}/attendance/scan`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        token,
                        user_id: parseInt(userId),
                        status: 'masuk'
                    })
                });

                const data = await response.json();

                if (data.success) {
                    statusDiv.className = 'success';
                    statusDiv.style.display = 'block';
                    statusMsg.textContent = `✓ Attendance recorded for ${data.data.user.nama}`;
                    loadTodayAttendance();
                    setTimeout(() => {
                        statusDiv.style.display = 'none';
                    }, 3000);
                } else {
                    statusDiv.className = 'error';
                    statusDiv.style.display = 'block';
                    statusMsg.textContent = `✗ ${data.message}`;
                    setTimeout(() => {
                        statusDiv.style.display = 'none';
                    }, 3000);
                }
            } catch (error) {
                statusDiv.className = 'error';
                statusDiv.style.display = 'block';
                statusMsg.textContent = '✗ Error: ' + error.message;
                setTimeout(() => {
                    statusDiv.style.display = 'none';
                }, 3000);
            }
        }
    </script>
@endsection
