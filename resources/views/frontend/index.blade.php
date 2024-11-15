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
<div class="">
  <div class="container my-3 text-center">
    <h1>Tahun Ajaran {{setting('tahun_ajaran')}} </h1>
  </div>
  <div class="container my-3">
    <div class="row">
      <div class="col m-3 p-2 border rounded">
        {!! $totalStudentChart->container() !!}
      </div>
      <div class="col m-3 p-2 border rounded">
        {!! $teacherPerUnitChart->container() !!}
      </div>
    </div>
  </div>

  <!-- BIAYA -->
  <div class="container my-4">
    <h2 class="text-center mb-4">Biaya Pendidikan Tahun Ajaran {{setting('tahun_ajaran')}}</h2>
    <div class="table-responsive">
      <!-- <table class="table table-fixed">
        <thead>
          <tr>
            <th scope="col" class="fixed-column bg-light-gray">BIAYA</th>
            @foreach($fees as $fee)
              <th scope="col">{{ $fee->name_or_jurusan}}</th>
            @endforeach
          </tr>
        </thead>
        <tbody>
          <tr>
            <th scope="row" class="fixed-column bg-light-gray">DP</th>
            @foreach($fees as $fee)
              <td scope="col">{{$fee->DP ? "Rp.".number_format($fee->DP,0,"",".") : ""}}</td>
            @endforeach
          </tr>
          <tr>
            <th scope="row" class="fixed-column bg-light-gray">DPP</th>
            @foreach($fees as $fee)
              <td scope="col">{{$fee->DPP ? "Rp.".number_format($fee->DPP ,0,"",".") : ""}}</td>
            @endforeach
          </tr>
          <tr>
            <th scope="row" class="fixed-column bg-light-gray">SPP</th>
            @foreach($fees as $fee)
              <td scope="col">{{$fee->SPP ? "Rp.".number_format($fee->SPP,0,"",".") : ""}}</td>
            @endforeach
          </tr>
          <tr>
            <th scope="row" class="fixed-column bg-light-gray">UP</th>
            @foreach($fees as $fee)
              <td scope="col">{{$fee->UP ? "Rp.".number_format($fee->UP,0,"",".") : ""}}</td>
            @endforeach
          </tr>
        </tbody>
      </table> -->

      <table class="table table-fixed">
        <thead>
          <tr>
            <th scope="col" class="fixed-column bg-light-gray">BIAYA</th>
            <th scope="row" class="fixed-column bg-light-gray">SPP</th>
            <th scope="row" class="fixed-column bg-light-gray">SPM</th>
          </tr>
        </thead>
        <tbody>
          @foreach($fees as $fee)
            <tr>
              <th scope="row" class="fixed-column bg-light-gray">{{$fee->name_or_jurusan}}</th>
              <td scope="col">{{$fee->SPP ? "Rp.".number_format($fee->SPP,0,"",".") : ""}}</td>
              <td scope="col">{{$fee->SPM ? "Rp.".number_format($fee->SPM,0,"",".") : ""}}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  <div class="container text-center my-4">
    <a href="{{route('frontend.parameters.index')}}" type="button btn-lg" class="">
      <div class="card" style="border-color:#FF00FF;border-width: medium;" >
        <div class="card-body">
            <h1 class="heading"></span>Performa Unit<span><i class="mx-2 fa-solid fa-chart-line"></i></span></h1>
          <p></p>
        </div>
      </div>
    </a>
  </div>

  <div class="container">
    @php
      $color_counter = 0
    @endphp
    <div class="row dflex justify-content-center">
      @foreach($groups as $group)
        <div class="col-6 col-lg-6 my-2"  data-filter-item data-filter-name="{{$group->name}}">
          <a href="#{{$group->name}}_modal" data-toggle="modal" data-target="#{{$group->name}}_modal">
            <div class="card" style="border-color:{{$colors[$color_counter]}};" >
              <div class="card-body">
                  <h3 class="heading"><span><i class="{{$group->icon}} mr-2"></i></span>{{$group->name}}</h3>
                <p></p>
              </div>
            </div>
          </a>
        </div>

        <div id="{{$group->name}}_modal" class="modal fade">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h1>{{$group->name}}</h1>
              </div>

              <div class="modal-body">
                @php
                  $color_counter_appsite = 0
                @endphp
                @foreach($group->appsites as $appsite)
                  <a href="{{$appsite->url}}">

                    @php
                      if(!array_key_exists($color_counter_appsite,$colors))
                        $color_counter_appsite = 0;
                    @endphp
                    <div class="card my-3" style="border-color:{{$colors[$color_counter_appsite]}};" >
                      <div class="card-body">
                        <h5 class="heading"><span><i class="{{$appsite->icon}} mr-2"></i>{{$appsite->name}}<span><i class="fa-solid fa-arrow-right ml-2"></i></span></h5>
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

  .table-fixed th.fixed-column,
  .table-fixed td.fixed-column {
    position: sticky;
    left: 0;
    z-index: 1;
}
</style>
@endpush

@push ('after-scripts')

{!! $totalStudentChart->script() !!}
{!! $teacherPerUnitChart->script() !!}

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

  $(function(){
    $('.table-fixed').on('scroll', function(){
      $('.fixed-column').css('left', $(this).scrollLeft());
    });
  });
</script>

@endpush
