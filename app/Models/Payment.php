<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{

    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
        use HasFactory;


         protected $table = 'payments';

    protected $fillable = [
        'booking_id',
        'user_id',  // Tambahkan user_id
        'harga',
        'metode'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'harga' => 'float',
        'created_at' => 'datetime',
        'metode' => 'string'
    ];

    /**
     * Enum for payment methods
     */
    public const METODE = [
        'CASH' => 'CASH',
        'QRIS' => 'QRIS',
        'TRANSFER' => 'TRANSFER'
    ];

    /**
     * Get the booking associated with the payment
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'id');
    }

    public function scopePaid($query)
{
    return $query->where('booking_id', $this->booking_id);
}
    /**
     * Get the user associated with the payment
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}