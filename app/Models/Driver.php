<?php

namespace App\Models;

use App\Models\Car;
use App\Models\DriverDeparture;
use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Driver extends Model
{
    use HasFactory;

    protected $table = "drivers";

    protected $fillable = [
        'user_id',
        'address',
        'photo',
        'is_smoking',
        'driver_age',
        'no_ktp',
        'foto_ktp',
        'foto_sim',
        'foto_stnk',
    ];

    protected $with = ['user_driver:id,name'];

    public function user_driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function driver_departures(): HasMany
    {
        return $this->hasMany(DriverDeparture::class, 'driver_id', 'id');
    }

    public function driverDeparture(): HasOne
    {
        return $this->hasOne(DriverDeparture::class, 'driver_id', 'id');
    }

    public function car(): HasOne
    {
        return $this->hasOne(Car::class, 'driver_id', 'id');
    }

    protected function photo(): Attribute
    {
        return Attribute::make(
            get:fn($photo) => asset('/storage/photos/' . $photo),
        );
    }
}
