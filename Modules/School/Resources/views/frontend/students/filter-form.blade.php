@php
    $options = Modules\School\Services\StudentService::prepareFilter();
@endphp
<div class="row">
    <div class="col-9">
        <div class="form-group">
            <?php
            $field_name = 'major';
            $field_lable = __("school::$module_name.$field_name");
            $field_placeholder = "-- Pilih --";
            $required = "";
            $select_options = $options['majors'];
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            {{ html()->multiselect($field_name,$select_options)->name($field_name.'[]')->class('form-control')->attributes(["$required",'multiple' => 'multiple']) }}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-9">
        <div class="form-group">
            <?php
            $field_name = 'year_class';
            $field_lable = __("school::$module_name.$field_name");
            $field_placeholder = $field_lable;
            $required = "";
            $select_options = $options['year_class'];
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            {{ html()->multiselect($field_name,$select_options)->name($field_name.'[]')->class('form-control')->attributes(["$required",'multiple' => 'multiple']) }}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-6">
        <div class="form-group">
            <?php
            $field_name = 'height';
            $field_lable = "Min. TB (cm)";
            $field_placeholder = "Tinggi Minimal";
            $required = "";
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            {{ html()->text($field_name)->type("number")->placeholder($field_placeholder)->class('form-control')->attributes(["$required", 'aria-label'=>'Image', "min" => 0, "max" => 300, "step" =>0.1]) }}
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <?php
            $field_name = 'weight';
            $field_lable = "Min. BB (kg)";
            $field_placeholder = "Berat Minimal";
            $required = "";
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            {{ html()->text($field_name)->type("number")->placeholder($field_placeholder)->class('form-control')->attributes(["$required", 'aria-label'=>'Image', "min" => 0, "max" => 300, "step" =>0.1]) }}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="form-group">
            <?php
            $field_name = 'skills';
            $field_lable = __("school::$module_name.$field_name");
            $field_placeholder = $field_lable;
            $required = "";
            $select_options = $options['skills'];
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            <br>
            {{ html()->multiselect($field_name,$select_options)->name($field_name.'[]')->class('form-control')->attributes(["$required",'multiple' => 'multiple']) }}
        </div>
        <div class="form-group">
            <?php
            $field_name = 'must_have_all_skills';
            $field_lable = "Butuh Semua Keahlian";
            $field_placeholder = $field_lable;
            $required = "";
            ?>
            {{ html()->checkbox($field_name)->placeholder($field_placeholder)->attributes(["$required", 'aria-label'=>'Image']) }}
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="form-group">
            <?php
            $field_name = 'certificate';
            $field_lable = __("school::$module_name.$field_name");
            $field_placeholder = $field_lable;
            $required = "";
            $select_options = $options['certificate'];
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            <br>
            {{ html()->multiselect($field_name,$select_options)->name($field_name.'[]')->class('form-control')->attributes(["$required",'multiple' => 'multiple']) }}
        </div>
        <div class="form-group">
            <?php
            $field_name = 'must_have_all_certificate';
            $field_lable = "Butuh Semua Sertifikat";
            $field_placeholder = $field_lable;
            $required = "";
            ?>
            {{ html()->checkbox($field_name)->placeholder($field_placeholder)->attributes(["$required", 'aria-label'=>'Image']) }}
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
        </div>
    </div>
</div>

<!-- Select2 Library -->
<x-library.select2 />
<x-library.datetime-picker />

@push('after-styles')
<!-- File Manager -->
<link rel="stylesheet" href="{{ asset('vendor/file-manager/css/file-manager.css') }}">
@endpush

@push ('after-scripts')

<!-- Date Time Picker & Moment Js-->
<script type="text/javascript">

$(document).ready(function() {
    $('#skills').multiselect({
            enableFiltering: true,
        });

    $('#certificate').multiselect({
            enableFiltering: true,
        });

    $('#major').multiselect({
            enableFiltering: true,
        });


    $('#year_class').multiselect({
            enableFiltering: true,
        });
});

</script>

@endpush
