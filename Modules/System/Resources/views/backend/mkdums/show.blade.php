@extends('backend.layouts.app')

@section('title') {{ __($module_action) }} {{ $module_title }} @endsection

@section('breadcrumbs')
<x-backend-breadcrumbs>
    <x-backend-breadcrumb-item route='{{route("backend.$module_name.index")}}' icon='{{ $module_icon }}' >
        {{ $module_title }}
    </x-backend-breadcrumb-item>
    <x-backend-breadcrumb-item type="active">{{ __($module_action) }}</x-backend-breadcrumb-item>
</x-backend-breadcrumbs>
@endsection

@section('content')
<?php
    //Total Donations
    $total = 0;
    foreach($$module_name_singular->donations as $donation){
        $total += $donation->amount;
    }
?>
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-8">
                <h4 class="card-title mb-0">
                    <i class="{{ $module_icon }}"></i> {{ $module_title }} <small class="text-muted">{{ __($module_action) }}</small>
                </h4>
                <div class="small text-muted">
                    @lang(":module_name Management Dashboard", ['module_name'=>Str::title($module_name)])
                </div>
            </div>
            <!--/.col-->
            <div class="col-4">
                <div class="float-right">
                    <a href="{{ route("backend.$module_name.index") }}" class="btn btn-secondary mt-1 btn-sm" data-toggle="tooltip" title="{{ ucwords($module_name) }} List"><i class="fas fa-list"></i> List</a>
                    @can('edit_'.$module_name)
                    <a href="{{ route("backend.$module_name.edit", $$module_name_singular) }}" class="btn btn-primary mt-1 btn-sm" data-toggle="tooltip" title="Edit {{ Str::singular($module_name) }} "><i class="fas fa-wrench"></i> Edit</a>
                    @endcan
                </div>
            </div>
            <!--/.col-->
        </div>
        <!--/.row-->


        <!-- Tab panes -->

        <div class="mt-4">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="donasi-tab" data-toggle="tab" href="#donasi" role="tab" aria-controls="donasi" aria-selected="true">Donasi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="detail-tab" data-toggle="tab" href="#detail" role="tab" aria-controls="detail" aria-selected="false">Detail</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="donasi" role="tabpanel" aria-labelledby="donasi-tab">
                    <h4 class="text-primary text-center my-4">Riwayat Donasi</h4>
                    
                    <tr>
                        <td>
                            <strong>Total Donasi</strong>  
                        </td>
                        <td> 
                            <h4>Rp. {{number_format($total, 2, ',', '.')}}</h4>                         
                        </td>
                    </tr>

                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Tanggal</th>
                                <th scope="col">Nominal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($$module_name_singular->donations as $donation)
                                <tr>
                                    <td>
                                    {{$donation->donation_date}}    
                                    </td>
                                    <td> 
                                        Rp. {{number_format($donation->amount, 2, ',', '.')}}                              
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table> 
                </div>
                <div class="tab-pane fade" id="detail" role="tabpanel" aria-labelledby="detail-tab">
                    <hr>
                    @include('backend.includes.show')

                    <hr>
                        @include('mkstarter::backend.includes.activitylog')
                    <hr>
                </div>
            </div>
        </div>
    </div>

    <div class="card-footer">
        <div class="row">
            <div class="col">
                <small class="float-right text-muted">
                    Updated: {{$$module_name_singular->updated_at->diffForHumans()}},
                    Created at: {{$$module_name_singular->created_at->isoFormat('LLLL')}}
                </small>
            </div>
        </div>
    </div>
</div>

@stop
