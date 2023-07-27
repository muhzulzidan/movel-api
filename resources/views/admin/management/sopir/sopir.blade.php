@extends('admin.layouts.admin')

@section('main-content')
    <!-- Page Heading -->
    <div class="row no-gutters">
        <div class="col-6">
            <h1 class="h3 mb-4 text-gray-800">{{ __('Data Sopir') }}</h1>
        </div>
        <div class="col-6">
            <a href="{{ route('sopir.store') }}" class="btn btn-primary float-right"><strong>Registrasi Sopir
                    Baru</strong></a>
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
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-success shadow">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase">Sopir Aktif</div>
                        </div>
                        <div class="col-auto">
                            <div class="font-weight-bold text-gray-800">
                                {{ $driver_aktif }}
                                <i class="fas fa-users ml-1 text-gray-500"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sopir Offline -->
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-danger shadow">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase">Sopir Nonaktif</div>
                        </div>
                        <div class="col-auto">
                            <div class="font-weight-bold text-gray-800">
                                {{ $allDriver - $driver_aktif }}
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
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Mobil</th>
                                    {{-- <th>Alamat</th> --}}
                                    <th>Status</th>
                                    <th>Top Up Saldo</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($drivers as $sopir)
                                    <tr>
                                        <td class="d-flex align-items-center">
                                            <img class="img-profile rounded-circle avatar"
                                                src="{{ asset(Storage::url($sopir->photo)) }}" alt="">
                                            <div class="pl-3 email">
                                                <span class="font-weight-bold">{{ $sopir->name }}</span>
                                                <span class="d-block">{{ $sopir->email }}</span>
                                            </div>
                                        </td>

                                        <td class="">
                                            <span class="font-weight-bold">{{ $sopir->merk }} - {{ $sopir->type }}</span>
                                            <span class="d-block">{{ $sopir->license_plate_number }}</span>
                                        </td>

                                        @php
                                            $driverStatus = null;
                                            foreach ($driver_departure as $status) {
                                                if ($status->driver_id == $sopir->sopir_id) {
                                                    $driverStatus = $status->is_active;
                                                    break;
                                                }
                                            }
                                        @endphp

                                        <!-- Your other table columns here -->
                                        @if ($driverStatus == 1)
                                            <td class="text-center"><span class="badge badge-success">Aktif</span></td>
                                        @else
                                            <td class="text-center"><span class="badge badge-danger">Nonaktif</span></td>
                                        @endif


                                        <td class="text-center">
                                            <div class="btn-toolbar justify-content-center">
                                                <div class="input-group mr-2">
                                                    <input type="text" class="form-control"
                                                        placeholder="{{ 'Rp ' . number_format($sopir->saldo, 0, ',', '.') }}"
                                                        readonly>
                                                </div>
                                                <div class="btn-group" role="group" aria-label="Basic example">
                                                    <button type="button" class="btn btn-info" id="{{ $sopir->sopir_id }}"
                                                        data-toggle="modal"
                                                        data-target="#topupModal-{{ $sopir->sopir_id }}"
                                                        data-toggle="tooltip" data-placement="top" title="Top Up Saldo">
                                                        <i class="fa-solid fa-wallet"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-warning"
                                                        id="{{ $sopir->sopir_id }}" data-toggle="modal"
                                                        data-target="#changeSaldoModal-{{ $sopir->sopir_id }}"
                                                        data-toggle="tooltip" data-placement="top" title="Change Saldo">
                                                        <i class="fa-solid fa-money-bill-transfer"></i>
                                                    </button>
                                                    {{-- <button type="button" class="btn btn-secondary"
                                                        id="{{ $sopir->sopir_id }}" data-toggle="modal"
                                                        data-target="#topupModal-{{ $sopir->sopir_id }}"
                                                        data-toggle="tooltip" data-placement="top" title="Reset Saldo">
                                                        <i class="fa-solid fa-eraser"></i>
                                                    </button> --}}
                                                </div>
                                            </div>
                                        </td>

                                        <td class="text-center">

                                            {{-- <a href="{{ route('sopir.show', $sopir->sopir_id) }}" class="btn btn-info">
                                                <i class="fa-solid fa-comments"></i>
                                            </a> --}}
                                            <a href="{{ route('sopir.show', $sopir->sopir_id) }}" class="btn btn-success">
                                                <i class="fas fa-info-circle"></i> {{ __('Detail') }}
                                            </a>
                                            <a href="{{ route('sopir.edit', $sopir->sopir_id) }}" class="btn btn-primary">
                                                <i class="fas fa-edit"></i> {{ __('Edit') }}
                                            </a>

                                            <a class="btn btn-danger" id="{{ $sopir->sopir_id }}" href="#"
                                                data-toggle="modal" data-target="#deleteModal-{{ $sopir->sopir_id }}">
                                                <i class="fas fa-trash"></i> {{ __('Delete') }}
                                            </a>
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

    <!-- TopUp Saldo Modal-->
    @foreach ($drivers as $sopir)
        <div class="modal fade" id="changeSaldoModal-{{ $sopir->sopir_id }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel-{{ $sopir->sopir_id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel-{{ $sopir->sopir_id }}">
                            <h5>Ubah saldo Sopir <strong>{{ $sopir->name }}, ID: {{ $sopir->sopir_id }}</strong></h5>
                        </h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form action="{{ route('sopir.ubah_saldo', $sopir->sopir_id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="_method" value="PUT">
                        <div class="modal-body">

                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <select class="custom-select" id="inputGroupSelect01" name="saldo">
                                    <option selected>Choose...</option>
                                    <option value="5000">5.000</option>
                                    <option value="10000">10.000</option>
                                    <option value="20000">20.000</option>
                                    <option value="30000">30.000</option>
                                    <option value="40000">40.000</option>
                                    <option value="50000">50.000</option>
                                    <option value="100000">100.000</option>
                                    <option value="150000">150.000</option>
                                    <option value="200000">200.000</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">TopUp</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Change Saldo Modal-->
    @foreach ($drivers as $sopir)
        <div class="modal fade" id="topupModal-{{ $sopir->sopir_id }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel-{{ $sopir->sopir_id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel-{{ $sopir->sopir_id }}">
                            <h5>TopUp saldo Sopir <strong>{{ $sopir->name }}, ID: {{ $sopir->sopir_id }}</strong></h5>
                        </h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form action="{{ route('sopir.topup', $sopir->sopir_id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="_method" value="PUT">
                        <div class="modal-body">

                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <select class="custom-select" id="inputGroupSelect01" name="saldo">
                                    <option selected>Choose...</option>
                                    <option value="5000">5.000</option>
                                    <option value="10000">10.000</option>
                                    <option value="20000">20.000</option>
                                    <option value="30000">30.000</option>
                                    <option value="40000">40.000</option>
                                    <option value="50000">50.000</option>
                                    <option value="100000">100.000</option>
                                    <option value="150000">150.000</option>
                                    <option value="200000">200.000</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">TopUp</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach


    <!-- Delete Modal-->
    @foreach ($drivers as $sopir)
        <div class="modal fade" id="deleteModal-{{ $sopir->sopir_id }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel-{{ $sopir->sopir_id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel-{{ $sopir->sopir_id }}">
                            {{ __('Sure to Delete?') }}</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">Apakah kamu ingin menghapus data <strong>{{ $sopir->name }} ID
                            {{ $sopir->sopir_id }}</strong>!</div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <form action="{{ route('sopir.destroy', $sopir->sopir_id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
