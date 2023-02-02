@extends('frontend.layouts.app')

@section('title') {{app_name()}} @endsection

@section('content')

<section class="section-header pb-6 pb-lg-10 bg-primary text-white">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 text-center">
                <h1 class="display-1 mb-4">Halo kawan Peduli!</h1>
                <p class="lead text-muted">
                    Selamat datang di sistem Gerakan Peduli Pendidikan Yayasan Pendidikan Warga Surakarta
                </p>
                <a href="{{ route('frontend.donators.index') }}" class="btn btn-danger btn-lg m-2 rounded-pill pr-4" title="cek_status"><i class="fas fa-user-tie mx-2"></i>Donatur</a>
                <small class ="d-block mb-4">Sudah punya akun? Silakan masuk melalui tombol diatas</small>
                @include('frontend.includes.messages')
            </div>
        </div>
    </div>
</section>

<section class="section section-lg line-bottom-light bg-light">
    <div class="container mt-n7 mt-lg-n12 z-2">
        <div class="row">

            <div class="col-lg-12 mb-5">
                <div class="card bg-white border-light shadow-soft flex-md-row no-gutters p-4">
                    <div class="card-body d-flex flex-column justify-content-between col-auto py-4 p-lg-5">
                        <h5 class="text-center">Daftar dana masuk yang sudah terkonfirmasi</h5>       
                    
                        {{ $dataTable->table() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push ('after-styles')
<!-- DataTables Fund and Extensions -->
<link rel="stylesheet" href="{{ asset('vendor/datatable/datatables.min.css') }}">

@endpush

@push ('after-scripts')

<script type="text/javascript" src="{{ asset('vendor/datatable/datatables.min.js') }}"></script>
        
<script src="/vendor/datatables/buttons.server-side.js"></script>

<!-- DataTables Fund and Extensions -->
{!! $dataTable->scripts()  !!}
@endpush

