@extends('admin.layouts.admin')

@section('main-content')
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">{{ __('Registrasi Sopir Baru') }}</h1>

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

    <form method="POST" action="{{ route('sopir.store') }}" class="user" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="name">Nama Lengkap</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="{{ __('Nama Lengkap') }}" required autofocus>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="{{ __('E-Mail Address') }}" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="no_hp">No HP</label>
                                <input type="number" class="form-control" id="no_hp" name="no_hp"
                                    placeholder="{{ __('No HP') }}" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="{{ __('Password') }}" required>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="password_confirmation">Password Confirmation</label>
                                <input type="password" class="form-control" id="password_confirmation"
                                    name="password_confirmation" placeholder="{{ __('Confirm Password') }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <input type="text" class="form-control" name="role_id" value="3"
                                placeholder="{{ __('Sopir') }}" hidden>
                        </div>

                        <div class="form-row">
                            <div class="form-group custom-file col-md-3">
                                <label for="photo">Foto Profile</label>
                                <input type="file" class="form-control form-control-file" id="photo" name="photo"
                                    placeholder="{{ __('Foto Profile') }}" required autofocus>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="address">Alamat</label>
                                <input type="text" class="form-control" id="address" name="address"
                                    placeholder="{{ __('Alamat') }}" required>
                            </div>
                            <div class="form-group col-md-3  mb-3">
                                <span class="d-block d-flex justify-content-center" for="is_smoking">Merokok?</span>
                                <div class="d-block d-flex justify-content-center my-2">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="is_smoking" id="inlineRadio2"
                                            value="1">
                                        <label class="form-check-label" for="inlineRadio2">Ya</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="is_smoking" id="inlineRadio1"
                                            value="0" checked>
                                        <label class="form-check-label" for="inlineRadio1">Tidak</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="driver_age">Umur</label>
                                <input type="number" class="form-control" id="driver_age" name="driver_age"
                                    placeholder="{{ __('Umur') }}" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="no_ktp">No KTP</label>
                                <input type="number" class="form-control" id="no_ktp" name="no_ktp"
                                    placeholder="{{ __('No KTP') }}" required>
                            </div>
                            <div class="form-group custom-file col-md-3">
                                <label for="foto_ktp">Foto KTP</label>
                                <input type="file" class="form-control form-control-file" id="foto_ktp"
                                    name="foto_ktp" placeholder="{{ __('Foto KTP') }}" required autofocus>
                            </div>
                            <div class="form-group custom-file col-md-3">
                                <label for="foto_sim">Foto SIM</label>
                                <input type="file" class="form-control form-control-file" id="foto_sim"
                                    name="foto_sim" placeholder="{{ __('Foto SIM') }}" required autofocus>
                            </div>
                            <div class="form-group custom-file col-md-3">
                                <label for="foto_stnk">Foto STNK</label>
                                <input type="file" class="form-control form-control-file" id="foto_stnk"
                                    name="foto_stnk" placeholder="{{ __('Foto STNK') }}" required autofocus>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="merk">merk</label>
                                <input type="text" class="form-control" id="merk" name="merk"
                                    placeholder="{{ __('Toyota') }}" required autofocus>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="type">Type</label>
                                <input type="text" class="form-control" id="type" name="type"
                                    placeholder="{{ __('Kijang Inova') }}" required autofocus>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="production_year">Tuhun Produksi</label>
                                <input type="number" class="form-control" id="production_year" name="production_year"
                                    placeholder="{{ __('Tahun Produksi') }}" required autofocus>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="jenis">Jenis Kendaraan</label>
                                <input type="text" class="form-control" id="jenis" name="jenis"
                                    placeholder="{{ __('Mobil Penumpang') }}" required>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="model">Model</label>
                                <input type="text" class="form-control" id="model"name="model" placeholder="{{ __('Mini Bus') }}" required>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="seating_capacity">Kapasitas Kursi</label>
                                <input type="number" class="form-control" id="seating_capacity"
                                    name="seating_capacity" placeholder="{{ __('Kapasitas Kursi') }}" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="license_plate_number">Nomor Kendaraan</label>
                                <input type="text" class="form-control" id="license_plate_number" name="license_plate_number"
                                    placeholder="{{ __('DD 2023 YR') }}" required>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="machine_number">Nomor Mesin</label>
                                <input type="text" class="form-control" id="machine_number"name="machine_number" placeholder="{{ __('Nomor Mesin') }}" required>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="isi_silinder">Isi Silinder</label>
                                <input type="number" class="form-control" id="isi_silinder"
                                    name="isi_silinder" placeholder="{{ __('Isi Silinder') }}" required>
                            </div>
                        </div>

                        {{-- <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="no_ktp">No KTP</label>
                                <input type="number" class="form-control" id="no_ktp" name="no_ktp"
                                    placeholder="{{ __('No KTP') }}" required>
                            </div>
                            <div class="form-group custom-file col-md-3">
                                <label for="foto_ktp">Foto KTP</label>
                                <input type="file" class="form-control form-control-file" id="foto_ktp"
                                    name="foto_ktp" placeholder="{{ __('Foto KTP') }}" required autofocus>
                            </div>
                            <div class="form-group custom-file col-md-3">
                                <label for="foto_sim">Foto SIM</label>
                                <input type="file" class="form-control form-control-file" id="foto_sim"
                                    name="foto_sim" placeholder="{{ __('Foto SIM') }}" required autofocus>
                            </div>
                            <div class="form-group custom-file col-md-3">
                                <label for="foto_stnk">Foto STNK</label>
                                <input type="file" class="form-control form-control-file" id="foto_stnk"
                                    name="foto_stnk" placeholder="{{ __('Foto STNK') }}" required autofocus>
                            </div>
                        </div> --}}

                    </div>
                </div>
            </div>

        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-user btn-block">
                {{ __('Register') }}
            </button>
        </div>
    </form>
@endsection
