<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $sopir = User::where('role_id', 3)->count();
        $penumpang = User::where('role_id', 2)->count();
        $total_pesanan = Order::count();

        $widget = [
            'sopir' => $sopir,
            'penumpang' => $penumpang,
            'total_pesanan' => $total_pesanan,
            //...
        ];

        return view('admin.home', compact('widget'));
    }
}
