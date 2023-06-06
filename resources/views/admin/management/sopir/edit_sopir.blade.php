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
            <div class="card shadow mb-4">
                <div class="row mt-4 align-items-center">
                    <div class="col-4">
                        <div class="card-profile-image ml-4">
                            <img class="rounded-circle avatar avatar" style="height: 125px; width: 125px;"
                                src="{{ asset(Storage::url($show_sopir['photo'])) }}" alt="">
                        </div>
                    </div>
                    <div class="col-8">
                        <div class="">
                            <h4 class="font-weight-bold">{{ $show_sopir['name'] }}</h4>
                            <div class="row">
                                <div class="col-6">{{ $show_sopir['email'] }}</div>
                                <div class="col-6 mr-0">{{ $show_sopir['no_hp'] }}</div>
                            </div>
                            <div class="mt-2 row">
                                <div class="col-6">
                                    <i class="fa fa-star text-warning"></i>
                                    <i class="fa fa-star text-warning"></i>
                                    <i class="fa fa-star text-warning"></i>
                                    <i class="fa fa-star text-warning"></i>
                                    <i class="fa fa-star"></i>
                                    <span class="text-success font-weight-bold ml-2">4/5</span>
                                </div>
                                <div class="col-6">
                                    <span>65 Penumpang</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">

                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link font-weight-bold active" id="home-tab1" data-toggle="tab" href="#home1"
                                role="tab" aria-controls="home1" aria-selected="true">Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link font-weight-bold" id="profile-tab1" data-toggle="tab" href="#profile1"
                                role="tab" aria-controls="profile1" aria-selected="false">Data Mobil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link font-weight-bold" id="contact-tab1" data-toggle="tab" href="#contact1"
                                role="tab" aria-controls="contact1" aria-selected="false">Umpan Balik</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">

                        {{-- Info Data Profile --}}
                        <div class="tab-pane fade show active" id="home1" role="tabpanel"
                            aria-labelledby="home-tab1">
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
                                    <div class="avatar avatar" style="height: 100px; width: 100px;">
                                        <img src="{{ asset(Storage::url($show_sopir['foto_ktp'])) }}" class="rounded"
                                            alt="...">
                                    </div>
                                </div>
                                <div class="col-4 text-center">
                                    <div class="avatar avatar" style="height: 100px; width: 100px;">
                                        <img src="{{ asset(Storage::url($show_sopir['foto_sim'])) }}" class="rounded"
                                            alt="...">
                                    </div>
                                </div>
                                <div class="col-4 text-center">
                                    <div class="avatar avatar" style="height: 100px; width: 100px;">
                                        <img src="{{ asset(Storage::url($show_sopir['foto_stnk'])) }}" class="rounded"
                                            alt="...">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Info Data Mobil --}}
                        <div class="tab-pane fade" id="profile1" role="tabpanel" aria-labelledby="profile-tab1">
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
                                    <div class="avatar avatar" style="height: 100px; width: 100px;">
                                        <img src="{{ asset(Storage::url($show_sopir['foto_ktp'])) }}" class="rounded"
                                            alt="...">
                                    </div>
                                </div>
                                <div class="col-4 text-center">
                                    <div class="avatar avatar" style="height: 100px; width: 100px;">
                                        <img src="{{ asset(Storage::url($show_sopir['foto_sim'])) }}" class="rounded"
                                            alt="...">
                                    </div>
                                </div>
                                <div class="col-4 text-center">
                                    <div class="avatar avatar" style="height: 100px; width: 100px;">
                                        <img src="{{ asset(Storage::url($show_sopir['foto_stnk'])) }}" class="rounded"
                                            alt="...">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Info Data Feedback --}}
                        <div class="tab-pane fade" id="contact1" role="tabpanel" aria-labelledby="contact-tab1">...
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-lg-6">

            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link font-weight-bold active" id="home-tab" data-toggle="tab" href="#home"
                        role="tab" aria-controls="home" aria-selected="true">Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link font-weight-bold" id="profile-tab" data-toggle="tab" href="#profile"
                        role="tab" aria-controls="profile" aria-selected="false">Data Mobil</a>
                </li>
                <li class="nav-item font-weight-bold">
                    <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab"
                        aria-controls="contact" aria-selected="false">Umpan Balik</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">

                {{-- Untuk Edit Data Diri Sopir --}}
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="card shadow mb-4">

                        <div class="card-header py-3">
                            <h6 class="heading-small font-weight-bold text-primary m-0">Data Diri Sopir</h6>
                        </div>

                        <div class="card-body">

                            <form method="POST" action="{{ route('sopir.update', $show_sopir->id) }}"
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
                                            <label for="address">Alamat</label>
                                            <input type="text" class="form-control" id="address" name="address"
                                                placeholder="{{ __('Alamat') }}"
                                                value="{{ old('address', $show_sopir['address']) }}" required>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-3  mb-2">
                                            <span class="d-block d-flex justify-content-center"
                                                for="is_smoking">Merokok?</span>
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
                                        <div class="form-group col-md-3">
                                            <label for="driver_age">Umur</label>
                                            <input type="number" class="form-control" id="driver_age" name="driver_age"
                                                placeholder="{{ __('Umur') }}"
                                                value="{{ old('driver_age', $show_sopir['driver_age']) }}" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="no_ktp">No KTP</label>
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
                                            <button type="submit" class="btn btn-primary">Save Changes</button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                        </div>

                    </div>
                </div>

                {{-- Untuk Edit Data Mobil Sopir --}}
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">

                </div>

                {{-- Untuk Edit Data Feedback Sopir --}}
                <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">

                </div>
            </div>

        </div>

    </div>

@endsection
