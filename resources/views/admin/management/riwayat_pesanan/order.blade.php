@extends('admin.layouts.admin')

@section('main-content')
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">{{ __('Riwayat Pesanan') }}</h1>

    @if (session('success'))
        <div class="alert alert-success border-left-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session('status'))
        <div class="alert alert-success border-left-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <div class="row">

        <!-- Sopir yang Online -->
        <div class="col-xl-4 col-md-4 mb-4">
            <div class="card border-left-success shadow">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase">Pesanan Sukses</div>
                        </div>
                        <div class="col-auto">
                            <div class="font-weight-bold text-gray-800">{{ $orderBerhasil->count }}
                                <i class="fas fa-users ml-1 text-gray-500"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sopir yang Berangkat -->
        <div class="col-xl-4 col-md-4 mb-4">
            <div class="card border-left-info shadow">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase">Sedang Berlangsung</div>
                        </div>
                        <div class="col-auto">
                            <div class="font-weight-bold text-gray-800">
                                {{ $orderBerlangsung->count }}
                                <i class="fas fa-users ml-1 text-gray-500"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sopir Offline -->
        <div class="col-xl-4 col-md-4 mb-4">
            <div class="card border-left-danger shadow">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase">Pesanan Gagal</div>
                        </div>
                        <div class="col-auto">
                            <div class="font-weight-bold text-gray-800">
                                {{ $orderGagal->count }}
                                <i class="fas fa-users ml-1 text-gray-500"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        <!-- Content Column -->
        <div class="col-lg-12 mb-4">

            <!-- Databel order -->
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Nama Penumpang</th>
                                    <th>Nama Sopir</th>
                                    <th>Order Date</th>
                                    <th>Departure Date</th>
                                    <th>Tujuan</th>
                                    <th>Status</th>
                                    <th>Harga</th>
                                    <th>Rating</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($orders as $order)
                                    <tr>
                                        <td class="d-flex align-items-center">
                                            <img class="img-profile rounded-circle avatar"
                                                src="{{ asset(Storage::url($order->passenger_photo)) }}" alt="">
                                            <div class="pl-3 email">
                                                <span class="font-weight-bold">{{ $order->passenger_name }}</span>
                                                <span class="d-block">{{ $order->passenger_email }}</span>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="d-flex">
                                                <img class="img-profile rounded-circle avatar"
                                                    src="{{ asset(Storage::url($order->driver_photo)) }}" alt="">
                                                <div class="pl-3 email">
                                                    <span class="font-weight-bold">
                                                        @php
                                                            $driverName = null;
                                                            if ($order->driver_id) {
                                                                foreach ($dataDriver as $driver) {
                                                                    if ($driver->id == $order->driver_id) {
                                                                        // Mengambil data user (nama) dari tabel users berdasarkan user_id dari tabel drivers
                                                                        $user = $driver->user;
                                                                        if ($user) {
                                                                            $driverName = $user->name;
                                                                        }
                                                                        break;
                                                                    }
                                                                }
                                                            }
                                                        @endphp
                                                        @if ($driverName)
                                                            {{ $driverName }}
                                                        @endif
                                                    </span>
                                                    <span class="d-block">
                                                        @php
                                                            $driverEmail = null;
                                                            if ($order->driver_id) {
                                                                foreach ($dataDriver as $driver) {
                                                                    if ($driver->id == $order->driver_id) {
                                                                        // Mengambil data user (email) dari tabel users berdasarkan user_id dari tabel drivers
                                                                        $user = $driver->user;
                                                                        if ($user) {
                                                                            $driverEmail = $user->email;
                                                                        }
                                                                        break;
                                                                    }
                                                                }
                                                            }
                                                        @endphp
                                                        @if ($driverEmail)
                                                            {{ $driverEmail }}
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                        </td>

                                        <td>{{ $order->tgl_pemesanan }}</td>
                                        <td>{{ $order->date_departure }} - {{ $order->time_departure }}</td>

                                        @php
                                            $kotaAsal = null;
                                            if ($order->kota_asal_id) {
                                                foreach ($kotaAsalData as $kota) {
                                                    if ($kota->id == $order->kota_asal_id) {
                                                        $kotaAsal = $kota->nama_kota;
                                                        break;
                                                    }
                                                }
                                            }

                                            $kotaTujuan = null;
                                            if ($order->kota_tujuan_id) {
                                                foreach ($kotaTujuanData as $kota) {
                                                    if ($kota->id == $order->kota_tujuan_id) {
                                                        $kotaTujuan = $kota->nama_kota;
                                                        break;
                                                    }
                                                }
                                            }
                                        @endphp

                                        @if ($kotaAsal && $kotaTujuan)
                                            <td>{{ $kotaAsal }} - {{ $kotaTujuan }}</td>
                                        @endif

                                        <td>{{ $order->status_name }}</td>
                                        <td class="text-center">
                                            {{ 'Rp ' . number_format($order->price_order, 0, ',', '.') }}</td>
                                        <td class="text-center">{{ $order->is_rating }}</td>
                                        <td class="text-center">
                                            {{-- <a href="{{ route('order.show', $order->id) }}" class="btn btn-info">
                                                <i class="fas fa-info-circle"></i> {{ __('Info') }}
                                            </a> --}}

                                            {{-- <a class="btn btn-danger" id="{{ $order->id }}" href="#"
                                                data-toggle="modal" data-target="#deleteModal">
                                                <i class="fas fa-trash"></i> {{ __('Delete') }}
                                            </a> --}}
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- Delete Modal-->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('Sure to Delete?') }}</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Are you sure you want to delete this data?</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    {{-- <form action="{{ route('sopir.destroy', $sopir->id) }}" method="POST"> --}}
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
