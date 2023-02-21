<div class="card frontend-mkdum mkdum-card position-relative" style="width: 200px;height: 35rem;">
  <div class="position-absolute mx-1" style="left:0;">
    <a class="btn btn-sm btn-blue hover-tool rounded-bottom" href="#"><i class="fa fa-exchange"></i></a>
  </div>
  <div class="position-absolute mx-1" style="right:0;">
    <a class="btn btn-sm btn-warning hover-tool rounded-bottom" href="#"><i class="fa fa-bookmark"></i></a>
  </div>
  <a href="#"><img class="card-img-top img-fluid" src="{{$mkdum->photo ? asset($mkdum->photo) : asset('img/default-avatar.jpg') }}" alt="Image placeholder" style="max-height:190px;min-height:190px;object-fit: cover;"></a>
  <div class="card-body">
    @if($mkdum->checkBookedBy(auth()->user()->corporation->id ?? 0)
      <button class="btn btn-block btn-danger" id="choose-mkdum-{{$mkdum->id}}">Batal</button>
    @else
      <button class="btn btn-block btn-success" id="choose-mkdum-{{$mkdum->id}}">Pilih</button>
    @endif
    <a href="#">
      <h4 class="card-title pt-3" style="font-size: 22px">{{\Illuminate\Support\Str::limit($mkdum->name, 17, $end = '...')}}</h4>
    </a>
      <h4 class="card-title" style="font-size: 19px">{{$mkdum->major}} - {{$mkdum->year_class}}</h4>
    <!-- detail -->

    @include('mkstarter::frontend.mkdums.mkdum-card-detail')
    
    <!-- detail end -->
    <span class="donation-time mb-3 d-block">--</span>
    
  </div>
</div>

@push('after-scripts')
<script>
    $(document).ready(function(){
        $('#choose-mkdum-{{$mkdum->id}}').on('click', function(e) {
            $.ajax({
                type: "POST",
                url: '{{route("frontend.bookings.pickMkdum")}}',
                data: {
                    "corporation_id" : "{{auth()->user()->id}}",
                    "mkdum_id" : "{{$mkdum->id}}",
                    "status" : "Picked",
                    "_method":"POST",
                    "_token": "{{ csrf_token() }}"
                },
                success: function (response) {
                  if(response.isPicked){

                    console.log($(this));
                    $('#choose-mkdum-{{$mkdum->id}}').removeClass( 'btn-success');
                    $('#choose-mkdum-{{$mkdum->id}}').addClass( 'btn-danger');
                    $('#choose-mkdum-{{$mkdum->id}}').html( 'batal');

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

                    $('#choose-mkdum-{{$mkdum->id}}').removeClass( 'btn-danger');
                    $('#choose-mkdum-{{$mkdum->id}}').addClass( 'btn-success');
                    $('#choose-mkdum-{{$mkdum->id}}').html( 'pilih');
                    
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