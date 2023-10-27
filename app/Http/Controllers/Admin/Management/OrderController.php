<?php

namespace App\Http\Controllers\Admin\Management;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\KotaKab;
use App\Models\Order;
use App\Models\StatusOrder;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        // Mau juga ditampilkan di API JSON
        $orders = Order::join('users', 'orders.user_id', '=', 'users.id')
            ->join('driver_departures', 'orders.driver_departure_id', '=', 'driver_departures.id')
            ->join('drivers', 'driver_departures.driver_id', '=', 'drivers.id')
            ->join('kota_kabs', 'driver_departures.kota_asal_id', '=', 'kota_kabs.id')
            ->join('passengers', 'users.id', '=', 'passengers.user_id')
            ->leftJoin('status_orders', 'orders.status_order_id', '=', 'status_orders.id')
            ->select('users.id as user_id', 'users.name as passenger_name', 'passengers.photo as passenger_photo', 'users.email as passenger_email', 'driver_departures.created_at as tgl_pemesanan',
                'drivers.id as driver_id', 'users.name as driver_name', 'drivers.photo as driver_photo', 'users.email as driver_email',
                'orders.*', 'driver_departures.*', 'status_orders.*', 'kota_kabs.*')
            ->get();

        $kotaAsalData = KotaKab::all();
        $kotaTujuanData = KotaKab::all();
        $dataDriver = Driver::with('user')->get();
        $statusOrder = StatusOrder::all();

        $orderBerhasil = Order::select(DB::raw('COUNT(*) as count'))
            ->where('status_order_id', 7) // Ganti dengan id yang diinginkan
            ->first();
        $orderBerlangsung = Order::select(DB::raw('COUNT(*) as count'))
            ->whereNotIn('status_order_id', [1, 7, 9]) // Ganti dengan id yang diinginkan
            ->first();
        $orderGagal = Order::select(DB::raw('COUNT(*) as count'))
            ->where('status_order_id', [1, 9]) // Ganti dengan id yang diinginkan
            ->first();

        return view('admin.management.riwayat_pesanan.order', compact('orders', 'kotaAsalData', 'kotaTujuanData', 'dataDriver', 'statusOrder', 'orderBerhasil', 'orderBerlangsung', 'orderGagal'));
    }
}
