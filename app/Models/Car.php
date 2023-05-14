<?php

namespace App\Models;

use App\Models\Driver;
use App\Models\LabelSeatCar;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Car extends Model
{
    use HasFactory;

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class, 'driver_id', 'id');
    }

    public function labelSeats()
    {
        return $this->hasMany(LabelSeatCar::class);
    }

    // public function driverDeparture(): HasOneThrough
    // {
    //     return $this->hasOneThrough(DriverDeparture::class, Driver::class, 'id', 'driver_id', 'driver_id', 'id');
    // }

}
