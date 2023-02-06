
<div class="d-flex justify-content-between mb-1">
    <div id="appsite-count">
        Menampilkan {{$appsite->count()}} dari {{ $appsite->total() > 100 ? "100+" : $appsite->total()}} Siswa
    </div>
    <div id="appsite-loader">
        {{$appsite->links()}}
    </div>
</div>
<div class="row">
@foreach($appsite as $appsite)
    <div class="col-3 pb-3 card-padding" style="margin-right: 0px;">
        @include('system::frontend.appsites.appsite-card-big')
    </div>

@endforeach
</div>
<div class="d-flex justify-content-end">
    {{$appsite->links()}}
</div>

@push('after-scripts')
    @include("system::frontend.appsites.dynamic-scripts")
@endpush
