@extends('admin.layouts.admin')

@section('main-content')

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">{{ __('Data Sopir ') }}
        @if ($show_sopir)
            <strong>{{ $show_sopir['name'] }}</strong>
        @else
            <strong>Data not found</strong>
        @endif
    </h1>

    @if (session('success'))
        <div class="alert alert-success border-left-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger border-left-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger border-left-danger" role="alert">
            <ul class="pl-4 my-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">

        <div class="col-lg-6">
            <h5 class="text-success font-weight-bold border-left-success pl-2">Panel Data Sekarang</h5>
            <div class="card shadow mb-4">
                <div class="row mt-3 align-items-center">
                    <div class="col-3">
                        <div class="card-profile-image ml-3">
                            <img class="rounded-circle avatar" style="height: 120px; width: 120px;"
                                src="{{ asset(Storage::url($show_sopir['photo'])) }}" alt="">
                        </div>
                    </div>
                    <div class="col-9">
                        <h4 class="font-weight-bold">{{ $show_sopir['name'] }}</h4>
                        <div class="row">
                            <div class="col-6">{{ $show_sopir['email'] }}</div>
                            <div class="col-6 mr-0">{{ $show_sopir['no_hp'] }}</div>
                        </div>
                        <div class="mt-1 row">
                            <div class="col-6">
                                <i class="fa fa-star text-warning"></i>
                                <i class="fa fa-star text-warning"></i>
                                <i class="fa fa-star text-warning"></i>
                                <i class="fa-solid fa-star-half-stroke text-warning"></i>
                                <i class="fa-regular fa-star text-warning"></i>
                                <span class="text-success font-weight-bold ml-2">3.5</span>
                            </div>
                            <div class="col-6">
                                <span>{{ $total_penumpang }} Penumpang</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link font-weight-bold active" id="home-tab1" data-toggle="tab" href="#home1"
                                role="tab" aria-controls="home1" aria-selected="true">
                                <i class="fa-solid fa-id-card"></i> Profile
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link font-weight-bold" id="profile-tab1" data-toggle="tab" href="#profile1"
                                role="tab" aria-controls="profile1" aria-selected="false">
                                <i class="fa-solid fa-car"></i> Data Mobil
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">

                        {{-- Info Data Profile --}}
                        <div class="tab-pane fade show active" id="home1" role="tabpanel" aria-labelledby="home-tab1">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td>Alamat</td>
                                        <td class="font-weight-bold">{{ $show_sopir['address'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Umur</td>
                                        <td class="font-weight-bold">{{ $show_sopir['driver_age'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Keterangan Merokok</td>
                                        <td class="font-weight-bold">
                                            @if ($show_sopir['is_smoking'] == 1)
                                                Merokok
                                            @else
                                                Tidak
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>No. KTP</td>
                                        <td class="font-weight-bold">{{ $show_sopir['no_ktp'] }}</td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="row">
                                <div class="col-4 text-center">
                                    <div class="avatar border shadow" style="height: 80px; width: 150px;">
                                        <a style="text-decoration: none" href="{{ asset(Storage::url($show_sopir['foto_ktp'])) }}"
                                            data-lightbox="foto_ktp" data-title="{{ $show_sopir['foto_ktp'] }}">
                                            <img src="{{ asset(Storage::url($show_sopir['foto_ktp'])) }}" class="rounded"
                                                alt="..."> <span class="text-black font-weight-bold">SIM</span>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-4 text-center">
                                    <div class="avatar border shadow" style="height: 80px; width: 150px;">
                                        <a style="text-decoration: none" href="{{ asset(Storage::url($show_sopir['foto_sim'])) }}"
                                            data-lightbox="foto_sim" data-title="{{ $show_sopir['foto_sim'] }}">
                                            <img src="{{ asset(Storage::url($show_sopir['foto_sim'])) }}" class="rounded"
                                                alt="..."> <span class="text-black font-weight-bold">KTP</span>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-4 text-center">
                                    <div class="avatar border shadow" style="height: 80px; width: 150px;">
                                        <a style="text-decoration: none" href="{{ asset(Storage::url($show_sopir['foto_stnk'])) }}"
                                            data-lightbox="foto_stnk" data-title="{{ $show_sopir['foto_stnk'] }}">
                                            <img src="{{ asset(Storage::url($show_sopir['foto_stnk'])) }}"
                                                class="rounded" alt="..."> <span class="text-black font-weight-bold">STNK</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Info Data Mobil --}}
                        <div class="tab-pane fade" id="profile1" role="tabpanel" aria-labelledby="profile-tab1">
                            <table class="table">
                                <tbody cellpadding="20px" cellspacing="10px">
                                    <tr>
                                        <td>Merek - Type - Tahun</td>
                                        <td class="font-weight-bold">{{ $show_sopir['merk'] }} -
                                            {{ $show_sopir['type'] }} - {{ $show_sopir['production_year'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Model & Jenis</td>
                                        <td class="font-weight-bold">{{ $show_sopir['model'] }} -
                                            {{ $show_sopir['jenis'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Jumlah Kursi</td>
                                        <td class="font-weight-bold">{{ $show_sopir['seating_capacity'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Nomor Kendaraan</td>
                                        <td class="font-weight-bold">{{ $show_sopir['license_plate_number'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Nomor Mesin - Silinder</td>
                                        <td class="font-weight-bold">{{ $show_sopir['machine_number'] }} -
                                            {{ $show_sopir['isi_silinder'] }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

@endsection
