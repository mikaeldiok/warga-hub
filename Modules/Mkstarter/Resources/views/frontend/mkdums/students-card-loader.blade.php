
<div class="d-flex justify-content-between mb-1">
    <div id="mkdums-count">
        Menampilkan {{$mkdums->count()}} dari {{ $mkdums->total() > 100 ? "100+" : $mkdums->total()}} Siswa
    </div>
    <div id="mkdums-loader">
        {{$mkdums->links()}}
    </div>
</div>
<div class="row">
@foreach($mkdums as $mkdum)
    <div class="col-3 pb-3 card-padding" style="margin-right: 0px;">
        @include('mkstarter::frontend.mkdums.mkdum-card-big')
    </div>

@endforeach
</div>
<div class="d-flex justify-content-end">
    {{$mkdums->links()}}
</div>

@push('after-scripts')
    @include("mkstarter::frontend.mkdums.dynamic-scripts")
@endpush
