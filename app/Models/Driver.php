<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Driver extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'address',
        'photo',
        'no_ktp',
        'foto_ktp',
        'foto_sim',
        'foto_stnk',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
