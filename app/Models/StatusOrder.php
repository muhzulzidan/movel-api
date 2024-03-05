<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusOrder extends Model
{
    use HasFactory;

    protected $fillable = ['status_label', 'status_name'];

    public $incrementing = false;
    protected $primaryKey = 'status_label';
    protected $keyType = 'int';

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
