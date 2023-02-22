
<div class="d-flex justify-content-between mb-1">
    <div id="parameters-count">
        Menampilkan {{$parameters->count()}} dari {{ $parameters->total() > 100 ? "100+" : $parameters->total()}} Siswa
    </div>
    <div id="parameters-loader">
        {{$parameters->links()}}
    </div>
</div>
<div class="row">
@foreach($parameters as $parameter)
    <div class="col-3 pb-3 card-padding" style="margin-right: 0px;">
        @include('performance::frontend.parameters.parameter-card-big')
    </div>

@endforeach
</div>
<div class="d-flex justify-content-end">
    {{$parameters->links()}}
</div>

@push('after-scripts')
    @include("performance::frontend.parameters.dynamic-scripts")
@endpush
