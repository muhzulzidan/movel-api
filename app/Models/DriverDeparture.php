<?php

namespace App\Models;

use App\Models\Car;
use App\Models\Order;
use App\Models\Driver;
use App\Models\KotaKab;
use App\Models\LabelSeatCar;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class DriverDeparture extends Model
{
    use HasFactory;

    protected $fillable = ['driver_id', 'kota_asal_id', 'kota_tujuan_id', 'date_departure', 'time_departure'];

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class, 'driver_id', 'id');
    }

    public function car(): HasOneThrough
    {
        return $this->hasOneThrough(Car::class, Driver::class, 'id', 'driver_id', 'driver_id', 'id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'driver_departure_id', 'id');
    }

    public function kotaAsal(): BelongsTo
    {
        return $this->belongsTo(KotaKab::class, 'kota_asal_id', 'id');
    }

    public function kotaTujuan(): BelongsTo
    {
        return $this->belongsTo(KotaKab::class, 'kota_tujuan_id', 'id');
    }
}
