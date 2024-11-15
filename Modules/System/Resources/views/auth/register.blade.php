@extends('auth.layout')

@section('title') @lang('Register') @endsection

@section('content')

<div class="main-content bg-light">

    <div class="bg-gradient-primary py-7 py-lg-8">
        <div class="container">
            <div class="header-body text-center mb-6">
                <div class="row justify-content-center">
                    <div class="col-xl-5 col-lg-6 col-md-8 px-5">
                        <h1 class="text-white">
                            @lang('Pendaftaran Donatur')
                        </h1>
                        <p class="text-lead text-white">
                            @lang("Silakan Mengisi Formulir di bawah ini")
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="separator separator-bottom separator-skew zindex-100">
            <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
                <polygon class="fill-white" points="2560 0 2560 100 0 100"></polygon>
            </svg>
        </div>
    </div>

    <div class="container mt--9 pb-5">

        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="card bg-secondary border border-soft">

                    <div class="card-body px-lg-5 py-lg-5">
                        <div class="text-center text-muted mb-4">
                            <small class="text-danger"> @lang("Semua isian wajib diisi.") </small>
                        </div>

                        @include('flash::message')

                        @if ($errors->any())
                        <!-- Validation Errors -->
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <p>
                                <i class="fas fa-exclamation-triangle"></i> @lang('Please fix the following errors & try again!')
                            </p>
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>

                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @endif

                        <form role="form" method="POST" action="{{ route('auth.appsites.register') }}">
                            @csrf
                            
                            @include('system::auth.register-form')

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">
                                    @lang("Daftar")
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-6 text-left">
                        <!-- <a href="{{ route('password.request') }}" class="text-primary">
                            <small>{{ __('Forgot Your Password?') }}</small>
                        </a> -->
                    </div>

                    <div class="col-6 text-right">
                        <a href="{{ route('auth.appsites.login') }}" class="text-primary">
                            <small>{{ __('Login to account') }}</small>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
