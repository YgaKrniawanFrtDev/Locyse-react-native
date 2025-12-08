<!DOCTYPE html>
<html>
<head>
    <title>QR Codes</title>

    <!-- QRCode.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
</head>

<body>

    <h1>QR Token List</h1>

    @foreach ($qrs as $qr)
        <div style="margin-bottom:20px;">
            <p>Token: {{ $qr->token }}</p>

            <!-- tempat QR -->
            <div id="qr-{{ $qr->id }}"></div>
        </div>
    @endforeach

    <script>
        @foreach ($qrs as $qr)
            new QRCode(document.getElementById("qr-{{ $qr->id }}"), {
                text: "{{ $qr->token }}",
                width: 200,
                height: 200
            });
        @endforeach
    </script>

</body>
</html>
