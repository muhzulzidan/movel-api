@extends('layouts.admin')

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

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <form method="POST" action="{{ route('addSopir') }}" class="user">

                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="name">Nama Lengkap</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="{{ __('Nama Lengkap') }}" value="{{ old('name') }}" required autofocus>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="{{ __('E-Mail Address') }}" value="{{ old('email') }}" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="no_hp">No HP</label>
                                <input type="number" class="form-control" id="no_hp" name="no_hp"
                                    placeholder="{{ __('No HP') }}" value="{{ old('no_hp') }}" required>
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
                            <div class="form-group custom-file col-md-4">
                                <label for="photo">Foto Profile</label>
                                <input type="file" class="form-control form-control-file" id="photo" name="photo"
                                    placeholder="{{ __('Foto Profile') }}" value="{{ old('photo') }}" required autofocus>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="address">Alamat</label>
                                <input type="text" class="form-control" id="address" name="address"
                                    placeholder="{{ __('Alamat') }}" value="{{ old('address') }}" required>
                            </div>
                            <div class="form-group col-md-2  mb-3">
                                <span class="d-block d-flex justify-content-center" for="is_smoking">Merokok?</span>
                                <div class="d-block d-flex justify-content-center my-2">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="is_smoking"
                                            id="inlineRadio2" value="1">
                                        <label class="form-check-label" for="inlineRadio2">Ya</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="is_smoking"
                                            id="inlineRadio1" value="0" checked>
                                        <label class="form-check-label" for="inlineRadio1">Tidak</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="driver_age">Umur</label>
                                <input type="number" class="form-control" id="driver_age" name="driver_age"
                                    placeholder="{{ __('Umur') }}" value="{{ old('driver_age') }}" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="no_ktp">No KTP</label>
                                <input type="number" class="form-control" id="no_ktp" name="no_ktp"
                                    placeholder="{{ __('No KTP') }}" value="{{ old('no_ktp') }}" required>
                            </div>
                            <div class="form-group custom-file col-md-3">
                                <label for="foto_ktp">Foto KTP</label>
                                <input type="file" class="form-control form-control-file" id="foto_ktp" name="foto_ktp"
                                    placeholder="{{ __('Foto KTP') }}" value="{{ old('foto_ktp') }}" required autofocus>
                            </div>
                            <div class="form-group custom-file col-md-3">
                                <label for="foto_sim">Foto SIM</label>
                                <input type="file" class="form-control form-control-file" id="foto_sim" name="foto_sim"
                                    placeholder="{{ __('Foto SIM') }}" value="{{ old('foto_sim') }}" required autofocus>
                            </div>
                            <div class="form-group custom-file col-md-3">
                                <label for="foto_stnk">Foto STNK</label>
                                <input type="file" class="form-control form-control-file" id="foto_stnk" name="foto_stnk"
                                    placeholder="{{ __('Foto STNK') }}" value="{{ old('foto_stnk') }}" required autofocus>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-user btn-block">
                                {{ __('Register') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
