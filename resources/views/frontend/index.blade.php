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
            <div class="card" style="width:400px">
              <div class="card-body">
                <a href="{{$appsite->url}}" class="">
                  <h3 class="heading">{{$appsite->name}}</h3>
                </a>
                <p>{{$appsite->url}}</p>
                <!-- <p><a href="{{$appsite->url}}" class="link-underline">Learn More</a></p> -->
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
