<div class="row">
    <div class="col">
        <div class="form-group">
            <?php
            $field_name = 'mkdum_name';
            $field_lable = __("mkstarter::$module_name.$field_name");
            $field_placeholder = $field_lable;
            $required = "required";
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            {{ html()->text($field_name)->placeholder($field_placeholder)->class('form-control text-dark')->attributes(["$required"]) }}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-5">
        <div class="form-group">
            <?php
            $field_name = 'mkdum_type';
            $field_data_id = 'mkdum_type';
            $field_lable = __("mkstarter::$module_name.$field_name");
            $field_placeholder = __("Select an option");
            $required = "required";
            $select_options = $mkdum_types;
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            {{ html()->select($field_data_id, $select_options)->placeholder($field_placeholder)->class('form-control select2')->attributes(["$required"]) }}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-6">
        <div class="form-group">
            <?php
            $field_name = 'mkdum_phone';
            $field_lable = __("mkstarter::$module_name.$field_name");
            $field_placeholder = $field_lable;
            $required = "required";
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            {{ html()->text($field_name)->placeholder($field_placeholder)->class('form-control text-dark')->attributes(["$required", 'aria-label'=>'Image']) }}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="form-group">
            <?php
            $field_name = 'mkdum_address';
            $field_lable = __("mkstarter::$module_name.$field_name");
            $field_placeholder = $field_lable;
            $required = "required";
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            {{ html()->textarea($field_name)->rows(5)->placeholder($field_placeholder)->class('form-control text-dark')->attributes(["$required"]) }}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="form-group">
            <?php
            $field_name = 'mkdum_bank_name';
            $field_data_id = 'mkdum_bank_name';
            $field_lable = __("mkstarter::$module_name.$field_name");
            $field_placeholder = __("Select an option");
            $required = "required";
            $select_options = $banks;
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            {{ html()->select($field_data_id, $select_options)->placeholder($field_placeholder)->class('form-control select2')->attributes(["$required"]) }}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-8">
        <div class="form-group">
            <?php
            $field_name = 'mkdum_bank_account';
            $field_lable = __("mkstarter::$module_name.$field_name");
            $field_placeholder = $field_lable;
            $required = "required";
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            {{ html()->text($field_name)->placeholder($field_placeholder)->class('form-control text-dark')->attributes(["$required", 'aria-label'=>'Image']) }}
        </div>
    </div>
</div>

<hr>

<div class="row">
    <div class="col-12">
        <div class="form-group">
            <?php
            $field_name = 'mkdum_email';
            $field_lable = __("mkstarter::$module_name.$field_name");
            $field_placeholder = $field_lable;
            $required = "required";
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            {{ html()->email($field_name)->placeholder($field_placeholder)->class('form-control text-dark')->attributes(["$required", 'aria-label'=>'Image']) }}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-6">
        <div class="form-group">
            <?php
            $field_name = 'password';
            $field_lable = __("mkstarter::$module_name.$field_name");
            $field_placeholder = $field_lable;
            $required = "required";
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            {{ html()->password($field_name)->placeholder($field_placeholder)->class('form-control text-dark')->attributes(["$required", 'aria-label'=>'Image']) }}
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <?php
            $field_name = "konfirmasi password";
            $field_lable = "konfirmasi password";
            $field_placeholder = $field_lable;
            $required = "required";
            ?>
            {{ html()->label($field_lable, $field_name) }} {!! fielf_required($required) !!}
            {{ html()->password($field_name)->placeholder($field_placeholder)->class('form-control text-dark')->attributes(["$required", 'aria-label'=>'Image']) }}
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
    $('.datetime').datetimepicker({
        format: 'YYYY-MM-DD HH:mm:ss',
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
</script>

<script type="text/javascript">


</script>

@endpush
