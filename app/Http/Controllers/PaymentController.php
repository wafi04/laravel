<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Booking;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PaymentController extends Controller
{
    /**
     * Menampilkan semua payment dan relasi  ke booking
     */
    public function index()
    {
        $payments = Payment::with('booking')->paginate(10);
        
        return response()->json([
            'status' => 'success',
            'data' => $payments
        ]);
    }


    public function create()
    {
        $gedungs = Payment::available()->get();
        return view('payment.create', compact('payments'));
    }
    
public function generateQrisQrCode($bookingId, $totalHarga) {
    try {
        Log::info('QRIS Generation Attempt', [
            'bookingId' => $bookingId, 
            'totalHarga' => $totalHarga
        ]);

        if (!$bookingId || !$totalHarga) {
            throw new \Exception('Invalid booking ID or total price');
        }

        $qrisPayload = "http://localhost:8000/payment/{$bookingId}/{$totalHarga}";

        $qrCode = QrCode::create($qrisPayload)
            ->setEncoding(new Encoding('UTF-8'))
            ->setSize(300)
            ->setMargin(10);

        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        Storage::makeDirectory('public/qrcodes');

        $filename = 'qris_' . uniqid() . '_' . $bookingId . '.png';
        $qrCodePath = 'qrcodes/' . $filename;

        $saveResult = Storage::put('public/' . $qrCodePath, $result->getString());

        Log::info('QR Code Generation Success', [
            'path' => $qrCodePath,
            'saveResult' => $saveResult
        ]);

        return response()->json([
            'qrCodePath' => Storage::url($qrCodePath)
        ]);

    } catch (\Exception $e) {
        Log::error('QRIS QR Code Generation Error', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'error' => 'QR Code Generation Failed',
            'details' => $e->getMessage()
        ], 500);
    }
}
protected function generateQrisPayload($bookingId, $totalHarga) {
    return "QRIS-{$bookingId}-{$totalHarga}";
}
    
public function store(Request $request) {
    // Validasi request
    $validator = Validator::make($request->all(), [
        'booking_id' => 'required|exists:bookings,id',
        'harga' => [
            'required',
            'numeric',
            'min:0',
            function ($attribute, $value, $fail) {
                if ($value > 999999999999.99) {
                    $fail('The '.$attribute.' must not exceed 999,999,999,999.99');
                }
            }
        ],
        'metode' => [
            'required',
            Rule::in(array_keys(Payment::METODE))
        ]
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->with('error', $validator->errors()->first());
    }

    try {
        return DB::transaction(function () use ($request) {
            $booking = Booking::findOrFail($request->booking_id);

            if ($booking->status === 'CONFIRMED') {
                throw new \Exception('Booking sudah dikonfirmasi');
            }

            $harga = is_string($request->harga)
                ? str_replace([',', ' '], ['', ''], $request->harga)
                : $request->harga;

            // Buat pembayaran
            $payment = Payment::create([
                'booking_id' => $request->booking_id,
                'user_id' => Auth::id(),
                'harga' => $harga,
                'metode' => $request->metode
            ]);

            $booking->update([
                'status' => 'CONFIRMED'
            ]);

            // Redirect with success message
            return redirect()->route('bookings.my-bookings')
                ->with('success', 'Pembayaran berhasil dilakukan');

        });
    } catch (\Exception $e) {
        // Redirect with error message
        return redirect()->back()
            ->with('error', 'Gagal membuat pembayaran: ' . $e->getMessage());
    }
}

    /**
     * Display the specified payment.
     */
    public function show($id)
    {
        $payment = Payment::with('booking')->find($id);

        if (!$payment) {
            return response()->json([
                'status' => 'error',
                'message' => 'Payment not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $payment
        ]);
    }

    /**
     * Update the specified payment.
     */
    public function update(Request $request, $id)
    {
        $payment = Payment::find($id);

        if (!$payment) {
            return response()->json([
                'status' => 'error',
                'message' => 'Payment not found'
            ], 404);
        }

        // Validate the request
        $validator = Validator::make($request->all(), [
            'booking_id' => 'sometimes|required|exists:bookings,id',
            'harga' => 'sometimes|required|numeric|min:0',
            'metode' => [
                'sometimes',
                'required', 
                Rule::in(array_keys(Payment::METODE))
            ]
        ]);

        // Check validation
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            // Update payment
            $payment->update($request->only(['booking_id', 'harga', 'metode']));

            return response()->json([
                'status' => 'success',
                'message' => 'Payment updated successfully',
                'data' => $payment
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update payment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified payment.
     */
    public function destroy($id)
    {
        $payment = Payment::find($id);

        if (!$payment) {
            return response()->json([
                'status' => 'error',
                'message' => 'Payment not found'
            ], 404);
        }

        try {
            $payment->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Payment deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete payment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getPaymentMethods()
    {
        return response()->json([
            'status' => 'success',
            'data' => Payment::METODE
        ]);
    }
}