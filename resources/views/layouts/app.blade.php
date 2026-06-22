<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1">

    <title>PHP Laravel12 Rut</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

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
    </style>

</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">

        <div class="container">

            <a class="navbar-brand"
                href="{{ route('citizens.index') }}">

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

        @yield('content')

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>