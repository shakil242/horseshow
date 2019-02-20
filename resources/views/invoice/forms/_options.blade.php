 <?php if($field['form_field_type'] == OPTION_DROPDOWN || 
         $field['form_field_type'] == OPTION_RADIOBUTTON || 
         $field['form_field_type'] == OPTION_CHECKBOX || 
         $field['form_field_type'] == OPTION_AUTO_POPULATE){ ?>
 
<div class="Optionslidedown" style="display:block"> 
    <div class="row">
        <div class="col-sm-9">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label>Option </label>
                <input type="text" name="fields[{{$index}}][form_field_options][{{$indexoptions}}][opt_name]" value="{{$option['opt_name']}}" class="form-control" placeholder="Enter Option" required />
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <br />
                <label><input name="fields[{{$index}}][form_field_options][{{$indexoptions}}][opt_default]" data-attr="field{{$index}}" value="1" {{ (isset($option['opt_default']) && $option['opt_default']== 1) ? "checked":"" }} type="radio" />Default </label>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label>Weightage PT(S)</label>
                <input type="text" name="fields[{{$index}}][form_field_options][{{$indexoptions}}][opt_weightage]" value="{{$option['opt_weightage']}}" class="form-control" placeholder="Add Weight" />
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-3">
          <br />
          <input type="button" class="btn btn-sm btn-defualt delete-options-row" value="Delete" />
        </div>
      </div>
</div>
<?php } elseif($field['form_field_type'] == OPTION_LABEL){ ?>

<div class="row">
<div class="col-sm-9">
  <div class="row">
    <div class="col-sm-12">
      <div class="form-group">
        <div class="col-sm-3"><label>Enter Text :</label></div><div class="col-sm-8"> <input type="text" name="fields[{{$index}}][form_field_options][{{$indexoptions}}][opt_label]" class="form-control" value="{{$option['opt_label']}}" required /></div>
        </div>
      </div>
    </div>
    </div>
    </div>

<?php }elseif ($field['form_field_type'] == OPTION_HYPERLINK) { ?>

<div class="row">
<div class="col-sm-9">
  <div class="row">
    <div class="col-sm-12">
      <div class="form-group">
        <div class="col-sm-3"><label>Hyperlink :</label></div><div class="col-sm-8"> <input type="text" name="fields[{{$index}}][form_field_options][{{$indexoptions}}][opt_hyperlink]" class="form-control" value="{{$option['opt_hyperlink']}}" /></div>
        </div>
      </div>
    </div>
    </div>
    </div>

<?php }elseif ($field['form_field_type'] == OPTION_IMAGE) { ?>
      <div class="row">
        <div class="col-sm-12">
          <div class="form-group">
            <div class="col-sm-3"><label>Upload Image :</label></div>
            <?php if(isset($option['upload_files']) && $option['upload_files'] != "" ){
                  $uploadfile_val = $option['upload_files'];
              }else{
                  $uploadfile_val = "";
              }
            ?>
            <input type="hidden" name="fields[{{$index}}][form_field_options][{{$indexoptions}}][old_upload_files]" value="{{$uploadfile_val}}">
            <div class="col-sm-4"><input type="file" name="fields[{{$index}}][form_field_options][{{$indexoptions}}][upload_files]" class="validate-upload-file" accept="image/*"> </div>
              <div class="col-sm-4">
                @if(isset($option['upload_files']) && $option['upload_files'] != "")
               <div style="border: 1px solid;float: left;margin: 3px;padding: 5px;">
                  <?php $URLs = PATH_UPLOAD_FORMS."master_temp_$masterid/form_$form->id/";?>
                  <img src="{{ URL::asset($URLs.$option['upload_files']) }}" width="150" height="120" />      
                </div>
                @endif
              </div>
            </div>
          </div>
        </div>
<?php }elseif ($field['form_field_type'] == OPTION_ATTACHMENT) { ?>
      <div class="row">
        <div class="col-sm-12">
          <div class="form-group">
            <div class="col-sm-3"><label>Upload Attachment :</label></div>
            <?php if(isset($option['upload_files']) && $option['upload_files'] != ""){
                  $uploadfile_val = $option['upload_files'];
              }else{
                  $uploadfile_val = "";
              }
            ?>
            <input type="hidden" name="fields[{{$index}}][form_field_options][{{$indexoptions}}][old_upload_files]" value="{{$uploadfile_val}}">
            <div class="col-sm-4"><input type="file" name="fields[{{$index}}][form_field_options][{{$indexoptions}}][upload_files]" class="validate-upload-file"> </div>
              <div class="col-sm-4">
                @if(isset($option['upload_files']) && $option['upload_files'] != "")
                <div style="border: 1px solid;float: left;margin: 3px;padding: 5px;">
                  <?php $URLs = PATH_UPLOAD_FORMS."master_temp_$masterid/form_$form->id/";?>
                  <input type="text" value="{{ URL::asset($URLs.$option['upload_files']) }}" />      
                </div>
                @endif
              </div>
          </div>
        </div>
      </div>
<?php }elseif ($field['form_field_type'] == OPTION_VIDEO) { ?>
<div class="row">
        <div class="col-sm-12">
          <div class="form-group">
            <div class="col-sm-3"><label>Upload Video :</label></div>
            <?php if(isset($option['upload_files']) && $option['upload_files'] != ""){
                  $uploadfile_val = $option['upload_files'];
              }else{
                  $uploadfile_val = "";
              }
            ?>
            <input type="hidden" name="fields[{{$index}}][form_field_options][{{$indexoptions}}][old_upload_files]" value="{{$uploadfile_val}}">
            <div class="col-sm-4"><input type="file" name="fields[{{$index}}][form_field_options][{{$indexoptions}}][upload_files]" class="validate-upload-file" accept="video/*"></div>
              <div class="col-sm-5">
                @if(isset($option['upload_files']) && $option['upload_files'] != "")
                <div style="border: 1px solid;float: left;margin: 3px;padding: 5px;">
                  <video width="320" height="240" controls>
                  <?php $URLs = PATH_UPLOAD_FORMS."master_temp_$masterid/form_$form->id/";?>
                    <source src="{{ URL::asset($URLs.$option['upload_files']) }}" type="video/mp4">
                    Your browser does not support the video tag.
                  </video>
                </div>
                @endif
              </div>
          </div>
        </div>
      </div>

<?php } ?>