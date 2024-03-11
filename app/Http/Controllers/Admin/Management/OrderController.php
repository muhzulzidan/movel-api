<?php

namespace App\Http\Controllers\Admin\Management;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\KotaKab;
use App\Models\Order;
use App\Models\StatusOrder;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\LabelSeatCar;
use App\Http\Resources\LabelSeatCarResource;

class OrderController extends Controller
{

    public function updateStatus($id, $status)
    {
        Log::info('Updating status for order id: ' . $id);
        Log::info('New status id: ' . $status);

        $order = Order::find($id);

        if (!$order) {
            return redirect()->route('order')->with('error', 'Order not found');
        }

        $order->status_order_id = $status;
        $order->save();

        // If the new status is the one that should trigger the reset
        if ($status == 7) {
            // Reset the is_filled field in the LabelSeatCar table
            LabelSeatCar::where('order_id', $id)->update(['is_filled' => 0, 'order_id' => null]);
        }

        return redirect()->route('order')->with('success', 'Order status updated successfully');

        Log::info('Order after update: ' . print_r($order, true));
    }

    public function index(Request $request)

    {
        DB::enableQueryLog();
        $status = $request->query('status');

        // dd($status);
        // Mau juga ditampilkan di API JSON


        $query = Order::with([
            'user:id,name,email', 
            'user.passenger:id,user_id',
            'driverDeparture.driver.user_driver:id,name,email'
        ])
        ->select('orders.id as order_id', 'orders.*');


        if ($status == 'berhasil') {
            $query->where('status_order_id', 7);

        } elseif ($status == 'berlangsung') {
            $query->whereNotIn('status_order_id', [1, 7, 9]);
          
        } elseif ($status == 'gagal') {
            $query->whereIn('status_order_id', [1, 9]);
           
        }

        $orders = $query->get();

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


        Log::info('Number of orders: ' . $orders->count());
        Log::info('Number of orders berhasil: ' . $orderBerhasil->count);

        $log = DB::getQueryLog();

        return view('admin.management.riwayat_pesanan.order', compact('orders', 'kotaAsalData', 'kotaTujuanData', 'dataDriver', 'statusOrder', 'orderBerhasil', 'orderBerlangsung', 'orderGagal'));
    }
}
