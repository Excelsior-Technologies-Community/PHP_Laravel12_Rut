<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1">

    <title>PHP Laravel12 Rut</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css"
        rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js">
    </script>

    <style>
        body {
            background: #f8fafc;
        }

        .navbar-brand {
            font-weight: bold;
        }

        .card {
            border: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .08);
        }

        .table th {
            vertical-align: middle;
        }

        .dropzone {
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            background: #fafafa;
        }

        .dropzone .dz-message {
            font-size: 1.1rem;
            color: #6c757d;
        }

        .stat-card {
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
        }

        .qr-section img {
            max-width: 200px;
        }
    </style>

</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">

        <div class="container">

            <a class="navbar-brand"
                href="{{ route('dashboard') }}">

                PHP Laravel12 Rut

            </a>

            <button class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarNav">

                <span class="navbar-toggler-icon"></span>

            </button>

            <div class="collapse navbar-collapse"
                id="navbarNav">

                <ul class="navbar-nav ms-auto">

                    <li class="nav-item">

                        <a class="nav-link"
                            href="{{ route('dashboard') }}">

                            Dashboard

                        </a>

                    </li>

                    <li class="nav-item">

                        <a class="nav-link"
                            href="{{ route('citizens.index') }}">

                            Citizens

                        </a>

                    </li>

                    <li class="nav-item">

                        <a class="nav-link"
                            href="{{ route('citizens.create') }}">

                            Add Citizen

                        </a>

                    </li>

                    <li class="nav-item">

                        <a class="nav-link"
                            href="{{ route('generator') }}">

                            Generate RUT

                        </a>

                    </li>

                    <li class="nav-item">

                        <a class="nav-link"
                            href="{{ route('import.create') }}">

                            Bulk Import

                        </a>

                    </li>

                    <li class="nav-item">

                        <a class="nav-link"
                            href="{{ route('citizens.trash') }}">

                            Trash
                            @if(isset($trashedCount) && $trashedCount > 0)
                                <span class="badge bg-danger">{{ $trashedCount }}</span>
                            @endif
                        </a>

                    </li>

                    <li class="nav-item">

                        <a class="nav-link"
                            href="{{ route('citizens.report') }}">

                            Report

                        </a>

                    </li>

                </ul>

            </div>

        </div>

    </nav>

    <div class="container py-4">

        @if(session('success'))

        <div class="alert alert-success">

            {{ session('success') }}

        </div>

        @endif

        @if(session('error'))

        <div class="alert alert-danger">

            {{ session('error') }}

        </div>

        @endif

        @yield('content')

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js">
    </script>

    @stack('scripts')

</body>

</html>
