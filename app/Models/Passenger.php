<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;



class Passenger extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = "passengers";

    protected $fillable = [
        'user_id',
        'address',
        'photo',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
