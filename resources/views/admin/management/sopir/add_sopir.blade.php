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

    {{-- @if (session('status'))
        <div class="alert alert-success border-left-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger border-left-danger" role="alert">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->has('error'))
        <div class="alert alert-danger border-left-danger" role="alert">
            {{ $errors->first('error') }}
        </div>
    @endif --}}

    @if ($errors->any())
        <div class="alert alert-danger border-left-danger" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
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
                                <label for="name">Nama Lengkap<span class="small text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="{{ __('Nama Lengkap') }}" value="{{ old('name') }}" required autofocus>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="email">Email<span class="small text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="{{ __('E-Mail Address') }}" value="{{ old('email') }}" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="no_hp">No HP<span class="small text-danger">*</span></label>
                                <input type="number" class="form-control" id="no_hp" name="no_hp"
                                    placeholder="{{ __('No HP') }}" value="{{ old('no_hp') }}" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="password">Password<span class="small text-danger">*</span></label>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="{{ __('Password') }}" required>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="password_confirmation">Password Confirmation<span
                                        class="small text-danger">*</span></label>
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
                                <label for="photo">Foto Profile<span class="small text-danger">*</span></label>
                                <input type="file" class="form-control form-control-file" id="photo" name="photo"
                                    placeholder="{{ __('Foto Profile') }}" required autofocus>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="address">Alamat<span class="small text-danger">*</span></label>
                                <input type="text" class="form-control" id="address" name="address"
                                    placeholder="{{ __('Alamat') }}" value="{{ old('address') }}" required>
                            </div>
                            <div class="form-group col-md-3 mb-3">
                                <span class="d-block d-flex justify-content-center" for="is_smoking">Merokok?<span class="small text-danger">*</span></span>
                                <div class="d-block d-flex justify-content-center my-2">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="is_smoking" id="inlineRadio2" value="1" @if(old('is_smoking') == "1") checked @endif>
                                        <label class="form-check-label" for="inlineRadio2">Ya</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="is_smoking" id="inlineRadio1" value="0" @if(old('is_smoking', '0') == "0") checked @endif>
                                        <label class="form-check-label" for="inlineRadio1">Tidak</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-2">
                                <label for="driver_age">Umur<span class="small text-danger">*</span></label>
                                <select class="form-control" id="driver_age" name="driver_age">
                                    <option value="{{ old('driver_age') }}">Pilih</option>
                                    @for ($age = 18; $age <= 60; $age++)
                                        <option value="{{ $age }}"
                                            {{ old('driver_age') == $age ? 'selected' : '' }}>{{ $age }}</option>
                                    @endfor
                                </select>
                            </div>

                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="no_ktp">No KTP<span class="small text-danger">*</span></label>
                                <input type="number" class="form-control" id="no_ktp" name="no_ktp"
                                    placeholder="{{ __('No KTP') }}" value="{{ old('no_ktp') }}" required>
                            </div>
                            <div class="form-group custom-file col-md-3">
                                <label for="foto_ktp">Foto KTP<span class="small text-danger">*</span></label>
                                <input type="file" class="form-control form-control-file" id="foto_ktp"
                                    name="foto_ktp" placeholder="{{ __('Foto KTP') }}" required autofocus>
                            </div>
                            <div class="form-group custom-file col-md-3">
                                <label for="foto_sim">Foto SIM<span class="small text-danger">*</span></label>
                                <input type="file" class="form-control form-control-file" id="foto_sim"
                                    name="foto_sim" placeholder="{{ __('Foto SIM') }}" required autofocus>
                            </div>
                            <div class="form-group custom-file col-md-3">
                                <label for="foto_stnk">Foto STNK<span class="small text-danger">*</span></label>
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
                                <label for="merk">Merek<span class="small text-danger">*</span></label>
                                <input type="text" class="form-control" id="merk" name="merk"
                                    placeholder="{{ __('Toyota') }}" value="{{ old('merk') }}" required autofocus>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="type">Type<span class="small text-danger">*</span></label>
                                <input type="text" class="form-control" id="type" name="type"
                                    placeholder="{{ __('Kijang Inova') }}" value="{{ old('type') }}" required
                                    autofocus>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="production_year">Tahun Produksi<span
                                        class="small text-danger">*</span></label>
                                <select class="form-control" id="production_year" name="production_year"
                                    placeholder="{{ __('Tahun Produksi') }}" value="{{ old('production_year') }}"
                                    autofocus>
                                    @php
                                        $selectedYear = old('production_year', ''); // Get the value from old, or set to empty string if not present
                                        $currentYear = date('Y');
                                        for ($year = $currentYear; $year >= 2000; $year--) {
                                            $selected = $year == $selectedYear ? 'selected' : ''; // Set 'selected' attribute if year matches old value
                                            echo "<option value=\"$year\" $selected>$year</option>";
                                        }
                                    @endphp
                                </select>
                            </div>

                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="jenis">Jenis Kendaraan<span class="small text-danger">*</span></label>
                                <input type="text" class="form-control" id="jenis" name="jenis"
                                    placeholder="{{ __('Mobil Penumpang') }}" value="{{ old('jenis') }}" required>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="model">Model<span class="small text-danger">*</span></label>
                                <input type="text" class="form-control" id="model"name="model"
                                    placeholder="{{ __('Mini Bus') }}" value="{{ old('model') }}" required>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="seating_capacity">Kapasitas Kursi<span
                                        class="small text-danger">*</span></label>
                                <select class="form-control" id="seating_capacity" name="seating_capacity">
                                    <option value="">Pilih Kapasitas Kursi</option>
                                    <option value="4" {{ old('seating_capacity') == '4' ? 'selected' : '' }}>4
                                    </option>
                                    <option value="7" {{ old('seating_capacity') == '7' ? 'selected' : '' }}>7
                                    </option>
                                </select>
                            </div>

                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="license_plate_number">Nomor Kendaraan<span
                                        class="small text-danger">*</span></label>
                                <input type="text" class="form-control" id="license_plate_number"
                                    name="license_plate_number" placeholder="{{ __('DD 2023 YR') }}"
                                    value="{{ old('license_plate_number') }}" required>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="machine_number">Nomor Mesin<span class="small text-danger">*</span></label>
                                <input type="text" class="form-control" id="machine_number"name="machine_number"
                                    placeholder="{{ __('Nomor Mesin') }}" value="{{ old('machine_number') }}" required>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="isi_silinder">Isi Silinder<span class="small text-danger">*</span></label>
                                <select class="form-control" id="isi_silinder" name="isi_silinder" required>
                                    <option value="">Pilih Isi Silinder</option>
                                    <option value="3" {{ old('isi_silinder') == '3' ? 'selected' : '' }}>3</option>
                                    <option value="4" {{ old('isi_silinder') == '4' ? 'selected' : '' }}>4</option>
                                    <option value="5" {{ old('isi_silinder') == '5' ? 'selected' : '' }}>5</option>
                                    <option value="6" {{ old('isi_silinder') == '6' ? 'selected' : '' }}>6</option>
                                    <option value="8" {{ old('isi_silinder') == '8' ? 'selected' : '' }}>8</option>
                                </select>
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
