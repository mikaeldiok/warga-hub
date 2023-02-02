<div class="card frontend-mkdum shadow mkdum-card position-relative" style="width: 200px;height: 35rem;">
  @php  
  //<div class="position-absolute mx-1" style="left:0;">
  //  <a class="btn btn-sm btn-blue hover-tool rounded-bottom" href="#"><i class="fa fa-exchange"></i></a>
  //</div>
  //<div class="position-absolute mx-1" style="right:0;">
  //  <a class="btn btn-sm btn-save hover-tool rounded-bottom" href="#"><i class="fa fa-bookmark"></i></a>
  //</div>
  @endphp
  <a href="#"><img class="card-img-top p-1" src="{{$mkdum->photo ? asset($mkdum->photo) : asset('img/default-avatar.jpg') }}" alt="Image placeholder" style="max-height:190px;min-height:190px;object-fit: contain;"></a>
  <div class="card-body">
    @if($mkdum->checkBookedBy(auth()->user()->corporation->id ?? 0))
      <button class="btn btn-block btn-danger choose-mkdum" data-id="{{$mkdum->id}}" id="choose-mkdum-{{$mkdum->id}}">BATAL</button>
    @else
      <button class="btn btn-block btn-success choose-mkdum" data-id="{{$mkdum->id}}" id="choose-mkdum-{{$mkdum->id}}">PILIH</button>
    @endif
    <a href="{{route('frontend.mkdums.show',[$mkdum->id,$mkdum->mkdum_id])}}">
      <h4 class="card-title pt-3" style="font-size: 22px">{{\Illuminate\Support\Str::limit($mkdum->name, 17, $end = '...')}}</h4>
    </a>
      <h4 class="card-title" style="font-size: 19px">{{$mkdum->major}} - {{$mkdum->year_class}}</h4>
    <!-- detail -->

    @include('mkstarter::frontend.mkdums.mkdum-card-detail')
    
    <!-- detail end -->
    <span class="donation-time mb-3 d-block">--</span>
    
  </div>
</div>
