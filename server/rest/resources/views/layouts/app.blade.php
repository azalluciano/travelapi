<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Honeymoon Destinations') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding-top: 20px;
        }

        .destination-card {
            height: 100%;
            transition: transform 0.3s;
        }

        .destination-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .card-img-top {
            height: 200px;
            object-fit: cover;
        }
    </style>
</head>

<body>
    <div class="container">
        <header class="d-flex flex-wrap justify-content-between py-3 mb-4 border-bottom">
            <a href="{{ route('admin.login') }}"
                class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-dark text-decoration-none">
                <span class="fs-4">Honeymoon Destinations</span>
            </a>
            <ul class="nav nav-pills">
                {{-- <li class="nav-item"><a href="{{ route('home') }}" class="nav-link" aria-current="page">Home</a></li> --}}
                @auth
                    <li class="nav-item"><a href="{{ route('admin.destinations.index') }}" class="nav-link">Admin
                            Dashboard</a></li>
                    <li class="nav-item">
                        <form action="{{ route('admin.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="nav-link bg-transparent border-0">Logout</button>
                        </form>
                    </li>
                @else
                    <li class="nav-item"><a href="{{ route('admin.login') }}" class="nav-link">Admin Login</a></li>
                    <li class="nav-item"><a href="{{ route('admin.register') }}" class="nav-link">Admin Register</a></li>
                @endauth
            </ul>
        </header>
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @yield('content')
        <footer class="pt-4 my-md-5 pt-md-5 border-top">
            <div class="row">
                <div class="col-12 col-md text-center">
                    <p>&copy; {{ date('Y') }} Honeymoon Destinations. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>