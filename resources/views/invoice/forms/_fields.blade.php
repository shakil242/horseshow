<?php $index = $key+1; ?>
<div class="add-form-field sort-fieldtype" style="display:block">
<div class="row">
<div class="col-sm-10"><i class="glyphicon glyphicon-move" aria-hidden="true"></i> </div>
<br>
  <div class="col-sm-4">
  <input type="hidden" name="fields[{{$index}}][unique_id]" value="{{ $field['unique_id'] }}">
    <div class="form-group">
      <label>Field Name *</label>
      <input name="fields[{{$index}}][form_name]" type="text" class="form-control" value="{{$field['form_name']}}" placeholder="Name of the field" Required/>
    </div>
  </div>
  <div class="col-sm-4">
    <div class="form-group">
      <label>Field Type *</label>
      <select data-select="{{$field['form_field_type']}}" class="option-selector-drp" name="fields[{{$index}}][form_field_type]" Required>
      <option value="">Please select</option>
        <option value="{{ OPTION_DROPDOWN }}" {{ ($field['form_field_type'] == OPTION_DROPDOWN ) ? 'selected':'' }}>Dropdown</option>
        <option value="{{ OPTION_RADIOBUTTON }}" {{ ($field['form_field_type'] == OPTION_RADIOBUTTON ) ? 'selected':'' }}>Radio Button</option>
        <option value="{{ OPTION_CHECKBOX }}" {{ ($field['form_field_type'] == OPTION_CHECKBOX ) ? 'selected':'' }}>Checkbox</option>
        <option value="{{ OPTION_AUTO_POPULATE }}" {{ ($field['form_field_type'] == OPTION_AUTO_POPULATE ) ? 'selected':'' }}>Auto Populate</option>
        <option value="{{ OPTION_TEXT }}" {{ ($field['form_field_type'] == OPTION_TEXT ) ? 'selected':'' }}>Text</option>
        <option value="{{ OPTION_MONETERY }}" {{ ($field['form_field_type'] == OPTION_MONETERY ) ? 'selected':'' }}>Monetary</option>
        <option value="{{ OPTION_NUMARIC }}" {{ ($field['form_field_type'] == OPTION_NUMARIC ) ? 'selected':'' }}>Numeric</option>
        <option value="{{ OPTION_IMAGE }}" {{ ($field['form_field_type'] == OPTION_IMAGE ) ? 'selected':'' }}>Image</option>
        <option value="{{ OPTION_VIDEO }}" {{ ($field['form_field_type'] == OPTION_VIDEO ) ? 'selected':'' }}>Video</option>
        <option value="{{ OPTION_DATE_PICKER }}" {{ ($field['form_field_type'] == OPTION_DATE_PICKER ) ? 'selected':'' }}>Date </option>
        <option value="{{ OPTION_TIME_PICKER }}" {{ ($field['form_field_type'] == OPTION_TIME_PICKER ) ? 'selected':'' }}>Time </option>
        <option value="{{ OPTION_LABEL }}" {{ ($field['form_field_type'] == OPTION_LABEL ) ? 'selected':'' }}>Label</option>
        <option value="{{ OPTION_HYPERLINK }}" {{ ($field['form_field_type'] == OPTION_HYPERLINK ) ? 'selected':'' }}>Hyperlink</option>
        <option value="{{ OPTION_ATTACHMENT }}" {{ ($field['form_field_type'] == OPTION_ATTACHMENT ) ? 'selected':'' }}>Attachment</option>
        <option value="{{ OPTION_EMAIL }}" {{ ($field['form_field_type'] == OPTION_EMAIL ) ? 'selected':'' }}>Email</option>
        <option value="{{ OPTION_RATINGS }}" {{ ($field['form_field_type'] == OPTION_RATINGS ) ? 'selected':'' }}>Rating</option>
        <option value="{{ OPTION_TEXTAREA }}" {{ ($field['form_field_type'] == OPTION_TEXTAREA ) ? 'selected':'' }}>Text Area</option>
        <option value="{{ OPTION_ADDRESS_MAP }}" {{ ($field['form_field_type'] == OPTION_ADDRESS_MAP ) ? 'selected':'' }}>Address Map</option>
      </select>
    </div>
  </div>
  <div class="col-sm-4 add-changeable-opt">
    <div class="form-group">
      <label><input name="fields[{{$index}}][form_field_required]" type="hidden" value="0"/><input class="requried-attr" name="fields[{{$index}}][form_field_required]" type="checkbox" value="1"  {{ $field['form_field_required'] == 1 ? "checked":"" }}/>Required</label>
    </div>
    @if( isset($field['form_field_ischangeable'] ))
      <div class="form-group delete-changeable-field">
          <label><input name="fields[{{$index}}][form_field_ischangeable]" type="hidden" value="0"><input name="fields[{{$index}}][form_field_ischangeable]" type="checkbox" value="1" {{ $field['form_field_ischangeable'] == 1 ? "checked":"" }}/>Changeable</label>
      </div>
    @endif
  </div>
</div>
 <input type="hidden" class="field-placement" value="{{$index}}">
  <?php if($field['form_field_type'] == OPTION_TEXT || 
          $field['form_field_type'] == OPTION_DATE_PICKER || 
          $field['form_field_type'] == OPTION_TIME_PICKER || 
          $field['form_field_type'] == OPTION_MONETERY || 
          $field['form_field_type'] == OPTION_NUMARIC || 
          $field['form_field_type'] == OPTION_RATINGS ||
          $field['form_field_type'] == OPTION_TEXTAREA || 
          $field['form_field_type'] == OPTION_EMAIL ){ ?>
        <div class="options-choices add-fields-options">
          <div class="adder">
          </div>
          <input class="btn btn-sm btn-warning btn-add-options" value="Add Options" type="button" style="display:none">
        </div>
   <?php }else{ ?>
   <div class="options-choices add-fields-options" style="display:block">
    <div class="adder">

      @if( isset($field['form_field_options'] ))
      <?php $indexoptions = 1; ?>
        @foreach ($field['form_field_options'] as $okey => $option)
          @include('admin.mastertemplates.forms._options')
          <?php $indexoptions = $indexoptions+1; ?>
        @endforeach
      @endif
    </div>
    <?php if($field['form_field_type'] == OPTION_LABEL ||
              $field['form_field_type'] == OPTION_HYPERLINK ||
              $field['form_field_type'] == OPTION_IMAGE || 
              $field['form_field_type'] == OPTION_VIDEO || 
              $field['form_field_type'] == OPTION_ATTACHMENT ){ 
      $display_option = "display:none";
     }else{
      $display_option = "display:block";
      } ?>
    <input class="btn btn-sm btn-warning btn-add-options" value="Add Options" type="button" style="{{$display_option}}">
  </div>
   <?php } ?>
   
<div class="col-4">
    <br />
    <input type="button" class="btn btn-sm btn-defualt deleteRow" value="Delete" />
</div>
</div>