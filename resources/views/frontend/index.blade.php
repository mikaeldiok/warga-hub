@extends('frontend.layouts.app')

@section('title') {{app_name()}} @endsection

@section('content')
  @foreach($groups as $group)
    <div class="site-section border-top">
      <div class="container">
        <div class="row mb-5">
          <div class="col-md-12">
            <h2>{{$group->name}}</h2>
          </div>
        </div>
        <div class="row">
          @foreach($group->appsites as $appsite)
          <div class="col-md-4">
            <div class="media block-6">
              <div class="icon text-warning"><span class="ion-ios-contacts"></span></div>
              <div class="media-body">
                <h3 class="heading">{{$appsite->name}}</h3>
                <p>{{$appsite->url}}</p>
                <p><a href="#" class="link-underline">Learn More</a></p>
              </div>
            </div> 
          </div>
          @endforeach

        </div>
      </div>
    </div> <!-- .site-section -->
  @endforeach
@endsection

@push ('after-styles')
@endpush

@push ('after-scripts')


@endpush
