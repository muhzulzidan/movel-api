<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KotaKab extends Model
{
    use HasFactory;

    // Memberikan izin mengisi nama_kota dan description
    protected $fillable = [
        'nama_kota',
        'description',
    ];

    // Agar created_at dan updated_at terisi otomatis
    public $timestamps = true;
}
