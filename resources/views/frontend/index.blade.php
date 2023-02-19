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
<div class="section">
  <div class="container">
    <div class="form-group mb-4 search">
      <input type="search" placeholder="Search..." aria-describedby="button-addon" class="form-control border-primary" data-search>
    </div>
  </div>

  <div class="container">
    @php
      $color_counter = 0
    @endphp
    <div class="row">
      @foreach($groups as $group)
        <div class="col-md-4 m-2"  data-filter-item data-filter-name="{{$group->name}}">
          <a href="#{{$group->name}}_modal" data-toggle="modal" data-target="#{{$group->name}}_modal">        
            <div class="card" style="border-color:{{$colors[$color_counter]}};" >
              <div class="card-body">
                  <h5 class="heading">{{$group->name}}</h5>
                <p></p>
              </div>
            </div>
          </a>
        </div>

        <div id="{{$group->name}}_modal" class="modal fade">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <!-- Header -->
              <div class="modal-header">
                <h1>{{$group->name}}</h1>
              </div>

              <!-- Body -->
              <div class="modal-body">
                @php
                  $color_counter_appsite = 0
                @endphp
                @foreach($group->appsites as $appsite)
                  <a href="{{$appsite->url}}">        
                    <div class="card my-3" style="border-color:{{$colors[$color_counter_appsite]}};" >
                      <div class="card-body">
                          <h5 class="heading">{{$appsite->name}}</h5>
                      </div>
                    </div>
                  </a>
                  @php
                    if($color_counter_appsite >= count($colors))
                      $color_counter_appsite = 0;
                    else
                      $color_counter_appsite++;
                  @endphp
                @endforeach
              </div>

              <!-- Footer -->
              <div class="modal-footer modal-footer--mine">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
              </div>
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
    </div>
  </div>
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
