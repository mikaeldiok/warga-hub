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
    <div class="row"> 
      <div class="col">
        <a href="/" type="button" class="btn btn-primary">Home</a>
      </div>
      <div class="col">
        <div class="form-group">
          <label for="datetimepicker">Pilih Bulan dan Tahun:</label>
          <div class="input-group" id="datetimepicker-container">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fa fa-calendar"></i></span>
            </div>
            <input type="text" class="form-control datetimepicker" data-target="#datetimepicker" id="datetimepicker" name="datetimepicker" value="{{$datetime ?? ''}}"/>
            <div class="input-group-append">
              <button class="btn btn-primary" onclick="submitForm()" type="button">GO<i class="fa fa-arrow-right"></i></button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- BIAYA -->
  <div class="container my-4">
    <a href="{{route('frontend.parameters.download')}}?datetime={{request()->get('datetime')}}" type="button" class="btn btn-primary mb-2">Download</a>
    @include('performance::frontend.parameters.table')
  </div>

@endsection
<x-library.datetime-picker />

@push ('after-styles')
@endpush

@push ('after-scripts')
  <script type="text/javascript">
    function submitForm() {
      var datetime = document.getElementById("datetimepicker").value;
      var url = "{{route('frontend.parameters.index')}}?datetime=" + encodeURIComponent(datetime);
      window.location.href = url;
    }

    $(document).ready(function() {
      $(function () {
        $('#datetimepicker').datetimepicker({
          format: 'MMM/YYYY',
        })
      });      
    });

    $('#datetimepicker').on('click', function() {
        $(this).datetimepicker('show');
      });
  </script>

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
