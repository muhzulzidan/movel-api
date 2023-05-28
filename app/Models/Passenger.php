<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Passenger extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "passengers";

    protected $fillable = [
        'user_id',
        'address',
        'photo',
        'gender',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected function photo(): Attribute
    {
        return Attribute::make(
            get:fn($photo) => asset('/storage/photos/' . $photo),
        );
    }
}
