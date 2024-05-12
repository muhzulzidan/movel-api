<?php

namespace App\Models;

use App\Models\Driver;
use App\Models\Order;
use App\Models\Passenger;
use App\Models\RiwayatPesanan;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Log;


class User extends Authenticatable implements MustVerifyEmailContract
{
    use HasApiTokens, HasFactory, Notifiable, MustVerifyEmail;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = "users";

    protected $fillable = [
        'name',
        'email',
        'password',
        'no_hp',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];



    public function sendEmailVerificationNotification()
    {
        Log::info('Sending email verification notification to ' . $this->email);
        $this->notify(new VerifyEmail);
        Log::info('Email verification notification sent to ' . $this->email);
    }

    public function sendPasswordResetNotification($token)
    {
        $url = 'https://admin.movel.id/reset_password?token=' . $token;
        $this->notify(new ResetPasswordNotification($url));
    }

    public function passenger()
    {
        return $this->hasOne(Passenger::class);
    }

    public function driver()
    {
        return $this->hasOne(Driver::class);
    }

    public function drivers()
    {
        return $this->hasMany(Driver::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function role()  
    {
        return $this->belongsTo(Role::class);
    }

    public function riwayatPesanan()
{
    return $this->hasMany(RiwayatPesanan::class, 'user_id');
}
}
