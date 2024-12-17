<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    protected $table = 'bookings';


    protected $fillable = [
        'user_id', 
        'gedung_id', 
        'start_date', 
        'end_date', 
        'status', 
        'total_harga'
    ];

    protected $dates = [
        'start_date', 
        'end_date'
    ];

    // Relasi dengan User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi dengan Gedung
    public function gedung()
    {
        return $this->belongsTo(Gedung::class);
    }
   public function payments()
{
    return $this->hasMany(Payment::class);
}

    //  untuk  update status ketika create booking
    public function scopePending($query)
    {
        return $query->where('status', 'PENDING');
    }

    //     update untuk admin jika ada booking sudah
    
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'CONFIRMED');
    }
    
    public function scopeCompleted($query)
    {
        return $query->where('status', 'COMPLETED');
    }
    
   

    public function scopeCancelled($query)
    {
        return $query->where('status', 'CANCELLED');
    }
}