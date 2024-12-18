<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GedungKu</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold text-danger fs-4" href="/">
            GedungKu
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav gap-2 align-items-center">
                @auth
                    @if(auth()->user()->role === 'admin')
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}" class="btn btn-danger text-white fw-semibold">
                                Dashboard
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a href="{{ route('bookings.my-bookings') }}" class="nav-link text-dark fw-semibold">
                                My Bookings
                            </a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-link text-secondary text-decoration-none fw-semibold">
                                Logout
                            </button>
                        </form>
                    </li>
                @else
                    <!-- Guest User -->
                    <li class="nav-item">
                        <a href="{{ route('login') }}" class="nav-link text-dark fw-semibold">
                            Masuk
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('register') }}" class="btn btn-danger text-white fw-semibold">
                            Daftar
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

   <div class="min-vh-50">
    <header class="bg-danger py-5">
        <div class="container">
            <div class="row align-items-center g-4">
                <!-- Search Form -->
                <div class="col-lg-6">
                    <div class="card bg-dark bg-opacity-25 border-light">
                        <div class="card-body p-4">
                            <h2 class="text-white fw-bold mb-4">Temukan Gedung Impian Anda</h2>
                            <form method="GET" action="{{ route('welcome') }}">
                                <div class="mb-4">
                                    <label for="date" class="form-label text-white">Tanggal Booking</label>
                                    <input 
                                        type="date" 
                                        name="date" 
                                        id="date"
                                        value="{{ request('date') }}"
                                        min="{{ date('Y-m-d') }}"
                                        class="form-control form-control-lg bg-dark bg-opacity-25 text-white border-light"
                                        required
                                    >
                                </div>
                                <button type="submit" class="btn btn-warning btn-lg w-100 fw-bold">
                                    Cari Gedung Sekarang
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 ">
                    <img 
                        src="https://plus.unsplash.com/premium_photo-1680777484547-de735ff024a4?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8Z2VkdW5nfGVufDB8fDB8fHww"
                        alt="Event Venue"
                        class="img-fluid rominded shadow"
                    />
                </div>
            </div>
        </div>
    </header>

    <!-- Gedung Section -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold mb-3">
                    {{ request('date') ? 'Gedung Tersedia' : 'Gedung Terbaru' }}
                </h2>
                <p class="text-muted">
                    {{ request('date') 
                        ? 'Gedung yang tersedia pada tanggal ' . \Carbon\Carbon::parse(request('date'))->isoFormat('DD MMMM YYYY')
                        : 'Temukan gedung-gedung terbaru yang telah bergabung dengan platform kami' 
                    }}
                </p>
            </div>
            
            <!-- Gedung Grid -->
            <div class="row g-4">
                @forelse($gedungs as $gedung)
                    <div class="col-md-6 col-lg-4 col-xl-3">
                        <div class="card h-100 shadow-sm">
                            <!-- Gedung Image -->
                            <div class="ratio ratio-16x9">
                                <img src="https://images.unsplash.com/photo-1706264473113-3036fd904c35?w=400&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8N3x8Z2VkdW5nfGVufDB8fDB8fHww" 
                                     class="card-img-top" 
                                     alt="{{ $gedung->name }}"> 
                            </div>
                            <div class="card-body">
                                <h5 class="card-title fw-bold">{{ $gedung->name }}</h5>
                                <p class="card-text text-muted small">
                                    {{ Str::limit($gedung->deskripsi, 100) }}
                                </p>
                                <hr>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-danger fw-bold">
                                        Rp {{ number_format($gedung->harga, 0, ',', '.') }}
                                    </span>
                                    <a href="{{ route('gedung.show', $gedung) }}" 
                                       class="btn btn-outline-danger btn-sm">
                                        Detail 
                                        <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5 bg-light rounded">
                            <i class="bi bi-building-x fs-1 text-muted"></i>
                            <p class="mt-3 text-muted">
                                {{ request('date') 
                                    ? 'Tidak ada gedung yang tersedia pada tanggal yang dipilih'
                                    : 'Belum ada gedung tersedia'
                                }}
                            </p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <footer class="bg-dark text-light py-5">
        <div class="container">
            <div class="text-center">
                <h3 class="text-danger fw-bold mb-3">GedungKu</h3>
                <p class="text-muted mb-4">
                    Platform terbaik untuk menyewa dan menyewakan gedung dengan mudah, 
                    cepat, dan aman di seluruh Indonesia.
                </p>
                <hr class="border-secondary">
                <p class="text-muted small mt-4">
                    &copy; {{ date('Y') }} GedungKu. All rights reserved.
                </p>
            </div>
        </div>
    </footer>
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>