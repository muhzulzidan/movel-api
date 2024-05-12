<?php

namespace App\Models;

use App\Models\DriverDeparture;
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

    public function driverDeparturesAsal()
    {
        return $this->hasMany(DriverDeparture::class, 'kota_asal_id');
    }

    public function driverDeparturesTujuan()
    {
        return $this->hasMany(DriverDeparture::class, 'kota_tujuan_id');
    }
}
