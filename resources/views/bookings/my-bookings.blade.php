<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Saya - GedungKu</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f4f4; }
        .booking-card { transition: transform 0.3s, box-shadow 0.3s; }
        .booking-card:hover { 
            transform: translateY(-5px); 
            box-shadow: 0 10px 20px rgba(0,0,0,0.1); 
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
        <div class="card shadow-lg">
            <div class="card-body">
                <h1 class="card-title mb-4">Booking Saya</h1>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($bookings->isEmpty())
                    <div class="text-center py-5">
                        <p class="text-muted">Anda belum memiliki booking.</p>
                    </div>
                @else
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                        @foreach($bookings as $booking)
                            <div class="col">
                                <div class="card booking-card h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <h5 class="card-title text-primary">
                                                <a href="{{ route('gedung.show', $booking->gedung) }}" class="text-decoration-none">
                                                    {{ $booking->gedung->name }}
                                                </a>
                                            </h5>
                                            <div class="d-flex flex-column align-items-end">
                                                @if($booking->is_paid)
                                                    <span class="badge bg-success mb-1">Sudah Dibayar</span>
                                                @endif
                                                <span class="badge bg-secondary">{{ $booking->status }}</span>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="text-muted">Tanggal Mulai</span>
                                                <span>{{ optional($booking->start_date)->format('d M Y H:i') ?? 'N/A' }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="text-muted">Tanggal Selesai</span>
                                                <span>{{ optional($booking->end_date)->format('d M Y H:i') ?? 'N/A' }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span class="text-muted">Total Harga</span>
                                                <span class="fw-bold">
                                                    Rp {{ number_format($booking->total_harga, 0, ',', '.') }}
                                                </span>
                                            </div>
                                        </div>

                                        @if($booking->is_paid)
                                            <div class="bg-light p-3 rounded">
                                                <h6 class="text-success mb-2">Detail Pembayaran</h6>
                                                @foreach($booking->payments as $payment)
                                                    <div class="d-flex justify-content-between small text-muted">
                                                        <span>{{ $payment->metode }}</span>
                                                        <span>{{ $payment->created_at->setTimezone('Asia/Jakarta')->locale('id')->translatedFormat('d F Y H:i') }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif

                                        @if($booking->status == 'PENDING')
                                            <div class="card-footer bg-white border-0 pt-3 d-flex justify-content-between">
                                                
                                                    
                                                    <form action="{{ route('bookings.destroy', $booking->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger btn-sm"
                                                                onclick="return confirm('Apakah Anda yakin ingin menghapus booking ini?')">
                                                            Cancel
                                                        </button>
                                                    </form>
                                                
                                                <button 
                                                    data-booking-id="{{ $booking->id }}"
                                                    data-total-harga="{{ $booking->total_harga }}"
                                                    class="btn btn-primary btn-sm add-to-payment-btn"
                                                    onclick="openPaymentModal(this)">
                                                    Bayar Sekarang
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLabel">Konfirmasi Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="paymentForm" action="{{ route('payment.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="booking_id" id="modalBookingId">
                        
                        <div class="mb-3">
                            <label class="form-label">Total Harga</label>
                            <input type="text" 
                                   id="modalTotalHarga" 
                                   name="harga" 
                                   readonly 
                                   class="form-control bg-light">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Metode Pembayaran</label>
                            <select name="metode" class="form-select" required>
                                <option value="">Pilih Metode Pembayaran</option>
                                <option value="CASH">CASH</option>
                                <option value="TRANSFER_BANK">Transfer Bank</option>
                                <option value="QRIS">QRIS</option>
                            </select>
                        </div>

                        <div id="qris-container" class="d-none"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Konfirmasi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function openPaymentModal(element) {
            const bookingId = element.getAttribute('data-booking-id');
            const totalHarga = element.getAttribute('data-total-harga');
            
            document.getElementById('modalBookingId').value = bookingId;
            document.getElementById('modalTotalHarga').value = 'Rp ' + new Intl.NumberFormat('id-ID').format(totalHarga);
            
            document.getElementById('paymentForm').addEventListener('submit', function(event) {
                let hargaInput = document.getElementById('modalTotalHarga');
                let cleanedHarga = hargaInput.value.replace('Rp ', '').replace(/\./g, '');
                hargaInput.value = cleanedHarga;
            });
            
            var paymentModal = new bootstrap.Modal(document.getElementById('paymentModal'));
            paymentModal.show();
        }

        document.addEventListener('DOMContentLoaded', function() {
            const paymentMethodSelect = document.querySelector('select[name="metode"]');
            const qrisContainer = document.getElementById('qris-container');
            let qrisGenerationTimeout = null;

            paymentMethodSelect.addEventListener('change', function() {
                if (qrisGenerationTimeout) {
                    clearTimeout(qrisGenerationTimeout);
                }

                qrisContainer.classList.add('d-none');
                qrisContainer.innerHTML = '';

                if (this.value === 'QRIS') {
                    qrisGenerationTimeout = setTimeout(() => {
                        generateQRISQRCode();
                    }, 1000);
                }
            });

            function generateQRISQRCode() {
                const bookingId = document.getElementById('modalBookingId').value;
                const hargaInput = document.getElementById('modalTotalHarga');
                
                const cleanedHarga = parseFloat(
                    hargaInput.value
                        .replace('Rp ', '')
                        .replace(/\./g, '')
                        .replace(',', '.')
                );

                if (isNaN(cleanedHarga) || cleanedHarga <= 0) {
                    alert('Harga tidak valid');
                    return;
                }
                
                fetch(`/payments/generate-qris-qrcode/${bookingId}/${cleanedHarga}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(error => {
                            throw new Error(error.details || 'QR Code Generation Failed');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    qrisContainer.innerHTML = `
                        <div class="text-center p-4 bg-light rounded">
                            <h3 class="mb-3">Scan QRIS</h3>
                            <img src="${data.qrCodePath}" alt="QRIS QR Code" class="img-fluid mx-auto mb-3" style="max-width: 250px;">
                            <div class="text-muted small">
                                Akan submit dalam 10 detik
                            </div>
                        </div>
                    `;
                    qrisContainer.classList.remove('d-none');
                })
                .catch(error => {
                    console.error('QR Code Generation Error:', error);
                    alert('Gagal membuat QR Code: ' + error.message);
                });
            }
        });
    </script>
</body>
</html>