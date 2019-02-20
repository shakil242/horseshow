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
        <option value="{{ DIVIDER_PANEL }}" {{ ($field['form_field_type'] == DIVIDER_PANEL ) ? 'selected':'' }}>Divider Panel</option>
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
          <option value="{{ OPTION_CALCULATE_TOTAL }}" {{ ($field['form_field_type'] == OPTION_CALCULATE_TOTAL ) ? 'selected':'' }}>Calculate Total</option>
          <option value="{{ OPTION_STATE_TAX }}" {{ ($field['form_field_type'] == OPTION_STATE_TAX ) ? 'selected':'' }}>State Tax</option>
          <option value="{{ OPTION_FEDERAL_TAX }}" {{ ($field['form_field_type'] == OPTION_FEDERAL_TAX ) ? 'selected':'' }}>Federal Tax</option>

          <option value="{{ OPTION_BREEDS_AUTO_POPULATE }}" {{ ($field['form_field_type'] == OPTION_BREEDS_AUTO_POPULATE ) ? 'selected':'' }}>Breeds Auto Populate</option>
          <option value="{{ OPTION_BREEDS_STATUS_AUTO_POPULATE }}" {{ ($field['form_field_type'] == OPTION_BREEDS_STATUS_AUTO_POPULATE ) ? 'selected':'' }}>Breeds Rider Auto Populate</option>
          <option value="{{ OPTION_HORSE_AGE_AUTO_POPULATE }}" {{ ($field['form_field_type'] == OPTION_HORSE_AGE_AUTO_POPULATE ) ? 'selected':'' }}>Horse Age Auto Populate</option>
          <option value="{{ OPTION_RIDER_AGE_AUTO_POPULATE }}" {{ ($field['form_field_type'] == OPTION_RIDER_AGE_AUTO_POPULATE ) ? 'selected':'' }}>Rider Age Auto Populate</option>

          <option value="{{ OPTION_SIGNATURE }}" {{ ($field['form_field_type'] == OPTION_SIGNATURE ) ? 'selected':'' }}>Signature</option>
      </select>
    </div>
  </div>
  <div class="col-sm-4 add-changeable-opt">
    <div class="form-group">
      <label><input name="fields[{{$index}}][form_field_required]" type="hidden" value="0"/><input class="requried-attr" name="fields[{{$index}}][form_field_required]" type="checkbox" value="1"  {{ $field['form_field_required'] == 1 ? "checked":"" }}/>Required</label>
    </div>
     <div class="form-group">
      <label><input name="fields[{{$index}}][form_field_private]" type="hidden" value="0"/><input class="private-attr" name="fields[{{$index}}][form_field_private]" type="checkbox" value="1"  {{ ( isset($field['form_field_private']) && $field['form_field_private'] == 1) ? "checked":"" }}/>Private</label>
    </div>

    @if( isset($field['form_field_ischangeable'] ))
      <div class="form-group delete-changeable-field">
          <label><input name="fields[{{$index}}][form_field_ischangeable]" type="hidden" value="0"><input name="fields[{{$index}}][form_field_ischangeable]" type="checkbox" value="1" {{ $field['form_field_ischangeable'] == 1 ? "checked":"" }}/>Changeable</label>
      </div>
    @endif
     
      <?php if(isset($field['form_field_duplicate_times'])){
        $allowedDuplicate = $field['form_field_duplicate_times'];
      }else{
        $allowedDuplicate = "";
      }
      ?>
        <div class="duplicate_times">
          <?php echo CreatePermissionsDrp("fields[$index][form_field_duplicate_times]",$allowedDuplicate,1, 20,'Unlimited') ?>
        </div>
      

  </div>
</div>
 <input type="hidden" class="field-placement" value="{{$index}}">
  <?php if($field['form_field_type'] == OPTION_TEXT || 
          $field['form_field_type'] == DIVIDER_PANEL || 
          $field['form_field_type'] == OPTION_DATE_PICKER || 
          $field['form_field_type'] == OPTION_TIME_PICKER || 
          $field['form_field_type'] == OPTION_MONETERY || 
          $field['form_field_type'] == OPTION_NUMARIC || 
          $field['form_field_type'] == OPTION_RATINGS ||
          $field['form_field_type'] == OPTION_STATE_TAX ||
          $field['form_field_type'] == OPTION_FEDERAL_TAX ||
          $field['form_field_type'] == OPTION_TEXTAREA || 
          $field['form_field_type'] == OPTION_SIGNATURE || 
          $field['form_field_type'] == OPTION_EMAIL ){ ?>
        <div class="options-choices add-fields-options">
          <div class="adder">
          </div>
          <input class="btn btn-sm btn-warning btn-add-options" value="Add Options" type="button" style="display:none">
          <div class="file-excel-uploader" style="display:none">
            <input class="btn btn-sm btn-success btn-upload-excel" type="button" value="upload excel"><small><a href="{{ asset('uploads/excel/option-sample.xlsx') }}">View</a> sample file for excel file formate. </small>
            <input class="excel-upload-file" name="excel_upload_file" type="file" style="display:none" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
          </div>
        </div>
   <?php }elseif($field['form_field_type'] == OPTION_CALCULATE_TOTAL){ ?>
   <div class="options-choices add-fields-options" style="display:block">
    <div class="adder">
          @if( isset($field['form_field_options'] ))
            @foreach ($field['form_field_options'] as $calutionNo => $option)
              @include('admin.mastertemplates.forms._addition')
            @endforeach
          @else
              @include('admin.mastertemplates.forms._addition')
          @endif
      </div>
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
              $field['form_field_type'] == OPTION_ADDRESS_MAP || 
              $field['form_field_type'] == OPTION_ATTACHMENT ){ 
      $display_option = "display:none";
     }else{
      $display_option = "display:block";
      } ?>
    <input class="btn btn-sm btn-warning btn-add-options" value="Add Options" type="button" style="{{$display_option}}">
    <div class="file-excel-uploader" style="{{$display_option}}">
      <input class="btn btn-sm btn-success btn-upload-excel" type="button" value="upload excel"> <small><a href="{{ asset('uploads/excel/option-sample.xlsx') }}">View</a> sample file for excel file formate. </small>
      <input class="excel-upload-file" name="excel_upload_file" type="file" style="display:none" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
    </div>
  </div>
   <?php } ?>
   
<div class="col-4">
    <br />
    <input type="button" class="btn btn-sm btn-defualt deleteRow" value="Delete" />
</div>
</div>