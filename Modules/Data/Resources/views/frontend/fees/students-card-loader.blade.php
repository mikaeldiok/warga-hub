
<div class="d-flex justify-content-between mb-1">
    <div id="units-count">
        Menampilkan {{$units->count()}} dari {{ $units->total() > 100 ? "100+" : $units->total()}} Siswa
    </div>
    <div id="units-loader">
        {{$units->links()}}
    </div>
</div>
<div class="row">
@foreach($units as $unit)
    <div class="col-3 pb-3 card-padding" style="margin-right: 0px;">
        @include('data::frontend.units.unit-card-big')
    </div>

@endforeach
</div>
<div class="d-flex justify-content-end">
    {{$units->links()}}
</div>

@push('after-scripts')
    @include("data::frontend.units.dynamic-scripts")
@endpush
