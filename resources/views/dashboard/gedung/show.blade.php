<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $gedung->name }} - GedungKu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f4;
        }
    </style>
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

    <div class="container py-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <h1 class="card-title mb-4">{{ $gedung->name }}</h1>
                
                <div class="row">
                    <div class="col-md-6">
                        <h2 class="h4 mb-3">Detail Gedung</h2>
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold">Alamat</td>
                                <td>{{ $gedung->alamat }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Harga</td>
                                <td>Rp {{ number_format($gedung->harga, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Kapasitas</td>
                                <td>{{ $gedung->kapasitas }} orang</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Ketersediaan</td>
                                <td>{{ $gedung->ketersediaan }}</td>
                            </tr>
                        </table>
                    </div>
                    
                    <div class="col-md-6">
                        <h2 class="h4 mb-3">Deskripsi</h2>
                        <p class="text-muted">{{ $gedung->deskripsi }}</p>
                    </div>
                </div>

                <div class="mt-4">
                    @auth
                        @if(auth()->id() == $gedung->user_id)
                            <a href="{{ route('gedung.edit', $gedung) }}" class="btn btn-primary me-2">
                                Edit Gedung
                            </a>
                            <form action="{{ route('gedung.destroy', $gedung) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus gedung ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    Hapus Gedung
                                </button>
                            </form>
                        @else
                            @if($gedung->ketersediaan === 'Tersedia')
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#bookingModal">
                                    Booking Sekarang
                                </button>
                            @endif
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn btn-success">
                            Login untuk Booking
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Booking Modal -->
    <div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="bookingForm" action="{{ route('bookings.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="gedung_id" value="{{ $gedung->id }}">

                    <div class="modal-header">
                        <h5 class="modal-title" id="bookingModalLabel">Booking Gedung</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="start_date" class="form-label">Tanggal Mulai</label>
                            <input type="datetime-local" name="start_date" id="start_date" required class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="end_date" class="form-label">Tanggal Selesai</label>
                            <input type="datetime-local" name="end_date" id="end_date" required class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="total_harga" class="form-label">Total Harga</label>
                            <input type="number" name="total_harga" id="total_harga" value="{{ $gedung->harga }}" readonly class="form-control bg-light">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Booking</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('start_date').addEventListener('change', calculateTotalPrice);
        document.getElementById('end_date').addEventListener('change', calculateTotalPrice);

        function calculateTotalPrice() {
            const startDate = new Date(document.getElementById('start_date').value);
            const endDate = new Date(document.getElementById('end_date').value);
            const hargaPerHari = "{{ $gedung->harga }}";

            if (startDate && endDate && endDate > startDate) {
                const timeDiff = Math.abs(endDate.getTime() - startDate.getTime());
                const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24));
                const totalHarga = daysDiff * parseInt(hargaPerHari);
                
                document.getElementById('total_harga').value = totalHarga;
            }
        }

        document.getElementById('bookingForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const form = e.target;
            const formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'error') {
                    alert(data.message);
                } else {
                    window.location.href = "{{ route('bookings.my-bookings') }}";
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat melakukan booking');
            });
        });
    </script>
</body>
</html>