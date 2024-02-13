<?php

namespace App\Models\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = ['chat_id', 'user_id', 'message'];

    public function chat()
    {
        return $this->belongsTo('App\Models\Chat');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
