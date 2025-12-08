@extends('layouts.admin')

@section('title', 'Scan Barcode - Admin')
@section('page_title', 'Scan Barcode')

@section('content')

    @if(isset($qr_code))
    <section class="card card-bigqr text-dark" style="margin-bottom: 20px;">
        <h2 class="text-center">QR Code Absensi Aktif</h2>

        <div class="bigqr-wrapper">
            {!! QrCode::size(250)->generate($qr_code->token) !!}
        </div>

        <p class="text-desc" style="text-align:center; margin-top:10px;">
            Token: {{ $qr_code->token }}
        </p>
    </section>
    @endif
    <style>
        .text-desc {
            color: black !important;
        }
        .card-bigqr {
            grid-column: span 2;
            text-align: center;
            padding: 20px;
            border-left: 4px solid #7c3aed;
        }

        .bigqr-wrapper {
            background: #fff;
            display: inline-block;
            padding: 20px;
            border-radius: 12px;
            border: 2px solid #e0e0e0;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
            margin-top: 10px;
        }

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
            transition: .3s;
        }

        .qrcode-card:hover {
            border-color: #7c3aed;
            box-shadow: 0 4px 12px rgba(124, 58, 237, 0.15);
        }

        .qrcode-barcode {
            background: white;
            padding: 10px;
            border-radius: 4px;
            margin: 10px 0;
        }

        .qrcode-token {
            font-size: 11px;
            color: #7c8da3;
            background: #f0f0f0;
            padding: 8px;
            border-radius: 4px;
            font-family: monospace;
        }

        .qrcode-status {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            margin-top: 8px;
        }

        .qrcode-status.active {
            background: #d4edda;
            color: #155724;
        }

        .qrcode-status.expired {
            background: #f8d7da;
            color: #721c24;
        }

        .scan-item {
            padding: 12px;
            border-bottom: 1px solid #e0e0e0;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-top: 15px;
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

        document.addEventListener('DOMContentLoaded', function() {
            loadQrCodes();
            loadTodayAttendance();
            loadActiveQrCodes();
            setupWebSocket();
        });

        function setupWebSocket() {
            setInterval(loadTodayAttendance, 5000);
        }

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

        async function loadQrCodes() {
            try {
                const response = await fetch(`${API_BASE_URL}/qr-codes?is_active=true`);
                const data = await response.json();

                if (data.success && data.data.data) {
                    const grid = document.getElementById('qrcodes-grid');
                    grid.innerHTML = data.data.data.map(qr => `
                        <div class="qrcode-card">
                            <h4>QR Code #${qr.id}</h4>
                            <div class="qrcode-barcode">
                                <img src="${API_BASE_URL}/barcodes/${qr.id}/image" />
                            </div>
                            <div class="qrcode-token">${qr.token}</div>
                            <span class="qrcode-status ${isExpired(qr.expired_date) ? 'expired' : 'active'}">
                                ${isExpired(qr.expired_date) ? 'Expired' : 'Active'}
                            </span>
                        </div>
                    `).join('');
                }
            } catch (error) {
                console.error('Error loading QR:', error);
            }
        }

        function isExpired(date) {
            return new Date(date) < new Date();
        }

        document.getElementById('barcode-input').addEventListener('keypress', async function(e) {
            if (e.key === 'Enter') {
                const token = this.value.trim();
                if (token) {
                    await submitBarcodeScan(token);
                    this.value = '';
                }
            }
        });

        async function submitBarcodeScan(token) {
            const statusDiv = document.getElementById('scan-status');
            const statusMsg = document.getElementById('status-message');

            try {

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
                    setTimeout(() => statusDiv.style.display = 'none', 3000);
                } else {
                    statusDiv.className = 'error';
                    statusDiv.style.display = 'block';
                    statusMsg.textContent = `✗ ${data.message}`;
                    setTimeout(() => statusDiv.style.display = 'none', 3000);
                }

            } catch (error) {
                statusDiv.className = 'error';
                statusDiv.style.display = 'block';
                statusMsg.textContent = '✗ Error: ' + error.message;
                setTimeout(() => statusDiv.style.display = 'none', 3000);
            }
        }

        function updateScanList() {
            const scanList = document.getElementById('scan-list');

            if (recentScans.length === 0) {
                scanList.innerHTML =
                    '<p class="muted" style="text-align:center; padding:20px;">Waiting for scans...</p>';
                return;
            }

            scanList.innerHTML = recentScans.map(scan => `
                <div class="scan-item">
                    <div>
                        <span class="scan-name">${scan.user_name}</span>
                        <span class="scan-status">${scan.status === 'masuk' ? 'Check In' : 'Check Out'}</span>
                    </div>
                    <span class="scan-time">${formatTime(scan.scanned_at)}</span>
                </div>
            `).join('');
        }

        function updateStats() {
            stats.checkin = recentScans.filter(s => s.status === 'masuk').length;
            stats.checkout = recentScans.filter(s => s.status === 'pulang').length;

            document.getElementById('total-checkin').textContent = stats.checkin;
            document.getElementById('total-checkout').textContent = stats.checkout;
        }

        function formatTime(dateString) {
            const date = new Date(dateString);
            return date.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
        }
    </script>

@endsection
