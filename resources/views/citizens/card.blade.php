<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>Citizen Card - {{ $citizen->name }}</title>

    <style>

        body {

            font-family: DejaVu Sans, sans-serif;

            margin: 0;

            padding: 0;

        }

        .card-container {

            width: 350px;

            margin: 40px auto;

            border: 2px solid #333;

            border-radius: 15px;

            padding: 25px;

            text-align: center;

            background: #fff;

        }

        .card-header {

            border-bottom: 1px solid #eee;

            padding-bottom: 15px;

            margin-bottom: 15px;

        }

        .card-header h2 {

            margin: 0;

            font-size: 1.4rem;

            color: #333;

        }

        .card-body p {

            margin: 8px 0;

            font-size: 0.95rem;

        }

        .card-body strong {

            display: inline-block;

            width: 80px;

            text-align: left;

        }

        .qr-section {

            margin-top: 20px;

            padding-top: 15px;

            border-top: 1px dashed #ccc;

        }

        .qr-section img {

            width: 140px;

            height: 140px;

        }

        .rut-highlight {

            font-size: 1.2rem;

            font-weight: bold;

            color: #0d6efd;

            margin-top: 5px;

        }

    </style>

</head>

<body>

    <div class="card-container">

        <div class="card-header">

            <h2>Citizen ID Card</h2>

            <p class="text-muted mb-0">Official Record</p>

        </div>

        <div class="card-body">

            <p><strong>Name:</strong> {{ $citizen->name }}</p>

            <p><strong>Email:</strong> {{ $citizen->email }}</p>

            <p><strong>RUT:</strong> {{ $citizen->rut }}</p>

            <p><strong>Num:</strong> {{ $citizen->rut_num }}</p>

            <p><strong>VD:</strong> {{ $citizen->rut_vd }}</p>

            <p><strong>ID:</strong> #{{ $citizen->id }}</p>

            <p><strong>Added:</strong> {{ $citizen->created_at->format('Y-m-d') }}</p>

        </div>

        <div class="qr-section">

            {!! $qrCode !!}

            <div class="rut-highlight">{{ $citizen->rut }}</div>

            <p class="text-muted small mt-2">Scan to verify</p>

        </div>

    </div>

</body>

</html>
