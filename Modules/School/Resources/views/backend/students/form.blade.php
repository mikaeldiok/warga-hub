
<div class="row">
    <div class="col-12 col-sm-6">
        <div class="form-group">
            <?php
            $field_name = 'available';
            $field_lable = label_case("Available?");
            $field_placeholder = "-- Pilih --";
            $required = "required";
            $select_options = [
                '1' => 'Ya',
                '0' => 'Tidak',
            ];
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            {{ html()->select($field_name, $select_options)->placeholder($field_placeholder)->class('form-control')->attributes(["$required"]) }}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-6">
        <div class="form-group">
            <?php
            $field_name = 'name';
            $field_lable = __("school::$module_name.$field_name");
            $field_placeholder = $field_lable;
            $required = "required";
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            {{ html()->text($field_name)->placeholder($field_placeholder)->class('form-control')->attributes(["$required", 'aria-label'=>'Image']) }}
        </div>
    </div>
    <div class="col-3">
        <div class="form-group">
            <?php
            $field_name = 'photo';
            $field_lable = __("school::$module_name.$field_name");
            $field_placeholder = $field_lable;
            $required = "";
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            <input id="{{$field_name}}" name="{{$field_name}}" multiple="" type="file">
        </div>
    </div>
    <div class="col-3">
        @if($module_action == "Edit")
            <img src="{{asset($$module_name_singular->photo)}}" class="user-profile-image img-fluid img-thumbnail" style="max-height:200px; max-width:200px;" />
        @endif
    </div>
</div>
<div class="row">
    <div class="col-6">
        <div class="form-group">
            <?php
            $field_name = 'student_id';
            $field_lable = __("school::$module_name.$field_name");
            $field_placeholder = $field_lable;
            $required = "required";
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            {{ html()->text($field_name)->placeholder($field_placeholder)->class('form-control')->attributes(["$required", 'aria-label'=>'Image']) }}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 col-sm-6">
        <div class="form-group">
            <?php
            $field_name = 'gender';
            $field_lable = label_case($field_name);
            $field_placeholder = "-- Pilih --";
            $required = "required";
            $select_options = [
                'L' => 'Laki-laki',
                'P' => 'Perempuan',
            ];
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            {{ html()->select($field_name, $select_options)->placeholder($field_placeholder)->class('form-control')->attributes(["$required"]) }}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 col-sm-6">
        <div class="form-group">
            <?php
            $field_name = 'religion';
            $field_lable = label_case($field_name);
            $field_placeholder = "-- Pilih --";
            $required = "required";
            $select_options = [
                'Islam'     => 'Islam',
                'Kristen'   => 'Kristen',
                'Katolik'   => 'Katolik',
                'Hindu'     => 'Hindu',
                'Budha'     => 'Budha',
                'Konghucu'  => 'Konghucu',
            ];
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            {{ html()->select($field_name, $select_options)->placeholder($field_placeholder)->class('form-control')->attributes(["$required"]) }}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-6">
        <div class="form-group">
            <?php
            $field_name = 'birth_place';
            $field_lable = __("school::$module_name.$field_name");
            $field_placeholder = $field_lable;
            $required = "required";
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            {{ html()->text($field_name)->placeholder($field_placeholder)->class('form-control')->attributes(["$required", 'aria-label'=>'Image']) }}
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <?php
            $field_name = 'birth_date';
            $field_lable = __("school::$module_name.$field_name");
            $field_placeholder = "DD/MM/YYYY";
            $required = "";
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            <div class="input-group date datetime" id="{{$field_name}}" name="{{$field_name}}" data-target-input="nearest">
                {{ html()->text($field_name)->placeholder($field_placeholder)->class('form-control datetimepicker-input')->attributes(["$required", 'data-target'=>"#$field_name"]) }}
                <div class="input-group-append" data-target="#{{$field_name}}" data-toggle="datetimepicker">
                    <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-6">
        <div class="form-group">
            <?php
            $field_name = 'year_class';
            $field_lable = __("school::$module_name.$field_name");
            $field_placeholder = $field_lable;
            $required = "required";
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            {{ html()->text($field_name)->placeholder($field_placeholder)->class('form-control')->attributes(["$required", 'aria-label'=>'Image','type' => 'number', 'min' => 1900, 'max' => Carbon\Carbon::now()->year()]) }}
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <?php
            $field_name = 'major';
            $field_lable = __("school::$module_name.$field_name");
            $field_placeholder = "-- Pilih --";
            $required = "required";
            $select_options = $options['majors'];
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            {{ html()->select($field_name, $select_options)->placeholder($field_placeholder)->class('form-control')->attributes(["$required"]) }}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-6">
        <div class="form-group">
            <?php
            $field_name = 'height';
            $field_lable = __("school::$module_name.$field_name");
            $field_placeholder = $field_lable." (Cm)";
            $required = "required";
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            {{ html()->text($field_name)->placeholder($field_placeholder)->class('form-control')->attributes(["$required", 'aria-label'=>'Image']) }}
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <?php
            $field_name = 'weight';
            $field_lable = __("school::$module_name.$field_name");
            $field_placeholder = $field_lable." (Kg)";
            $required = "required";
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            {{ html()->text($field_name)->placeholder($field_placeholder)->class('form-control')->attributes(["$required", 'aria-label'=>'Image']) }}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="form-group">
            <?php
            $field_name = 'skills';
            $field_lable = __("school::$module_name.$field_name")." (Bisa pilih lebih dari satu)";
            $field_placeholder = $field_lable;
            $select_options = $options['skills'];
            $required = "";
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            {{ html()->multiselect($field_name,$select_options)->name($field_name.'[]')->class('form-control')->attributes(["$required",'multiple' => 'multiple']) }}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="form-group">
            <?php
            $field_name = 'certificate';
            $field_lable = __("school::$module_name.$field_name")." (Bisa pilih lebih dari satu)";
            $field_placeholder = $field_lable;
            $select_options = $options['certificate'];
            $required = "";
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            {{ html()->multiselect($field_name,$select_options)->name($field_name.'[]')->class('form-control')->attributes(["$required",'multiple' => 'multiple']) }}
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
$(function() {
    var date = moment("{{$$module_name_singular->birth_date ?? ''}}", 'YYYY-MM-DD').toDate();
    $('.datetime').datetimepicker({
        format: 'DD/MM/YYYY',
        date: date,
        icons: {
            time: 'far fa-clock',
            date: 'far fa-calendar-alt',
            up: 'fas fa-arrow-up',
            down: 'fas fa-arrow-down',
            previous: 'fas fa-chevron-left',
            next: 'fas fa-chevron-right',
            today: 'far fa-calendar-check',
            clear: 'far fa-trash-alt',
            close: 'fas fa-times'
        }
    });
});

$(document).ready(function() {
        $('#skills').multiselect({
                enableFiltering: true,
            });

        $('#certificate').multiselect({
                enableFiltering: true,
            });
    });

</script>

@endpush
