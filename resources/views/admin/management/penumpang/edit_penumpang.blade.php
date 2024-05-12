@extends('admin.layouts.admin')

@section('main-content')

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">{{ __('Data Penumpang ') }}
        @if ($show_penumpang)
            <strong>{{ $show_penumpang['name'] }}</strong>
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
                            @if ($show_penumpang && isset($show_penumpang['photo']) && $show_penumpang['photo'] != null)
                                <img class="rounded-circle avatar" style="height: 120px; width: 120px;"
                                    src="{{ asset(Storage::url($show_penumpang['photo'])) }}" alt="">
                            @else
                                <img class="rounded-circle avatar" style="height: 120px; width: 120px;" src=""
                                    alt="">
                            @endif

                        </div>
                    </div>
                    <div class="col-9">
                        <h4 class="font-weight-bold">{{ $show_penumpang['name'] }}</h4>
                        <div class="row">
                            <div class="col-6">{{ $show_penumpang['email'] }}</div>
                            <div class="col-6 mr-0">{{ $show_penumpang['no_hp'] }}</div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td>Umur</td>
                                <td class="font-weight-bold">{{ $show_penumpang['age_passenger'] }}</td>
                            </tr>
                            <tr>
                                <td>Jenis Kelamnin</td>
                                <td class="font-weight-bold">{{ $show_penumpang['gender'] }}</td>
                            </tr>
                            <tr>
                                <td>Alamat</td>
                                <td class="font-weight-bold">{{ $show_penumpang['address'] }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <div class="col-lg-6">
            <h5 class="text-primary font-weight-bold border-left-primary pl-2">Panel Edit Data</h5>

            <div class="card shadow mb-4">

                <div class="card-body">

                    <form method="POST" action="{{ route('penumpang.update', $show_penumpang->id) }}"
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
                                            placeholder="Name" value="{{ old('name', $show_penumpang['name']) }}">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group focused">
                                        <label class="form-control-label" for="email">Email<span class="small text-danger">*</span></label>
                                        <input type="text" id="email" class="form-control" name="email"
                                            placeholder="Last name" value="{{ old('email', $show_penumpang['email']) }}">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group focused">
                                        <label class="form-control-label" for="no_hp">No. HP<span
                                                class="small text-danger">*</span></label>
                                        <input type="text" id="no_hp" class="form-control" name="no_hp"
                                            placeholder="Last name" value="{{ old('no_hp', $show_penumpang['no_hp']) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group custom-file col-md-4">
                                    <label for="photo">Foto Profile<span class="small text-danger">*</span></label>
                                    <input type="file" class="form-control form-control-file" id="photo"
                                        name="photo" placeholder="{{ __('Foto Profile') }}"
                                        value="{{ $show_penumpang['photo'] ? basename($show_penumpang['photo']) : '' }}"
                                        autofocus>
                                    <input type="hidden" id="existing-photo" name="existing_photo"
                                        value="{{ $show_penumpang['photo'] }}">
                                </div>
                                <div class="form-group col-md-8">
                                    <label for="address">Alamat<span class="small text-danger">*</span></label>
                                    <input type="text" class="form-control" id="address" name="address"
                                        placeholder="{{ __('Alamat') }}"
                                        value="{{ old('address', $show_penumpang['address']) }}" required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6  mb-2">
                                    <span class="d-block d-flex justify-content-center" for="gender">Jenis
                                        Kelamin<span class="small text-danger">*</span></span>
                                    <div class="d-block d-flex justify-content-center my-2">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="gender"
                                                id="inlineRadio2" value="Laki-Laki"
                                                @if($show_penumpang->gender === "Laki-Laki") checked @endif>
                                            <label class="form-check-label" for="inlineRadio2">Laki-Laki</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="gender"
                                                id="inlineRadio1" value="Perempuan"
                                                @if($show_penumpang->gender === "Perempuan") checked @endif>
                                            <label class="form-check-label" for="inlineRadio1">Perempuan</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="age_passenger">Umur<span class="small text-danger">*</span></label>
                                    <input type="number" class="form-control" id="age_passenger" name="age_passenger"
                                        placeholder="{{ __('Umur') }}"
                                        value="{{ old('age_passenger', $show_penumpang['age_passenger']) }}" required>
                                </div>
                            </div>
                        </div>

                        <!-- Button -->
                        <div class="col-12">
                            <div class="row">
                                <div class="col text-center">
                                    <button type="submit" class="btn btn-primary btn-block">Simpan Perubahan</button>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>

            </div>

        </div>

    </div>

@endsection
