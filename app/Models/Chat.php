<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'receiver_id'];
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function statusOrder()
    {
        return $this->belongsTo(StatusOrder::class, 'status_order_id');
    }
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}