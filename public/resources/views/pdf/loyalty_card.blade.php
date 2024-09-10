<!DOCTYPE html>
<html>
<head>
    <title>Carte de Fidélité</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
        }
        .card {
            border: 1px solid #333;
            padding: 20px;
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            text-align: center;
        }
        .card h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        .card p {
            font-size: 18px;
            margin: 5px 0;
        }
        .qr-code {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>Carte de Fidélité</h1>
        <p><img src="{{$client->user->photo}}" alt="Photo"></p>
        <p>{{ $client->user->name }}</p>

 <p><img src="{{ $qrCodePath }}" alt="QR Code"></p>
        <p>Merci de votre fidélité!</p>
    </div>
</body>
</html>
