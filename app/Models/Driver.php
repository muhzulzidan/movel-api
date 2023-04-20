<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function index()
    {
        $drivers = Driver::all();
        return view('admin.drivers.index', compact('drivers'));
    }

    
    public function create()
    {
        return view('admin.drivers.create');
    }

   
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'address' => 'required',
            'photo' => 'required',
            'no_ktp' => 'required',
            'foto_ktp' => 'required',
            'foto_sim' => 'required',
            'foto_stnk' => 'required',
        ]);

        Driver::create($request->all());
        return redirect()->route('admin.drivers.index')
            ->with('success', 'Driver berhasil ditambahkan!');
    }

    
    public function show(Driver $driver)
    {
        return view('admin.drivers.show', compact('driver'));
    }

  
    public function edit(Driver $driver)
    {
        return view('admin.drivers.edit', compact('driver'));
    }

    
    public function update(Request $request, Driver $driver)
    {
        $request->validate([
            'user_id' => 'required',
            'address' => 'required',
            'photo' => 'required',
            'no_ktp' => 'required',
            'foto_ktp' => 'required',
            'foto_sim' => 'required',
            'foto_stnk' => 'required',
           
        ]);

        $driver->update($request->all());
        return redirect()->route('admin.drivers.index')
            ->with('success', 'Driver berhasil diperbarui!');
    }

  
    public function destroy(Driver $driver)
    {
        $driver->delete();
        return redirect()->route('admin.drivers.index')
            ->with('success', 'Driver berhasil dihapus!');
    }
}
