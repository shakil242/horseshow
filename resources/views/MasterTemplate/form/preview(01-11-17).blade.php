
<div class="form-group form-title-holder">
  <div class="col-sm-offset-2 col-sm-8">
    <div class="{{ $TD_variables['TD_title_font_allignment']}} form-title" style="{{$TD_variables['TD_title_font_size']}}{{$TD_variables['TD_title_font_color']}}">{{$FormTemplate->name }}</div>
  </div>
</div>
<div class="form-fields-holder" style="{{$TD_variables['TD_field_font_size']}}{{$TD_variables['TD_field_font_color']}}">
<!-- check duplication field if exist -->
<?php 
//dd($answer_fields);
if (isset($answer_fields)) {
   $duplicate = array_where($answer_fields, function ($value, $key) {
        return isset($value['duplication_batch']);
    });
 }
?>
<!-- Start: Field Loop $pre_fields -->
@if(is_array($pre_fields))
    @foreach ($pre_fields as $key => $field)

    <!-- Edit saved form -->
    <?php 
    $disk = getStorageDisk();
    $answer = "";
    $search_location = "";
    $place_id = "";
    $latitude = "";
    $longitude = "";
    $address = "";
        if (isset($answer_fields)) {
           $indexer = array_search($field['unique_id'], array_column($answer_fields, 'form_field_id'));
            if ($answer_fields[$indexer]['form_field_type'] == OPTION_IMAGE ) {
               if(isset($answer_fields[$indexer]["upload_files"])){ 
                    $answer = $answer_fields[$indexer]['upload_files'];
                  }
            }elseif($answer_fields[$indexer]['form_field_type'] == OPTION_ATTACHMENT || $answer_fields[$indexer]['form_field_type'] == OPTION_VIDEO){
               if(isset($answer_fields[$indexer]["upload_filess"])){ 
                    $answer = $answer_fields[$indexer]['upload_filess'];
                  }
            }else{
              if(isset($answer_fields[$indexer]['answer'])){ 
                  $answer = $answer_fields[$indexer]['answer'];
                  if ($answer_fields[$indexer]['form_field_type'] == OPTION_ADDRESS_MAP) {
                      $search_location = $answer_fields[$indexer]['search_location'];
                      $place_id = $answer_fields[$indexer]['place_id'];
                      $latitude = $answer_fields[$indexer]['latitude'];
                      $longitude = $answer_fields[$indexer]['longitude'];
                      $address = $answer_fields[$indexer]['address'];
                  }
              }

            }
          }else{
            $answer = "";
         } 
    ?>
    
        <div class="form-group">
        <?php ($field['form_field_required']== 1 ? $required = "Required" : $required = "" ) ?>
          <label class="col-sm-2 control-label">{{$field["form_name"]}} <?php if($required == "Required"){echo "<span>*</span>";} ?></label>
          <div class="col-sm-8">
            <div class="required-fields-hidden">
              @if(isset($field['form_field_duplicate_times']))
              <input type="hidden" class="duplicate-permission-time" value="{{$field['form_field_duplicate_times']}}">
              <input type="hidden" class="duplicate-permission-fieldId" value="{{$field['unique_id']}}">
            @endif
              <input name="fields[{{$field['unique_id']}}][form_name]" type="hidden" value="{{$field['form_name']}}" />
              <input name="fields[{{$field['unique_id']}}][form_field_type]" class="form-field-type" type="hidden" value="{{$field['form_field_type']}}" />
              <input name="fields[{{$field['unique_id']}}][form_field_id]" type="hidden" value="{{$field['unique_id']}}" />
            </div>
            <?php if($field['form_field_type'] == OPTION_TEXT){ ?>
              <input name="fields[{{$field['unique_id']}}][answer]" value="{{ $answer }}" type="text" class="form-control" {{$required}} />
            <?php } ?>
            <?php if($field['form_field_type'] == OPTION_TEXTAREA){ ?>
                <textarea name="fields[{{$field['unique_id']}}][answer]" class="form-control" rows="5" {{$required}}>{{ $answer }}</textarea>
            <?php } ?>
            <?php if($field['form_field_type'] == OPTION_DATE_PICKER){ ?>
                <input name="fields[{{$field['unique_id']}}][answer]" type='text' class="form-control datetimepickerDate" placeholder="Select Date" value="{{ $answer }}" {{$required}} />
            <?php } ?>
            <?php if($field['form_field_type'] == OPTION_TIME_PICKER){ ?>
                <input name="fields[{{$field['unique_id']}}][answer]" type='text' class="form-control datetimepickerTime" placeholder="Select Time" value="{{ $answer }}" {{$required}} />
            <?php } ?>
            <?php if($field['form_field_type'] == OPTION_RATINGS){ ?>
              <input name="fields[{{$field['unique_id']}}][answer]" id="input-7-xs" class="rating rating-loading" value="{{ $answer }}" data-max="5" data-step="0.1" data-size="xs">
            <?php } ?>
            <?php if($field['form_field_type'] == OPTION_EMAIL){ ?>
              <input name="fields[{{$field['unique_id']}}][answer]" type="email" class="form-control" placeholder=" Email"  value="{{ $answer }}" data-error="Please enter a valid email address." {{$required}}>
            <?php } ?>
            <?php if($field['form_field_type'] == OPTION_MONETERY){ ?>
              <div class="input-group col-sm-5"> 
                <span class="input-group-addon">$</span>
                <input name="fields[{{$field['unique_id']}}][answer]" type="number" class="form-control currency NumaricRistriction" placeholder=" Enter numaric value" value="{{ $answer }}" step="any" data-error="Please enter monetry value" {{$required}}/>
              </div>
            <?php } ?>
            <?php if($field['form_field_type'] == OPTION_SIGNATURE){ ?>
              <div class="input-group col-sm-10">
                <div class="sigPad_{{$field['unique_id']}}">
                  <!-- <div class="col-sm-6"><input type="text" id="name" placeholder="Enter Name Or Click Draw It" class="form-control nameSign"></div>
                   --><div class="col-sm-6">
                    <ul class="sigNav">
                      <!-- <li class="typeIt"><a href="#type-it">Type It</a></li>
                       --><li class="drawIt"><a href="#draw-it" class="drawitS">Draw It</a></li>
                      <li class="clearButton"><a href="#clear">Clear</a></li>
                    </ul>
                    <div class="sig sigWrapper">
                      <div class="typed"></div>
                      <canvas class="pad" width="280" height="70"></canvas>
                      <input type="hidden" name="fields[{{$field['unique_id']}}][answer]" value="{{ $answer }}" class="output canvasanswer_{{$field['unique_id']}}">

                    </div>
                  </div>
                </div>
              </div>
              @if($answer)
                <script>
                  $(document).ready(function() {
                    //$(".sigPad_{{$field['unique_id']}}").signaturePad();
                     var sig = $(".canvasanswer_{{$field['unique_id']}}").val();
                     $(".sigPad_{{$field['unique_id']}}").signaturePad({displayOnly:true}).regenerate(sig);
                  });
                </script>
              @else
                  <script>
                      $(document).ready(function() {
                          var signaturePad = $(".sigPad_{{$field['unique_id']}}").signaturePad({drawOnly:true, lineTop:200,onDrawEnd:
                              function () {
                                 $(".signatureImg_{{$field['unique_id']}}").val(this.getSignatureImage());
                              }
                          });

                      });
                  </script>
              @endif
              <input type="hidden" name="fields[{{$field['unique_id']}}][signatureImg]" class="output signatureImg_{{$field['unique_id']}}">

            <?php } ?>
            <?php if($field['form_field_type'] == OPTION_NUMARIC){ ?>
              <input name="fields[{{$field['unique_id']}}][answer]" type="number" class="form-control NumaricRistriction" placeholder=" Enter numaric value" value="{{ $answer }}" step="any" data-error="Please enter a valid numeric value only" {{$required}}/>
            <?php } ?>
             <?php if($field['form_field_type'] == OPTION_ADDRESS_MAP){ ?>
              <div class="col-sm-12">
              
                  <div class="input-group">
                        <input name="fields[{{$field['unique_id']}}][answer]" type="text" class="form-control location" id='search-input' placeholder="Add location" value="{{ $answer }}" {{$required}}/>
                        <input name="fields[{{$field['unique_id']}}][search_location]" value="{{$search_location}}" type="hidden" class="search_location"/>
                        <input name="fields[{{$field['unique_id']}}][latitude]" value="{{ $latitude }}" type="hidden" class="latitude"/>
                        <input name="fields[{{$field['unique_id']}}][longitude]" value="{{ $longitude }}" type="hidden" class="longitude"/>
                        <input name="fields[{{$field['unique_id']}}][address]" value="{{ $address }}" type="hidden" class="address"/>
                        <input name="fields[{{$field['unique_id']}}][place_id]" value="{{ $place_id }}" type="hidden" class="place_id"/>

                        <span class="input-group-addon text-red" onclick="js:initialize();" data-placement="left" data-toggle="tooltip" data-title="Load Map">
                            <i class="fa fa-fw fa-lg fa-map-marker"></i>
                        </span>
                        
                </div>
                  <div id="map_wrapper">
                    <!--<input id="pac-input" class="controls" type="text" placeholder="Search Box">-->
                    <div id="map_canvas" class="mapping"></div>
                  </div>
            </div>
            <script src="{{ asset('/js/google-map-script-form.js') }}"></script>
    
            <?php } ?>
            <?php if( $field['form_field_type'] == OPTION_RADIOBUTTON || 
                $field['form_field_type'] == OPTION_CHECKBOX ||
                $field['form_field_type'] == OPTION_DROPDOWN ||
                $field['form_field_type'] == OPTION_AUTO_POPULATE ||
                $field['form_field_type'] == OPTION_LABEL ||
                $field['form_field_type'] == OPTION_HYPERLINK 
              ){ ?>
                @if( isset($field['form_field_options'] ))
                <div style="{{$TD_variables['TD_options_font_size']}}{{$TD_variables['TD_options_font_color']}}">
                    
                    <?php if (isset($indexer)) {
                          echo AddOptionsFrontend($field['form_field_options'], $field, $answer_fields[$indexer]);
                        }else{
                          echo AddOptionsFrontend($field['form_field_options'], $field );
                        }

                    ?>
                </div>
              @endif
            <?php } ?>
            <!-- Video -->
             <?php if($field['form_field_type'] == OPTION_VIDEO ){ ?>
               @if( isset($field['form_field_options'][1]))
                @if( $field['form_field_options'][1]['upload_files'] != "")
                  <div class="form-group">
                    <div style="border: 1px solid;float: left;margin: 3px;padding: 5px;">
                        <video width="520" height="340" controls>
                        <?php //$URLs = PATH_UPLOAD_FORMS."master_temp_$FormTemplate->template_id/form_$formid/";?> 
                        <source src="{{ getImageS3($field['form_field_options'][1]['upload_files']) }}" type="video/mp4">
                        Your browser does not support the video tag.
                        </video>
                    </div>
                  </div>
                @endif
               @endif
               @if( isset($field['form_field_ischangeable'] ))
                @if( $field['form_field_ischangeable'] == 1)
                  <div style="{{$TD_variables['TD_options_font_size']}}{{$TD_variables['TD_options_font_color']}}">
                    <div class="form-group uploadimage">
                        <input type="file" name="fields[{{$field['unique_id']}}][upload_files]" class="file video-uploader-browser" data-overwrite-initial="false" {{$required}}>
                      @if(isset($answer) && $answer != "")
                        <div class="file-preview-frame">
                        <div style="border: 1px solid;float: left;margin: 3px;padding: 5px;">
                          <video width="520" height="340" controls>
                          <source src="{{$disk->url($answer)}}" type="video/mp4">
                          Your browser does not support the video tag.
                          </video>
                          <button class="kv-file-remove-video btn btn-xs pull-right" type="button" title="Remove file">
                              <i class="glyphicon glyphicon-trash text-danger"></i>
                            </button>
                        </div>
                        <input type="hidden" name="fields[{{$field['unique_id']}}][upload_files]" value="{{$answer}}">
                        
                        </div>
                      @endif
                    </div>
                 </div>
                @endif
              @endif
            <?php } ?>
            <!-- Image -->
             <?php if($field['form_field_type'] == OPTION_IMAGE){ ?>
               @if( isset($field['form_field_options'] ))
                @if( $field['form_field_options'][1]['upload_files'] != "")
                  <div class="form-group">
                    <div style="border: 1px solid;float: left;margin: 3px;padding: 5px;">
                      <a href="#" data-toggle="modal" data-target="#myModal{{$field['unique_id']}}">
                        <?php //$URLs = PATH_UPLOAD_FORMS."master_temp_$FormTemplate->template_id/form_$formid/";?> 
                        <img src="{{ getImageS3($field['form_field_options'][1]['upload_files']) }}" height="240" />      
                      </a>
                    </div>
                    <!-- Popup -->
                    <div id="myModal{{$field['unique_id']}}" class="modal fade adminModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-body">
                            <?php //$URLs = PATH_UPLOAD_FORMS."master_temp_$FormTemplate->template_id/form_$formid/";?> 
                            <img src="{{ getImageS3($field['form_field_options'][1]['upload_files']) }}" />      
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                @endif
               @endif
               @if( isset($field['form_field_ischangeable'] ))
                @if( $field['form_field_ischangeable'] == 1)
                  <div style="{{$TD_variables['TD_options_font_size']}}{{$TD_variables['TD_options_font_color']}}">
                    <div class="form-group uploadimage">
                      @if($answer == "")
                      <input type="file" multiple class="file image-uploader-browser" data-overwrite-initial="false" {{$required}}>
                      @else
                      <input id="input-{{$field['unique_id']}}" type="file" multiple {{$required}}>
                        <?php $previousImages = getPreviousArray($answer); ?>
                         <script>
                          $(document).on('ready', function() {
                              $("#input-{{$field['unique_id']}}").fileinput({
                                  initialPreview: <?php echo $previousImages ?>,
                                  initialPreviewAsData: true,
                                  overwriteInitial: false,
                                  uploadUrl: '#', // you must set a valid URL here else you will get an error
                                  allowedFileExtensions: ['jpg','svg', 'png', 'gif'],
                                  overwriteInitial: false,
                                  maxFileCount: 10,
                                  minFileCount: 1,
                                  maxFileSize: 900,
                                  //allowedFileTypes: ['image', 'video', 'flash'],
                                  slugCallback: function (filename) {
                                      return filename.replace('(', '_').replace(']', '_');
                                  }
                              });
                              $(".kv-file-zoom").attr("disabled",false)
                          });
                          </script>
                          @foreach($answer as $exitstingFile)
                          <input type="hidden" name="fields[{{$field['unique_id']}}][upload_files][]" value="{{$exitstingFile}}" />      
                          @endforeach
                      @endif
                      <input type="hidden" class="fileuploaderid" value="{{$field['unique_id']}}">
                      <div class="fileholders">
                      </div>
                    </div>
                 </div>
                @endif
              @endif
            <?php } ?>
             <!-- Attachment -->
             <?php if($field['form_field_type'] == OPTION_ATTACHMENT){ ?>
               @if( isset($field['form_field_options'] ))
                @if( $field['form_field_options'][1]['upload_files'] != "")
                  <div class="form-group">
                    <div style="border: 1px solid;float: left;margin: 3px;padding: 10px;">
                        <?php //$URLs = PATH_UPLOAD_FORMS."master_temp_$FormTemplate->template_id/form_$formid/";?> 
                        <a href="{{ getImageS3($field['form_field_options'][1]['upload_files'])}}" target="_blank">Click here to preview file</a>
                    </div>
                  </div>
                @endif
               @endif
               @if( isset($field['form_field_ischangeable'] ))
                @if( $field['form_field_ischangeable'] == 1)
                  <div style="{{$TD_variables['TD_options_font_size']}}{{$TD_variables['TD_options_font_color']}}">
                    <div class="form-group uploadimage fileupload">
                      <input type="file" multiple name="fields[{{$field['unique_id']}}][upload_files][]" {{$required}}>
                      <input type="hidden" class="fileuploaderid" value="{{$field['unique_id']}}">
                      <div class="fileholders">
                        @if(isset($answer) && $answer!='')
                          @foreach($answer as $Aindex => $file)
                          <div class="file-preview-frame">
                            <div class="col-sm-4"style="border: 1px solid;float: left;margin: 3px;padding: 10px;">
                              <a href="{{ $disk->url($file['path']) }}" target="_blank">{{ $file['name'] }}</a>
                            
                            <input type="hidden" name="fields[{{$field['unique_id']}}][upload_filess][{{$Aindex}}][path]" value="{{$file['path']}}" />      
                            <input type="hidden" name="fields[{{$field['unique_id']}}][upload_filess][{{$Aindex}}][name]" value="{{$file['name']}}" />      
                            <button class="kv-file-remove-attachment btn btn-xs pull-right" type="button" title="Remove file">
                              <i class="glyphicon glyphicon-trash text-danger"></i>
                            </button>
                            </div>
                          </div>
                          
                          @endforeach
                        @endif
                      </div>
                    </div>
                 </div>
                @endif
              @endif
            <?php } ?>

            
          </div>
        </div>

    @endforeach
@endif
<!-- Add duplicate fileds -->
<div class="duplicate-fields-add">
  @if(isset($duplicate))
    @foreach ($duplicate as $key => $field)
      @include('MasterTemplate.form.duplication')
    @endforeach
  @endif
</div>
<!-- End: Field Loop $pre_fields -->
<div class="form-group"><div style="height:15px; clear:both;"></div></div>
</div>

