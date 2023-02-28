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
  ];
  $param_points = Config::get('performance.parameters');
@endphp

@section('content')
<div class="section">
  <div class="container my-4">
    <a href="/" type="button" class="btn btn-primary">Home</a>
  </div>
  <!-- BIAYA -->
  <div class="container my-4">
    <div class="table-responsive">
      @if($parameters)
        <table class="table table-sm table-fixed">
          <thead>
            <tr>
              <th scope="row" class="fixed-column bg-secondary" style="width: 250px;">PARAMETER</th>
              @foreach($parameters as $parameter)
                <th scope="col">{{ $parameter->unit->name}}
                  <small>{{\Carbon\Carbon::parse($parameter->date)->format('d/m')}}</small>
                </th>
              @endforeach
            </tr>
          </thead>
          <tbody>
            @foreach($param_points as $key => $param_point)
              <tr>
                <th scope="row" class="fixed-column bg-light-gray" >{{$param_point}}</th>
                @foreach($parameters as $parameter)
                    <td scope="col">{{ $parameter->$key}}</td>
                @endforeach
              </tr>
            @endforeach
          </tbody>
        </table>
      @endif
    </div>
  </div>

@endsection

@push ('after-styles')
<style>
  
  th:first-child {
    width: auto;
  }

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
