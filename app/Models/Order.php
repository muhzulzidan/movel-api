<?php

namespace App\Models;

use App\Models\DriverDeparture;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'driver_departure_id',
        'status_order_id',
        'price_order',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function driverDeparture(): BelongsTo
    {
        return $this->belongsTo(DriverDeparture::class, 'driver_departure_id', 'id');
    }

    public function labelSeatCars()
    {
        return $this->hasMany(LabelSeatCar::class);
    }

    public function statusOrder(): BelongsTo
    {
        return $this->belongsTo(StatusOrder::class, 'status_order_id', 'id');
    }
}
