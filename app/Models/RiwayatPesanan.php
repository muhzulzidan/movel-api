<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatPesanan extends Model
{
    protected $table = 'riwayat_pesanan';
    protected $fillable = [
        'driver', 'passenger', 'total_seats_ordered', 'order_date', 'departure_date',
        'departure_time', 'tujuan', 'status', 'harga', 'rating', 'biaya_admin'
    ];
}
