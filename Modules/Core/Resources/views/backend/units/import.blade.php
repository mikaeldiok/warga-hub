<!-- Modal -->
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Importer</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        {{ html()->form('POST', route("backend.$module_name.import"))->class('form')->acceptsFiles()->open() }}
            <div class="col-12">
                <div class="form-group">
                    <?php
                    $field_name = "data_file";
                    $field_lable = "data_file";
                    $field_placeholder = $field_lable;
                    $required = "required";
                    ?>
                    {!! Form::label("$field_name", "$field_lable") !!} {!! fielf_required($required) !!}
                    <div class="input-group mb-3">
                        <input type="file" class="form-control-file" name="{{$field_name}}" id="{{$field_name}}" placeholder="{{$field_placeholder}} {{$required}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="form-group">            
                        {{ html()->button($text = "Import", $type = 'submit')->class('btn btn-success') }}
                    </div>
                </div>
            </div>

        {{ html()->form()->close() }}
      </div>
    </div>
  </div>
</div>


