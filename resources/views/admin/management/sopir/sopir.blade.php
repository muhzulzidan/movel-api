@extends('layouts.admin')

@section('main-content')
    <!-- Page Heading -->
    <div class="row no-gutters">
        <div class="col-6">
            <h1 class="h3 mb-4 text-gray-800">{{ __('Data Sopir') }}</h1>
        </div>
        <div class="col-6">
            <a href="{{ route('addSopir') }}" class="btn btn-primary float-right"><strong>Registrasi Sopir Baru</strong></a>
        </div>
    </div>

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
                            <div class="text-xs font-weight-bold text-success text-uppercase">Sopir Online</div>
                        </div>
                        <div class="col-auto">
                            <div class="font-weight-bold text-gray-800">
                                20
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
                            <div class="text-xs font-weight-bold text-info text-uppercase">Sopir Berangkat</div>
                        </div>
                        <div class="col-auto">
                            <div class="font-weight-bold text-gray-800">
                                10
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
                            <div class="text-xs font-weight-bold text-danger text-uppercase">Sopir Offline</div>
                        </div>
                        <div class="col-auto">
                            <div class="font-weight-bold text-gray-800">
                                20
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

            <!-- Databel Sopir -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tabel Sopir</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>No HP</th>
                                    <th>Alamat</th>
                                    <th>Merokok?</th>
                                    <th>Usia</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($drivers as $sopir)
                                    <tr>
                                        <td class="d-flex align-items-center">
                                            <img class="img-profile rounded-circle avatar"
                                                src="https://api.movel.id/{{ $sopir->gambar_url }}" alt="">
                                            <div class="pl-3 email">
                                                <span class="font-weight-bold">{{ $sopir->name }}</span>
                                                <span class="d-block">{{ $sopir->email }}</span>
                                            </div>
                                        </td>
                                        <td>{{ $sopir->no_hp }}</td>
                                        <td>{{ $sopir->address }}</td>
                                        <td class="text-center">
                                            @if ($sopir->is_smoking == 1)
                                                <span class="badge badge-pill badge-warning">Merokok</span>
                                            @elseif ($sopir->is_smoking == 0)
                                                <span class="badge badge-pill badge-success">Tidak Merokok</span>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $sopir->driver_age }}</td>
                                        <td class="text-center">

                                            <a href="{{ route('showSopir', $sopir->id) }}" class="btn btn-success">
                                                <i class="fas fa-info-circle"></i> {{ __('Detail') }}
                                            </a>

                                            <a href="{{ route('editSopir', $sopir->id) }}" class="btn btn-primary">
                                                <i class="fas fa-edit"></i> {{ __('Edit') }}
                                            </a>

                                            <button class="btn btn-danger" id="{{ $sopir->id }}">
                                                <i class="fas fa-trash"></i> {{ __('Delete') }}
                                            </button>
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
@endsection
