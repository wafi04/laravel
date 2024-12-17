<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gedung extends Model
{
    use HasFactory;

    protected $table = 'gedungs';

    protected $fillable = [
        'name',
        'alamat',
        'harga',
        'deskripsi',
        'kapasitas',
        'ketersediaan',
        'user_id', 
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }    
}