@extends('layouts.admin')

@section('main-content')

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">{{ __('Edit Data Sopir') }}</h1>

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
        <form method="POST" action="{{ route('addSopir') }}" class="user">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="form-group">
                <input type="text" class="form-control form-control-user" name="name" placeholder="{{ __('Name') }}" value="{{ old('name') }}" required autofocus>
            </div>

            <div class="form-group">
                <input type="text" class="form-control form-control-user" name="last_name" placeholder="{{ __('Last Name') }}" value="{{ old('last_name') }}" required>
            </div>

            <div class="form-group">
                <input type="email" class="form-control form-control-user" name="email" placeholder="{{ __('E-Mail Address') }}" value="{{ old('email') }}" required>
            </div>

            <div class="form-group">
                <input type="password" class="form-control form-control-user" name="password" placeholder="{{ __('Password') }}" required>
            </div>

            <div class="form-group">
                <input type="password" class="form-control form-control-user" name="password_confirmation" placeholder="{{ __('Confirm Password') }}" required>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-user btn-block">
                    {{ __('Register') }}
                </button>
            </div>
        </form>
    </div>

@endsection
