@extends('frontend.layouts.app')

@section('title') {{app_name()}} @endsection

@php
  $colors= [
    "#6f42c1",
    "#dc3545",
    "#6610f2",
    "#0d6efd",
    "#ffc107",
    "#fd7e14",
    "#198754",
    "#0dcaf0",
    "#20c997",
    ]
@endphp

@section('content')
<div class="container">
  <div class="form-group mb-4 search">
    <input type="search" placeholder="Search..." aria-describedby="button-addon" class="form-control border-primary" data-search>
  </div>
</div>

<div class="container">
  @foreach($groups as $group)
    <div class="mb-5" data-filter-item-group>
        <div class="row">
          <div class="col-md-12">
            <h2>{{$group->name}}</h2>
          </div>
        </div>
        <div class="row">
          <!-- <div class="items"> -->
            @php
              $color_counter = 0
            @endphp
            @foreach($group->appsites as $appsite)
              <div class="col-md-4 m-2"  data-filter-item data-filter-name="{{$appsite->name}}">
                <div class="card" style="border-color:{{$colors[$color_counter]}};" >
                  <div class="card-body">
                    <a href="{{$appsite->url}}" class="">
                      <h5 class="heading">{{$appsite->name}}</h5>
                    </a>
                    <p>{{$appsite->url}}</p>
                    <!-- <p><a href="{{$appsite->url}}" class="link-underline">Learn More</a></p> -->
                  </div>
                </div>
              </div>

              @php
                if($color_counter >= count($colors))
                  $color_counter = 0;
                else
                  $color_counter++;
              @endphp
            @endforeach
          <!-- </div> -->
        </div>
    </div> <!-- .site-section -->
  @endforeach
  </div>
@endsection

@push ('after-styles')
<style>
  .search {
    margin: 30px;
  }


  .hidden {
    display: none;
  }
</style>
@endpush

@push ('after-scripts')

<script>
  $(document).ready(function(){
      $('[data-search]').on('keyup', function() {
        var searchVal = $(this).val();
        var filterItems = $('[data-filter-item]');

        if ( searchVal != '' ) {
          filterItems.addClass('hidden');
          $('[data-filter-item][data-filter-name*="' + searchVal.toLowerCase() + '"]').removeClass('hidden');
        } else {
          filterItems.removeClass('hidden');
        }
      });
  });
</script>

@endpush
