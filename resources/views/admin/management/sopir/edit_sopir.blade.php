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
                            <div class="col-12">{{ $show_sopir['email'] }}</div>
                            <div class="col-12">{{ $show_sopir['no_hp'] }}</div>
                        </div>
                        {{-- <div class="mt-2">
                            <a class="btn btn-success btn-sm" href="#">
                                <i class="fas fa-download"></i> {{ __('Unduh Data') }}
                            </a>
                            <a class="btn btn-info btn-sm" href="#">
                                <i class="fa-solid fa-unlock-keyhole"></i> {{ __('Reset Sandi') }}
                            </a>
                            <a class="btn btn-warning btn-sm" href="#">
                                <i class="fa-solid fa-map-location-dot"></i> {{ __('Lacak') }}
                            </a>
                        </div> --}}
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
                        <li class="nav-item">
                            <a class="nav-link font-weight-bold" id="contact-tab1" data-toggle="tab" href="#infolain"
                                role="tab" aria-controls="infolain" aria-selected="false">
                                <i class="fa fa-star"></i>
                                Driver Departure
                            </a>
                        </li>
                        {{-- <li class="nav-item">
                            <a class="nav-link font-weight-bold" id="contact-tab1" data-toggle="tab" href="#contact1"
                                role="tab" aria-controls="contact1" aria-selected="false">
                                <i class="fa fa-star"></i> Rating
                            </a>
                        </li> --}}
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
                                        <a style="text-decoration: none"
                                            href="{{ asset(Storage::url($show_sopir['foto_ktp'])) }}"
                                            data-lightbox="foto_ktp" data-title="{{ $show_sopir['foto_ktp'] }}">
                                            <img src="{{ asset(Storage::url($show_sopir['foto_ktp'])) }}" class="rounded"
                                                alt="..."> <span class="text-black font-weight-bold">SIM</span>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-4 text-center">
                                    <div class="avatar border shadow" style="height: 80px; width: 150px;">
                                        <a style="text-decoration: none"
                                            href="{{ asset(Storage::url($show_sopir['foto_sim'])) }}"
                                            data-lightbox="foto_sim" data-title="{{ $show_sopir['foto_sim'] }}">
                                            <img src="{{ asset(Storage::url($show_sopir['foto_sim'])) }}" class="rounded"
                                                alt="..."> <span class="text-black font-weight-bold">KTP</span>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-4 text-center">
                                    <div class="avatar border shadow" style="height: 80px; width: 150px;">
                                        <a style="text-decoration: none"
                                            href="{{ asset(Storage::url($show_sopir['foto_stnk'])) }}"
                                            data-lightbox="foto_stnk" data-title="{{ $show_sopir['foto_stnk'] }}">
                                            <img src="{{ asset(Storage::url($show_sopir['foto_stnk'])) }}"
                                                class="rounded" alt="..."> <span
                                                class="text-black font-weight-bold">STNK</span>
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
                                        <td class="font-weight-bold">{{ $show_sopir['seating_capacity'] }} Kursi</td>
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

                        {{-- Info Lain --}}
                        {{-- Info Lain --}}
                        <div class="tab-pane fade" id="infolain" role="tabpanel" aria-labelledby="infolain-tab">
                            {{-- <h5>Driver Departure Details</h5> --}}
                            <table class="table">
                                <tbody cellpadding="20px" cellspacing="10px">
                                    <tr>
                                        <td>Departure ID</td>
                                        <td class="font-weight-bold">{{ $driver_departure->id }}</td>
                                    </tr>
                                    <tr>
                                        <td>Departure Date</td>
                                        <td class="font-weight-bold">{{ $driver_departure->date_departure }}</td>
                                    </tr>
                                    <tr>
                                        <td>Departure Time</td>
                                        <td class="font-weight-bold">{{ $driver_departure->time_departure }}</td>
                                    </tr>
                                    <tr>
                                        <td>Destination ID</td>
                                        <td class="font-weight-bold">{{ $driver_departure->kota_tujuan_id }}</td>
                                    </tr>
                                    <!-- Add more fields as needed -->
                                </tbody>
                            </table>
                        </div>

                        {{-- Info Data Feedback --}}
                        {{-- <div class="tab-pane fade" id="contact1" role="tabpanel" aria-labelledby="contact-tab1">...
                        </div> --}}
                    </div>
                </div>
            </div>

        </div>

        <div class="col-lg-6">
            <h5 class="text-primary font-weight-bold border-left-primary pl-2">Panel Edit Data</h5>
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link font-weight-bold active" id="home-tab" data-toggle="tab" href="#home"
                        role="tab" aria-controls="home" aria-selected="true">
                        <i class="fa-solid fa-id-card"></i> Profile
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link font-weight-bold" id="profile-tab" data-toggle="tab" href="#profile"
                        role="tab" aria-controls="profile" aria-selected="false">
                        <i class="fa-solid fa-car"></i> Data Mobil
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link font-weight-bold" id="departure-tab" data-toggle="tab" href="#departure"
                        role="tab" aria-controls="departure" aria-selected="false">
                        <i class="fa-solid fa-route"></i> Driver Departure
                    </a>
                </li>
                {{-- <li class="nav-item font-weight-bold">
                    <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab"
                        aria-controls="contact" aria-selected="false">
                        <i class="fa fa-star"></i> Rating
                    </a>
                </li> --}}
            </ul>
            <div class="tab-content" id="myTabContent">

                {{-- Untuk Edit Data Diri Sopir --}}
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="card shadow mb-4">

                        <div class="card-body">

                            <form method="POST" action="{{ route('sopir.update.driver', $show_sopir->driver_id) }}"
                                autocomplete="off" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="_method" value="PUT">

                                <div class="col-12 mb-5">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group focused">
                                                <label class="form-control-label" for="name">Nama Lengkap<span
                                                        class="small text-danger">*</span></label>
                                                <input type="text" id="name" class="form-control" name="name"
                                                    placeholder="Name" value="{{ old('name', $show_sopir['name']) }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group focused">
                                                <label class="form-control-label" for="email">Email<span
                                                        class="small text-danger">*</span></label>
                                                <input type="text" id="email" class="form-control" name="email"
                                                    placeholder="Last name"
                                                    value="{{ old('email', $show_sopir['email']) }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group focused">
                                                <label class="form-control-label" for="no_hp">No. HP<span
                                                        class="small text-danger">*</span></label>
                                                <input type="text" id="no_hp" class="form-control" name="no_hp"
                                                    placeholder="Last name"
                                                    value="{{ old('no_hp', $show_sopir['no_hp']) }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group custom-file col-md-4">
                                            <label for="photo">Foto Profile</label>
                                            <input type="file" class="form-control form-control-file" id="photo"
                                                name="photo" placeholder="{{ __('Foto Profile') }}"
                                                value="{{ $show_sopir['photo'] ? basename($show_sopir['photo']) : '' }}"
                                                autofocus>
                                            <input type="hidden" id="existing-photo" name="existing_photo"
                                                value="{{ $show_sopir['photo'] }}">
                                        </div>
                                        <div class="form-group col-md-8">
                                            <label for="address">Alamat<span class="small text-danger">*</span></label>
                                            <input type="text" class="form-control" id="address" name="address"
                                                placeholder="{{ __('Alamat') }}"
                                                value="{{ old('address', $show_sopir['address']) }}" required>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-3  mb-2">
                                            <span class="d-block d-flex justify-content-center"
                                                for="is_smoking">Merokok?<span class="small text-danger">*</span></span>
                                            <div class="d-block d-flex justify-content-center my-2">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="is_smoking"
                                                        id="inlineRadio2" value="1"
                                                        @if ($show_sopir->is_smoking === 1) checked @endif>
                                                    <label class="form-check-label" for="inlineRadio2">Ya</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="is_smoking"
                                                        id="inlineRadio1" value="0"
                                                        @if ($show_sopir->is_smoking === 0) checked @endif>
                                                    <label class="form-check-label" for="inlineRadio1">Tidak</label>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="driver_age">Umur<span class="small text-danger">*</span></label>
                                            <select class="form-control" id="driver_age" name="driver_age">
                                                <option value="{{ old('driver_age', $show_sopir['driver_age']) }}">
                                                    {{ $show_sopir['driver_age'] }}</option>
                                                @for ($age = 18; $age <= 60; $age++)
                                                    <option value="{{ $age }}">{{ $age }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="no_ktp">No KTP<span class="small text-danger">*</span></label>
                                            <input type="number" class="form-control" id="no_ktp" name="no_ktp"
                                                placeholder="{{ __('No KTP') }}"
                                                value="{{ old('no_ktp', $show_sopir['no_ktp']) }}" required>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group custom-file col-md-4">
                                            <label for="foto_ktp">Foto KTP</label>
                                            <input type="file" class="form-control form-control-file" id="foto_ktp"
                                                name="foto_ktp" placeholder="{{ __('Foto KTP') }}"
                                                value="{{ $show_sopir['foto_ktp'] ? basename($show_sopir['foto_ktp']) : '' }}"
                                                autofocus>
                                        </div>
                                        <div class="form-group custom-file col-md-4">
                                            <label for="foto_sim">Foto SIM</label>
                                            <input type="file" class="form-control form-control-file" id="foto_sim"
                                                name="foto_sim" placeholder="{{ __('Foto SIM') }}"
                                                value="{{ $show_sopir['foto_sim'] ? basename($show_sopir['foto_sim']) : '' }}"
                                                autofocus>
                                        </div>
                                        <div class="form-group custom-file col-md-4">
                                            <label for="foto_stnk">Foto STNK</label>
                                            <input type="file" class="form-control form-control-file" id="foto_stnk"
                                                name="foto_stnk" placeholder="{{ __('Foto STNK') }}"
                                                value="{{ $show_sopir['foto_stnk'] ? basename($show_sopir['foto_stnk']) : '' }}"
                                                autofocus>
                                        </div>
                                    </div>
                                </div>

                                <!-- Button -->
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col text-center">
                                            <button type="submit" class="btn btn-primary btn-block">Simpan
                                                Perubahan</button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                        </div>

                    </div>
                </div>

                {{-- Untuk Edit Data Mobil Sopir --}}
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="card shadow mb-4">

                        <div class="card-body">

                            <form method="POST" action="{{ route('sopir.update.car', $show_sopir->id) }}"
                                autocomplete="off" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="_method" value="PUT">

                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="merk">Merek<span class="small text-danger">*</span></label>
                                        <input type="text" class="form-control" id="merk" name="merk"
                                            placeholder="{{ __('Toyota') }}" required
                                            value="{{ old('merk', $show_sopir['merk']) }}" autofocus>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="type">Type<span class="small text-danger">*</span></label>
                                        <input type="text" class="form-control" id="type" name="type"
                                            placeholder="{{ __('Kijang Inova') }}" required
                                            value="{{ old('type', $show_sopir['type']) }}" autofocus>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="production_year">Tahun Produksi<span
                                                class="small text-danger">*</span></label>
                                        <select class="form-control" id="production_year" name="production_year"
                                            placeholder="{{ __('Tahun Produksi') }}" autofocus>
                                            <option value="{{ old('production_year', $show_sopir['production_year']) }}">
                                                {{ $show_sopir['production_year'] }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="jenis">Jenis Kendaraan<span
                                                class="small text-danger">*</span></label>
                                        <input type="text" class="form-control" id="jenis" name="jenis"
                                            placeholder="{{ __('Mobil Penumpang') }}" required
                                            value="{{ old('jenis', $show_sopir['jenis']) }}">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="model">Model<span class="small text-danger">*</span></label>
                                        <input type="text" class="form-control" id="model"name="model"
                                            placeholder="{{ __('Mini Bus') }}" required
                                            value="{{ old('model', $show_sopir['model']) }}">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="seating_capacity">Kapasitas Kursi<span
                                                class="small text-danger">*</span></label>
                                        <select class="form-control" id="seating_capacity" name="seating_capacity">
                                            <option
                                                value="{{ old('seating_capacity', $show_sopir['seating_capacity']) }}">
                                                {{ $show_sopir['seating_capacity'] }} Kursi</option>
                                            <option value="4">4 Kursi</option>
                                            <option value="7">7 Kursi</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="license_plate_number">Nomor Kendaraan<span
                                                class="small text-danger">*</span></label>
                                        <input type="text" class="form-control" id="license_plate_number"
                                            name="license_plate_number" placeholder="{{ __('DD 2023 YR') }}" required
                                            value="{{ old('license_plate_number', $show_sopir['license_plate_number']) }}">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="machine_number">Nomor Mesin<span
                                                class="small text-danger">*</span></label>
                                        <input type="text" class="form-control"
                                            id="machine_number"name="machine_number"
                                            placeholder="{{ __('Nomor Mesin') }}" required
                                            value="{{ old('machine_number', $show_sopir['machine_number']) }}">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="isi_silinder">Isi Silinder<span
                                                class="small text-danger">*</span></label>
                                        <select class="form-control" id="isi_silinder" name="isi_silinder" required>
                                            <option value="{{ old('isi_silinder', $show_sopir['isi_silinder']) }}">
                                                {{ $show_sopir['isi_silinder'] }}</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="8">8</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Button -->
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col text-center">
                                            <button type="submit" class="btn btn-primary btn-block">Simpan
                                                Perubahan</button>
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

                {{-- Untuk Edit Data Departure --}}
                <div class="tab-pane fade" id="departure" role="tabpanel" aria-labelledby="departure-tab">
                    <div class="card shadow mb-4">
                        <div class="card-body">

                            <form method="POST" action="{{ route('departure.update', ['driver_id' => $show_sopir->driver_id, 'departure_id' => $driver_departure->id]) }}" autocomplete="off" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="_method" value="PUT">

                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="departure_city">Departure City<span
                                                class="small text-danger">*</span></label>
                                        <select class="form-control" id="departure_city" name="departure_city" required
                                            autofocus>
                                            @foreach ($kota_kabs as $kota_kab)
                                                <option value="{{ $kota_kab->id }}"
                                                    {{ old('departure_city', $driver_departure->kota_asal_id) == $kota_kab->id ? 'selected' : '' }}>
                                                    {{ $kota_kab->nama_kota }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="destination_city">Destination City<span
                                                class="small text-danger">*</span></label>
                                        <select class="form-control" id="destination_city" name="destination_city"
                                            required autofocus>
                                            @foreach ($kota_kabs as $kota_kab)
                                                <option value="{{ $kota_kab->id }}"
                                                    {{ old('destination_city', $driver_departure->kota_tujuan_id) == $kota_kab->id ? 'selected' : '' }}>
                                                    {{ $kota_kab->nama_kota }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="departure_time">Departure Time<span
                                                class="small text-danger">*</span></label>
                                        <input type="time" class="form-control" id="departure_time"
                                            name="departure_time" placeholder="{{ __('Departure Time') }}" required
                                            value="{{ old('departure_time', $driver_departure->time_departure) }}"
                                            autofocus>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="departure_date">Departure Date<span
                                                class="small text-danger">*</span></label>
                                        <input type="date" class="form-control" id="departure_date"
                                            name="departure_date" placeholder="{{ __('Departure Date') }}" required
                                            value="{{ old('departure_date', $driver_departure->date_departure) }}"
                                            autofocus>
                                    </div>
                                </div>

                                <!-- Button -->
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col text-center">
                                            <button type="submit" class="btn btn-primary btn-block">Save Changes</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Untuk Edit Data Rating Sopir --}}
                {{-- <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">...</div> --}}
            </div>

        </div>

    </div>

@endsection
