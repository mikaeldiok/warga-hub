
<div class="d-flex justify-content-between mb-1">
    <div id="students-count">
        Menampilkan {{$students->count()}} dari {{ $students->total() > 100 ? "100+" : $students->total()}} Siswa
    </div>
    <div id="students-loader">
        {{$students->links()}}
    </div>
</div>
<div class="row">
@foreach($students as $student)
    <div class="col-3 pb-3 card-padding" style="margin-right: 0px;">
        @include('school::frontend.students.student-card-big')
    </div>

@endforeach
</div>
<div class="d-flex justify-content-end">
    {{$students->links()}}
</div>

@push('after-scripts')
    @include("school::frontend.students.dynamic-scripts")
@endpush
