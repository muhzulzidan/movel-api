@extends('admin.layouts.admin')

@section('main-content')
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">{{ __('Data Penumpang') }}</h1>

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

        <!-- Content Column -->
        <div class="col-lg-12 mb-4">

            <!-- Databel Penumpang -->
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>No HP</th>
                                    <th>Alamat</th>
                                    <th>Gender</th>
                                    <th>Usia</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($passengers as $penumpang)
                                    <tr>
                                        <td class="d-flex align-items-center">
                                            <img class="img-profile rounded-circle avatar"
                                                src="{{ asset(Storage::url($penumpang->photo)) }}" alt="">
                                            <div class="pl-3 email">
                                                <span class="font-weight-bold">
                                                @if ($penumpang->hasVerifiedEmail())
                                                <i class="text-primary fas fa-user-check"></i>
                                                @else
                                                <i class="text-secondary fas fa-user-times"></i>
                                                @endif
                                                {{ $penumpang->name }}
                                                </span>
                                                <span class="d-block">{{ $penumpang->email }}</span>
                                            </div>
                                        </td>
                                        <td>{{ $penumpang->no_hp }}</td>
                                        <td>{{ $penumpang->address }}</td>
                                        <td class="text-center">{{ $penumpang->gender }}</td>
                                        <td class="text-center">{{ $penumpang->age_passenger }}</td>
                                        <td class="text-center">
                                            {{-- <a href="{{ route('penumpang.show', $penumpang->id) }}" class="btn btn-info">
                                                <i class="fas fa-info-circle"></i> {{ __('Detail') }}
                                            </a> --}}
                                            <a href="{{ route('penumpang.edit', $penumpang->id) }}"
                                                class="btn btn-primary">
                                                <i class="fas fa-edit"></i> {{ __('Edit') }}
                                            </a>
                                            <a class="btn btn-danger" id="{{ $penumpang->id }}" href="#"
                                                data-toggle="modal" data-target="#deleteModal-{{ $penumpang->id }}">
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

    <!-- Delete Modal-->
    @foreach ($passengers as $penumpang)
    <div class="modal fade" id="deleteModal-{{ $penumpang->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel-{{ $penumpang->id }}" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel-{{ $penumpang->id }}">{{ __('Sure to Delete?') }}</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Are you sure you want to delete this data? {{ $penumpang->name }} ID: {{ $penumpang->id }}</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <form action="{{ route('penumpang.destroy', $penumpang->id) }}" method="POST">
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
