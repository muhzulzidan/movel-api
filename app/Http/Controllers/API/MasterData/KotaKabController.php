<?php

namespace App\Http\Controllers\API\MasterData;

use App\Http\Controllers\Controller;
use App\Models\KotaKab;

class KotaKabController extends Controller
{

    public function index()
    {
        $kotaKab = KotaKab::all();

        return response()->json([
            'success' => true,
            'data' => $kotaKab,
        ]);
    }
}
