<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Gedung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    public function index(Request $request)
{
    $status = $request->input('status', 'all');

    $query = Booking::query()
        ->with([
            'user', 
            'gedung', 
            'payments' => function($query) {
                $query->latest();
            }
        ])
        ->when($status !== 'all', function($q) use ($status) {
            return $q->where('status', $status);
        })
        ->when(in_array($status, ['CONFIRMED', 'COMPLETED']), function($q) {
            return $q->whereHas('payments');
        })
        ->latest();

    $bookings = $query->get()->map(function ($booking) {
        $booking->start_date = \Carbon\Carbon::parse($booking->start_date);
        $booking->end_date = \Carbon\Carbon::parse($booking->end_date);
        return $booking;
    });

    return view('dashboard-jadwal', [
        'bookings' => $bookings,
        'selectedStatus' => $status,
        'stats' => [
            'total' => Booking::count(),
            'pending' => Booking::pending()->count(),
            'confirmed' => Booking::confirmed()->count(),
            'completed' => Booking::completed()->count(),
            'cancelled' => Booking::cancelled()->count(),
        ]
    ]);
}
public function dashboard()
{
    $gedungs = Gedung::latest()->take(4)->get();
    
    $bookings = Booking::with(['user', 'gedung', 'payments'])
        ->latest()
        ->take(5)
        ->get();

    $stats = [
        'total_gedung' => Gedung::count(),
        'total_bookings' => Booking::count(),
        'pending_bookings' => Booking::pending()->count(),
        'confirmed_bookings' => Booking::confirmed()->count(),
        'completed_bookings' => Booking::completed()->count(),
        'cancelled_bookings' => Booking::cancelled()->count(),
        'total_pendapatan' => Booking::where('status', 'COMPLETED')->sum('total_harga')
    ];

    return view('dashboard', [
        'gedungs' => $gedungs,
        'bookings' => $bookings,
        'stats' => $stats
    ]);
}

    public function create()
    {
        $gedungs = Gedung::available()->get();
        return view('bookings.create', compact('gedungs'));
    }
    public function getConfirmedBookingsWithPayments(Request $request)
    {
      $status = $request->input('status', 'all'); // Default semua status

    $query = Booking::query()
        ->with(['user', 'gedung', 'payments' => function($query) {
            $query->latest();
        }])
        ->latest();

    if ($status !== 'all') {
        $query->where('status', $status);
    }

    $bookings = $query->get()->map(function ($confirmedBooking) {
        $confirmedBooking->start_date = \Carbon\Carbon::parse($confirmedBooking->start_date);
        $confirmedBooking->end_date = \Carbon\Carbon::parse($confirmedBooking->end_date);
        return $confirmedBooking;
    });

    return view('dashboard-jadwal', [
        'bookings' => $bookings,
        'selectedStatus' => $status, 
        'stats' => [
            'total' => Booking::count(),
            'confirmed' => Booking::confirmed()->count(),
            'pending' => Booking::pending()->count(),
            'cancelled' => Booking::cancelled()->count(),
        ]
    ]);
    }

    
    public function store(Request $request)
    {
        if ($request->expectsJson()) {
        $validator = Validator::make($request->all(), [
            'gedung_id' => 'required|exists:gedungs,id',
            'start_date' => 'required|date|after:now',
            'end_date' => 'required|date|after:start_date',
            'total_harga' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
            ->withErrors($validator)
            ->withInput();
        }

        $isBooked = Booking::where('gedung_id', $request->gedung_id)
            ->where(function($query) use ($request) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                      ->orWhereBetween('end_date', [$request->start_date, $request->end_date]);
            })
            ->exists();

        if ($isBooked) {
    return response()->json([
        'status' => 'error',
        'message' => 'Gedung sudah dibooking pada tanggal tersebut'
    ], 422);
}

        if ($isBooked) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gedung sudah dibooking pada tanggal tersebut'
            ], 422);
        }

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'gedung_id' => $request->gedung_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_harga' => $request->total_harga,
            'status' => 'PENDING'
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'Booking berhasil',
            'redirect' => route('bookings.user-bookings')
        ]);
    }

    }
 function myBookings() {
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    $bookings = Booking::with(['gedung', 'payments' => function($query) {
            $query->where('user_id', auth()->id())
                  ->orderBy('created_at', 'desc');
        }])
        ->withCount('payments') // Menambahkan jumlah pembayaran
        ->where('user_id', auth()->id())
        ->orderBy('created_at', 'desc')
        ->get()
        ->map(function ($booking) {
            // Transformasi data
            $booking->start_date = \Carbon\Carbon::parse($booking->start_date);
            $booking->end_date = \Carbon\Carbon::parse($booking->end_date);
            
            // Cek apakah sudah dibayar
            $booking->is_paid = $booking->payments_count > 0;
            
            // Optional: Tentukan status pembayaran jika perlu
            $booking->payment_status = $this->determinePaymentStatus($booking);
            
            return $booking;
        });

    return view('bookings.my-bookings', compact('bookings'));
}

private function determinePaymentStatus($booking) {
    if ($booking->payments->isEmpty()) {
        return $booking->status === 'PENDING' ? 'Belum Dibayar' : 'Tidak Ada Pembayaran';
    }

    // Check the most recent payment
    $latestPayment = $booking->payments->first();

    switch ($latestPayment->status) {
        case 'PENDING':
            return 'Menunggu Konfirmasi';
        case 'SUCCESS':
            return 'Lunas';
        case 'FAILED':
            return 'Gagal';
        default:
            return 'Belum Lunas';
    }
}
    public function show(Booking $booking)
    {
        $this->authorize('view', $booking);
        return view('bookings.show', compact('booking'));
    }

    public function cancel(Booking $booking)
    {

        $booking->update(['status' => 'CANCELLED']);

        return redirect()->route('bookings.index')
            ->with('success', 'Booking berhasil dibatalkan');
    }

    public function updateStatus(Request $request, Booking $booking)
{
    $request->validate([
        'status' => 'required|in:CONFIRMED,COMPLETED,CANCELLED'
    ]);

    try {
        $booking->update([
            'status' => $request->status
        ]);

        return redirect()->back()->with('success', 'Status booking berhasil diperbarui');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Gagal memperbarui status: ' . $e->getMessage());
    }
}
    public function destroy(Booking $booking)
{

    if ($booking->status !== 'PENDING') {
        return redirect()->route('bookings.my-bookings')
            ->with('error', 'Hanya booking dengan status PENDING yang bisa dihapus');
    }

    $booking->delete();

    return redirect()->route('bookings.my-bookings')
        ->with('success', 'Booking berhasil dihapus');
}
}