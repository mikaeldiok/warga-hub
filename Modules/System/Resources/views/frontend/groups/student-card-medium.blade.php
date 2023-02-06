<div class="card frontend-appsite appsite-card position-relative" style="width: 200px;height: 35rem;">
  <div class="position-absolute mx-1" style="left:0;">
    <a class="btn btn-sm btn-blue hover-tool rounded-bottom" href="#"><i class="fa fa-exchange"></i></a>
  </div>
  <div class="position-absolute mx-1" style="right:0;">
    <a class="btn btn-sm btn-warning hover-tool rounded-bottom" href="#"><i class="fa fa-bookmark"></i></a>
  </div>
  <a href="#"><img class="card-img-top img-fluid" src="{{$appsite->photo ? asset($appsite->photo) : asset('img/default-avatar.jpg') }}" alt="Image placeholder" style="max-height:190px;min-height:190px;object-fit: cover;"></a>
  <div class="card-body">
    @if($appsite->checkBookedBy(auth()->user()->corporation->id ?? 0)
      <button class="btn btn-block btn-danger" id="choose-appsite-{{$appsite->id}}">Batal</button>
    @else
      <button class="btn btn-block btn-success" id="choose-appsite-{{$appsite->id}}">Pilih</button>
    @endif
    <a href="#">
      <h4 class="card-title pt-3" style="font-size: 22px">{{\Illuminate\Support\Str::limit($appsite->name, 17, $end = '...')}}</h4>
    </a>
      <h4 class="card-title" style="font-size: 19px">{{$appsite->major}} - {{$appsite->year_class}}</h4>
    <!-- detail -->

    @include('system::frontend.appsites.appsite-card-detail')
    
    <!-- detail end -->
    <span class="donation-time mb-3 d-block">--</span>
    
  </div>
</div>

@push('after-scripts')
<script>
    $(document).ready(function(){
        $('#choose-appsite-{{$appsite->id}}').on('click', function(e) {
            $.ajax({
                type: "POST",
                url: '{{route("frontend.bookings.pickAppsite")}}',
                data: {
                    "corporation_id" : "{{auth()->user()->id}}",
                    "appsite_id" : "{{$appsite->id}}",
                    "status" : "Picked",
                    "_method":"POST",
                    "_token": "{{ csrf_token() }}"
                },
                success: function (response) {
                  if(response.isPicked){

                    console.log($(this));
                    $('#choose-appsite-{{$appsite->id}}').removeClass( 'btn-success');
                    $('#choose-appsite-{{$appsite->id}}').addClass( 'btn-danger');
                    $('#choose-appsite-{{$appsite->id}}').html( 'batal');

                    const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    showCloseButton: true,
                    timer: 3000,
                    timerProgressBar: true,
                    })

                    Toast.fire({
                    icon: 'success',
                    title: 'Siswa ditambahkan ke daftar anda'
                    })
                  }else{

                    $('#choose-appsite-{{$appsite->id}}').removeClass( 'btn-danger');
                    $('#choose-appsite-{{$appsite->id}}').addClass( 'btn-success');
                    $('#choose-appsite-{{$appsite->id}}').html( 'pilih');
                    
                    const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    showCloseButton: true,
                    timer: 3000,
                    timerProgressBar: true,
                    })

                    Toast.fire({
                    icon: 'error',
                    title: response.message
                    })
                  }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    showCloseButton: true,
                    timer: 3000,
                    timerProgressBar: true,
                    })

                    Toast.fire({
                    icon: 'error',
                    title: 'Terjadi kesalahan! Silakan coba beberapa saat lagi.'
                    })
                }
            });
        });
    });
</script>
@endpush