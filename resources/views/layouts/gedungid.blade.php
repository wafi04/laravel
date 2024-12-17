<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gedung Rental</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow-md">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <a href="{{ route('welcome') }}" class="text-2xl font-bold text-red-500">Gedung Rental</a>
            <div>
                @auth
                    <a href="{{ route('gedung.create') }}" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                        Tambah Gedung
                    </a>
                @else
                    <a href="{{ route('login') }}" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                        Login
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    @yield('content')

    <footer class="bg-gray-800 text-white py-4 mt-8">
        <div class="container mx-auto px-4 text-center">
            <p>&copy; {{ date('Y') }} Gedung Rental Platform</p>
        </div>
    </footer>
</body>
</html>