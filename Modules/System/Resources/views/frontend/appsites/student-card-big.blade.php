<div class="card frontend-appsite shadow appsite-card position-relative" style="width: 200px;height: 35rem;">
  @php  
  //<div class="position-absolute mx-1" style="left:0;">
  //  <a class="btn btn-sm btn-blue hover-tool rounded-bottom" href="#"><i class="fa fa-exchange"></i></a>
  //</div>
  //<div class="position-absolute mx-1" style="right:0;">
  //  <a class="btn btn-sm btn-save hover-tool rounded-bottom" href="#"><i class="fa fa-bookmark"></i></a>
  //</div>
  @endphp
  <a href="#"><img class="card-img-top p-1" src="{{$appsite->photo ? asset($appsite->photo) : asset('img/default-avatar.jpg') }}" alt="Image placeholder" style="max-height:190px;min-height:190px;object-fit: contain;"></a>
  <div class="card-body">
    @if($appsite->checkBookedBy(auth()->user()->corporation->id ?? 0))
      <button class="btn btn-block btn-danger choose-appsite" data-id="{{$appsite->id}}" id="choose-appsite-{{$appsite->id}}">BATAL</button>
    @else
      <button class="btn btn-block btn-success choose-appsite" data-id="{{$appsite->id}}" id="choose-appsite-{{$appsite->id}}">PILIH</button>
    @endif
    <a href="{{route('frontend.appsites.show',[$appsite->id,$appsite->appsite_id])}}">
      <h4 class="card-title pt-3" style="font-size: 22px">{{\Illuminate\Support\Str::limit($appsite->name, 17, $end = '...')}}</h4>
    </a>
      <h4 class="card-title" style="font-size: 19px">{{$appsite->major}} - {{$appsite->year_class}}</h4>
    <!-- detail -->

    @include('system::frontend.appsites.appsite-card-detail')
    
    <!-- detail end -->
    <span class="donation-time mb-3 d-block">--</span>
    
  </div>
</div>
