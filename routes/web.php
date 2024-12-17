<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\GedungController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;


Route::get('/', [GedungController::class, 'welcome'])->name('welcome');


// Rute yang memerlukan autentikasi
Route::middleware(['auth'])->group(function () {
  
    Route::middleware(['role:admin'])->group(function () {
                Route::get('/dashboard', [BookingController::class, 'dashboard'])->name('dashboard');
                Route::get('/dashboard/jadwal', [BookingController::class, 'index'])->name('dashboard-jadwal');
                Route::prefix('dashboard/gedung')->name('dashboard.gedung.')->group(function () {
                    Route::get('/', [GedungController::class, 'index'])->name('index');
                Route::get('/create', [GedungController::class, 'create'])->name('create');
                Route::post('/', [GedungController::class, 'store'])->name('store');
                Route::get('/{gedung}', [GedungController::class, 'show'])->name('show');
                Route::get('/{gedung}/edit', [GedungController::class, 'edit'])->name('edit');
                Route::put('/{gedung}', [GedungController::class, 'update'])->name('update');
                Route::delete('/{gedung}', [GedungController::class, 'destroy'])->name('destroy');
            });
    });

    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::get('/', [BookingController::class, 'index'])->name('index');
        Route::get('/create', [BookingController::class, 'create'])->name('create');
        Route::post('/', [BookingController::class, 'store'])->name('store');
        
        Route::delete('/{booking}', [BookingController::class, 'destroy'])->name('destroy');
        Route::patch('/{booking}/update-status', [BookingController::class, 'updateStatus'])->name('update-status');
        
        Route::get('/my-bookings', [BookingController::class, 'myBookings'])->name('my-bookings');
        Route::post('/{booking}/cancel', [BookingController::class, 'cancel'])->name('cancel');
    });

    Route::prefix('payments')->name('payment.')->group(function()  {
        Route::post('/',[PaymentController::class,'store'])->name('store');
        Route::post('/create',[PaymentController::class,'create'])->name('create');
        Route::get('/generate-qris-qrcode/{bookingId}/{totalHarga}', 
    [PaymentController::class, 'generateQrisQrCode'])
    ->name('generate-qris-qrcode');
    });


    // User Profile
    Route::get('/profile', [AuthController::class, 'getUser'])->name('user.profile');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Rute publik untuk gedung
Route::get('/gedung', [GedungController::class, 'index'])->name('gedung.index');
Route::get('/gedung/{gedung}', [GedungController::class, 'show'])->name('gedung.show');

// Tetap sertakan auth routes
require __DIR__.'/auth.php';