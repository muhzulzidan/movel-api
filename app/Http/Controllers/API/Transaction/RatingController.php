<?php

namespace App\Http\Controllers\API\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Resources\DetailDriverRatingResource;
use App\Models\DriverDeparture;
use App\Models\Order;
use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{

    public function showDriverForRating()
    {
        $userId = auth()->user()->id;

        $order = Order::where('user_id', $userId)->where('is_rating', 0)->where('status_order_id', 6)->get()->first();

        // Jika data tidak ada
        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak tersedia',
            ], 400);
        }

        $driverDeparture = DriverDeparture::where('id', $order->driver_departure_id)->get()->first();

        // Jika data tidak ada
        if (!$driverDeparture) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak tersedia',
            ], 400);
        }

        return new DetailDriverRatingResource($driverDeparture);
    }

    // Tambah data rating passenger ke driver berdasarkan ordernya
    public function addRating(Request $request)
    {
        $request->validate([
            'nilai_rating' => 'required|numeric|between:1,5',
        ]);

        // Ambil data order berdasarkan id user yang sedang login dimana is_rating === 0 dan status_order_id === 6
        $userId = auth()->user()->id;
        $order = Order::where('user_id', $userId)->where('is_rating', 0)->where('status_order_id', 6)->get()->first();

        // Jika data tidak ada
        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak tersedia',
            ], 400);
        }

        // Ambil data driver_departures berdasarkan driver_departure_id di orders
        $driverDeparture = DriverDeparture::where('id', $order->driver_departure_id)->get()->first();

        // Jika data tidak ada
        if (!$driverDeparture) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak tersedia',
            ], 400);
        }

        // Update is_rating di orders
        Order::where('id', $order->id)->update(['is_rating' => 1]);

        // Create rating ke tabel ratings
        Rating::create([
            'nilai_rating' => $request->nilai_rating,
            'driver_id' => $driverDeparture->driver_id,
            'order_id' => $order->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil melakukan rating',
        ]);
    }
}
