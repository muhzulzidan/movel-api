<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabelSeatCar extends Model
{
    use HasFactory;

    protected $fillable = [
        'label_seat',
        'is_filled',
        'car_id',
        'user_id',
    ];
}
