<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DriverController extends Controller
{
    public function index()
    {
        $drivers = Driver::all();
        return view('drivers.index', compact('drivers'));
    }

    public function create()
    {
        return view('drivers.create');
    }

    
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:drivers',
            'phone' => 'required',
        ]);

        Driver::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
        ]);

        return redirect()->route('drivers.index')->with('success', 'Driver baru telah berhasil ditambahkan!');
    }

    public function show(Driver $driver)
    {
        return view('drivers.show', compact('driver'));
    }

    public function edit(Driver $driver)
    {
        return view('drivers.edit', compact('driver'));
    }

    public function update(Request $request, Driver $driver)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:drivers,email,' . $driver->id,
            'phone' => 'required',
        ]);

        $driver->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
        ]);

        return redirect()->route('drivers.index')->with('success', 'Driver telah berhasil diperbarui!');
    }

    public function destroy(Driver $driver)
    {
        $driver->delete();
        return redirect()->route('drivers.index')->with('success', 'Driver telah berhasil dihapus!');
    }
}
