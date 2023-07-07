<?php

namespace App\Http\Controllers\Admin\Management;

use App\Http\Controllers\Controller;
use App\Models\User;

class OrderController extends Controller
{
    public function index()
    {
        // $orders = User::join('orders', 'users.id', '=', 'orders.user_id')
        //     ->join('driver_departures', 'orders.id', '=', 'driver_departures.id')
        //     ->leftJoin('status_orders', 'orders.id', '=', 'status_orders.id')
        //     ->select('users.*', 'orders.*', 'driver_departures.*', 'status_orders.*')
        //     ->get();

        // return view('admin.management.riwayat_pesanan.order', compact('orders'));

        $orders = User::join('orders', 'users.id', '=', 'orders.user_id')
            ->join('driver_departures', 'orders.id', '=', 'driver_departures.id')
            ->join('drivers', 'driver_departures.driver_id', '=', 'drivers.id')
            ->join('kota_kabs', 'driver_departures.kota_asal_id', '=', 'kota_kabs.id')
            ->leftJoin('status_orders', 'orders.id', '=', 'status_orders.id')
            ->select('users.*', 'orders.*', 'driver_departures.*', 'status_orders.*', 'drivers.*', 'kota_kabs.*')
            ->get();

        return view('admin.management.riwayat_pesanan.order', compact('orders'));
    }
}
