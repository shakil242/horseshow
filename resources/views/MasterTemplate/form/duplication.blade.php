<?php $old_Divider_unique = 1; $old_duplicate_batch = 0; $new_duplicate = 0;?>
@foreach ($duplicate as $key => $field)
     <!-- if Title the DF_Title -->
    @if(isset($field['DF_title']))
     @if($old_Divider_unique != 1 )
          <?php $old_duplicate_batch = $field['duplication_batch']; ?>
            </div>
          </div>
          </div>
    @endif
<div class="form-group form-title-holder">
  <div class="col-sm-12">
    <div class="{{ $TD_variables['TD_title_font_allignment']}} form-title" style="{{$TD_variables['TD_title_font_size']}}{{$TD_variables['TD_title_font_color']}}">{{$field['DF_title']}}</div>
      <input name="fields[duplication_{{$field['duplication_batch']}}][DF_title]" type="hidden" value="{{$field['DF_title']}}" />
      <input name="fields[duplication_{{$field['duplication_batch']}}][duplication_batch]" type="hidden" value="{{$field['duplication_batch']}}" />
  </div>
</div>
<div class="form-fields-holder" style="{{$TD_variables['TD_field_font_size']}}{{$TD_variables['TD_field_font_color']}}">
<div class="times-duplicated"></div>
  <!-- Accordion START -->

    @else
    <?php 
    $disk = getStorageDisk();
    $answer = "";
    $required = "";
     $search_location = "";
        $place_id = "";
        $latitude = "";
        $longitude = "";
        $address = "";

  if (isset($field['duplicated_from'])) {
    $indexer = array_search($field['duplicated_from'], array_column($pre_fields, 'unique_id'));
   $question = $pre_fields[$indexer];
  }
   if ($field['form_field_type'] == OPTION_IMAGE ) {
       if(isset($field["upload_files"])){ 
            $answer = $field["upload_files"];
          }
    }elseif($field['form_field_type'] == OPTION_ATTACHMENT || $field['form_field_type'] == OPTION_VIDEO){
       if(isset($field["upload_filess"])){ 
            $answer = $field['upload_filess'];
          }
    }elseif($field['form_field_type'] == OPTION_ADDRESS_MAP) {
            $answer = $field['answer'];
            $search_location = $field['search_location'];
            $place_id = $field['place_id'];
            $latitude = $field['latitude'];
            $longitude = $field['longitude'];
            $address = $field['address'];
      }else{
      if(isset($field['answer'])){ 
          $answer = $field['answer'];
      }
    
    }
   
    ?>
    @if($field['form_field_type'] == DIVIDER_PANEL)
      
      @if($old_Divider_unique != 1 )
        @if($old_Divider_unique != $field['unique_id'] && ($old_duplicate_batch == $new_duplicate))
          </div>
          </div>
          </div>
        @endif
       @endif
      <?php $old_Divider_unique=$field['unique_id']; $new_duplicate = $old_duplicate_batch;  ?>
        <div class="panel slide-holder">
          <h5 class="card-header">
              <a class="d-block title panel-heading collapsed" data-toggle="collapse" href="#{{$field['unique_id']}}" data-class="{{$field['unique_id']}}" aria-expanded="true" aria-controls="collapse-example">
                  {{$field["DF_Divider_Name"]}}
              </a>
          </h5>
          <div id="{{$field['unique_id']}}" class="form-fields-in-panel panel-collapse collapse" aria-labelledby="heading-example">
              <div class="card-body">
            <input type="hidden" name="fields[{{$field['unique_id']}}][form_field_type]" value="100">
            <input type="hidden" name="fields[{{$field['unique_id']}}][DF_Divider_Name]" value="{{$field['DF_Divider_Name']}}">
            <input type="hidden" name="fields[{{$field['unique_id']}}][unique_id]" value="{{$field['unique_id']}}">
            <input name="fields[{{$field['unique_id']}}][duplication_batch]" type="hidden" value="{{$field['duplication_batch']}}" />     
    @else

      <div class="form-group fields-container-div row">
       <?php ($question['form_field_required']== 1 ? $required = "Required" : $required = "" ) ?>
        <label class="col-sm-2 control-label text-right">{{$field["form_name"]}} <?php if($required == "Required"){echo "<span>*</span>";} ?></label>
          <div class="col-sm-8 input-container">
            <div class="required-fields-hidden">
              @if(isset($field['form_field_duplicate_times']))
              <input type="hidden" class="duplicate-permission-time" value="{{$field['form_field_duplicate_times']}}">
              <input type="hidden" class="duplicate-permission-fieldId" value="{{$field['form_field_id']}}">
            @endif
              <input name="fields[{{$field['form_field_id']}}][form_name]" type="hidden" value="{{$field['form_name']}}" />
              <input name="fields[{{$field['form_field_id']}}][form_field_type]" class="form-field-type" type="hidden" value="{{$field['form_field_type']}}" />
              <input name="fields[{{$field['form_field_id']}}][form_field_id]" type="hidden" value="{{$field['form_field_id']}}" />
              <input name="fields[{{$field['form_field_id']}}][duplication_batch]" type='hidden' value="{{$field['duplication_batch']}}" />
              <input name="fields[{{$field['form_field_id']}}][duplicated_from]" value="{{$field['duplicated_from']}}" type="hidden">
            </div>
            <?php if($field['form_field_type'] == OPTION_TEXT){ ?>
              <input name="fields[{{$field['form_field_id']}}][answer]" value="{{ $answer }}" type="text" class="form-control allow-copy" {{$required}} />
            <?php } ?>
            <?php if($field['form_field_type'] == OPTION_TEXTAREA){ ?>
               <div class="form-mr-15">
                <textarea name="fields[{{$field['form_field_id']}}][answer]" class="form-control allow-copy" rows="5" {{$required}}>{{ $answer }}</textarea>
              </div>
            <?php } ?>
            <?php if($field['form_field_type'] == OPTION_DATE_PICKER){ ?>
                <input name="fields[{{$field['form_field_id']}}][answer]" type='text' class="form-control datetimepickerDate allow-copy" placeholder="Select Date" value="{{ $answer }}" {{$required}} />
            <?php } ?>
            <?php if($field['form_field_type'] == OPTION_TIME_PICKER){ ?>
                <input name="fields[{{$field['form_field_id']}}][answer]" type='text' class="form-control datetimepickerTime allow-copy" placeholder="Select Time" value="{{ $answer }}" {{$required}} />
            <?php } ?>
            <?php if($field['form_field_type'] == OPTION_RATINGS){ ?>
              <div class="form-mr-15">
              <input name="fields[{{$field['form_field_id']}}][answer]" id="input-7-xs" class="rating rating-loading" value="{{ $answer }}" data-max="5" data-step="0.1" data-size="xs">
              </div>
            <?php } ?>
            <?php if($field['form_field_type'] == OPTION_EMAIL){ ?>
              <input name="fields[{{$field['form_field_id']}}][answer]" type="email" class="form-control allow-copy" placeholder=" Email"  value="{{ $answer }}" data-error="Please enter a valid email address." {{$required}}>
            <?php } ?>
            <?php if($field['form_field_type'] == OPTION_MONETERY){ ?>
              <div class="input-group col-sm-5"> 
                <span class="input-group-addon">$</span>
                <input name="fields[{{$field['form_field_id']}}][answer]" type="number" class="form-control currency NumaricRistriction allow-copy" placeholder=" Enter numaric value" value="{{ $answer }}" step="any" data-error="Please enter monetry value" {{$required}}/>
              </div>
            <?php } ?>
            <?php if($field['form_field_type'] == OPTION_ADDRESS_MAP){ ?>
                <div class="col-sm-12 map-location" initialize="false">
                
                    <div class="input-group">
                          <input name="fields[{{$field['form_field_id']}}][answer]" type="text" class="form-control location allow-copy" id='search_input_{{$field["form_field_id"]}}' placeholder="Add location" value="{{ $answer }}" {{$required}}/>
                          <input name="fields[{{$field['form_field_id']}}][search_location]" value="{{$search_location}}" type="hidden" class="search_location"/>
                          <input name="fields[{{$field['form_field_id']}}][latitude]" value="{{ $latitude }}" type="hidden" class="latitude"/>
                          <input name="fields[{{$field['form_field_id']}}][longitude]" value="{{ $longitude }}" type="hidden" class="longitude"/>
                          <input name="fields[{{$field['form_field_id']}}][address]" value="{{ $address }}" type="hidden" class="address"/>
                          <input name="fields[{{$field['form_field_id']}}][place_id]" value="{{ $place_id }}" type="hidden" class="place_id"/>

                          <span class="input-group-addon text-red" onclick="js:initialize();" data-placement="left" data-toggle="tooltip" data-title="Load Map">
                              <i class="fa fa-fw fa-lg fa-map-marker"></i>
                          </span>
                          
                  </div>
                    <div id="map_wrapper">
                      <!--<input id="pac-input" class="controls" type="text" placeholder="Search Box">-->
                      <div id="map_canvas_{{$field['form_field_id']}}" class="map-canvas mapping"></div>
                    </div>
              </div>
      
              <?php } ?>
            <?php if($field['form_field_type'] == OPTION_SIGNATURE){ ?>
              <div class="input-group col-sm-10"> 
                <div class="sigPad_{{$field['form_field_id']}}">
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
                      <input type="hidden" name="fields[{{$field['form_field_id']}}][answer]" value="{{ $answer }}" class="output canvasanswer_{{$field['form_field_id']}} outputCanvi">
                    </div>
                  </div>
                </div>
              </div>
              @if($answer)
                <script>
                  $(document).ready(function() {
                    //$(".sigPad_{{$field['form_field_id']}}").signaturePad();
                     var sig = $(".canvasanswer_{{$field['form_field_id']}}").val();
                     $(".sigPad_{{$field['form_field_id']}}").signaturePad({displayOnly:true}).regenerate(sig);
                  });
                </script>
              @else
                <script>
                  $(document).ready(function() {
                    $(".sigPad_{{$field['form_field_id']}}").signaturePad({drawOnly:true, lineTop:200});
                  });
                </script>
              @endif

            <?php } ?>
            <?php if($field['form_field_type'] == OPTION_NUMARIC){ ?>
              <input name="fields[{{$field['form_field_id']}}][answer]" type="number" class="form-control NumaricRistriction allow-copy" placeholder=" Enter numaric value" value="{{ $answer }}" step="any" data-error="Please enter a valid numeric value only" {{$required}}/>
            <?php } ?>
            <?php if( $field['form_field_type'] == OPTION_RADIOBUTTON || 
                $field['form_field_type'] == OPTION_CHECKBOX ||
                $field['form_field_type'] == OPTION_DROPDOWN ||
                $field['form_field_type'] == OPTION_AUTO_POPULATE ||
                $field['form_field_type'] == OPTION_BREEDS_AUTO_POPULATE ||
                    $field['form_field_type'] == OPTION_BREEDS_STATUS_AUTO_POPULATE ||
                    $field['form_field_type'] == OPTION_HORSE_AGE_AUTO_POPULATE ||
                    $field['form_field_type'] == OPTION_RIDER_AGE_AUTO_POPULATE ||
                $field['form_field_type'] == OPTION_LABEL 
              ){ ?>

                <div style="{{$TD_variables['TD_options_font_size']}}{{$TD_variables['TD_options_font_color']}}">
                  <?php 
                  if (isset($indexer)) {
                        echo AddOptionsFrontend($pre_fields[$indexer]['form_field_options'], $pre_fields[$indexer], $required ,$field);
                      }
                  ?>
                </div>

            <?php } ?>
            <?php if( $field['form_field_type'] == OPTION_HYPERLINK ){ ?>
                <div style="{{$TD_variables['TD_options_font_size']}}{{$TD_variables['TD_options_font_color']}}" class="form-mr-15 {{$required}}">
                  <div class="Linker_div">
                      <input type="button" class="btn-small btn-success addLinkToClick" value="Add/Edit Link">
                      
                      <input name="fields[{{$field['form_field_id']}}][answer]" value="{{ $answer }}" type="text" class="form-control allow-copy input-linker" style="display:none" {{$required}} />
                      
                    </div>
                      @if($answer)
                        <a href="http://{{$answer}}" target="_blank">{{$answer}}</a><br>
                      @endif
                     <script type="text/javascript">
                      $(function () {
                        $("body").on( "click", ".addLinkToClick", function(event) {
                          event.preventDefault();
                          $(this).closest(".Linker_div").find(".input-linker").show();
                        });
                      });
                      
                      </script>
                  <?php 
                  if (isset($indexer)) {
                        echo AddOptionsFrontend($pre_fields[$indexer]['form_field_options'], $pre_fields[$indexer], $required ,$field);
                      }
                  ?>
                </div>

            <?php } ?>
            <!-- Video -->
             <?php if($field['form_field_type'] == OPTION_VIDEO ){ ?>
               @if( isset($question['form_field_options'][1]))
                @if( $question['form_field_options'][1]['upload_files'] != "")
                  <div class="form-group form-mr-15 clearfix">
                    <div style="border: 1px solid;float: left;margin: 3px;padding: 5px;">
                        <video width="520" height="340" controls>
                        <?php //$URLs = PATH_UPLOAD_FORMS."master_temp_$FormTemplate->template_id/form_$formid/";?> 
                        <source src="{{ getImageS3($question['form_field_options'][1]['upload_files']) }}" type="video/mp4">
                        Your browser does not support the video tag.
                        </video>
                    </div>
                  </div>
                @endif
               @endif
               @if( isset($question['form_field_ischangeable'] ))
                @if( $question['form_field_ischangeable'] == 1)
                  <div style="{{$TD_variables['TD_options_font_size']}}{{$TD_variables['TD_options_font_color']}}">
                    <div class="form-mr-15 uploadimage">
                        <input type="file" name="fields[{{$field['form_field_id']}}][upload_files]" class="file video-uploader-browser" data-overwrite-initial="false" {{$required}}>
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
                        <input type="hidden" name="fields[{{$field['form_field_id']}}][upload_files]" value="{{$answer}}">
                        
                        </div>
                      @endif
                    </div>
                 </div>
                @endif
              @endif
            <?php } ?>
            <!-- Image -->
             <?php if($field['form_field_type'] == OPTION_IMAGE){ ?>
               @if( isset($question['form_field_options'] ))
                @if( $question['form_field_options'][1]['upload_files'] != "")
                  <div class="form-group form-mr-15 clearfix">
                    <div style="border: 1px solid;float: left;margin: 3px;padding: 5px;">
                      <a href="#" data-toggle="modal" data-target="#myModal{{$field['form_field_id']}}">
                        <?php //$URLs = PATH_UPLOAD_FORMS."master_temp_$FormTemplate->template_id/form_$formid/";?> 
                        <img src="{{ getImageS3($question['form_field_options'][1]['upload_files']) }}" height="240" />      
                      </a>
                    </div>
                    <!-- Popup -->
                    <div id="myModal{{$field['form_field_id']}}" class="modal fade adminModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-body">
                            <?php //$URLs = PATH_UPLOAD_FORMS."master_temp_$FormTemplate->template_id/form_$formid/";?> 
                            <img src="{{ getImageS3($question['form_field_options'][1]['upload_files']) }}" />      
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                @endif
               @endif
               @if( isset($question['form_field_ischangeable'] ))
                @if( $question['form_field_ischangeable'] == 1)
                  <div style="{{$TD_variables['TD_options_font_size']}}{{$TD_variables['TD_options_font_color']}}">
                    <div class="uploadimage form-mr-15">
                      @if($answer == "")
                      <input  type="file" multiple class="file image-uploader-browser uploaded uploaded-image-req" data-overwrite-initial="false" {{$required}}>
                      @else
                      <input class="uploaded-image-req {{$required}}" id="input-{{$field['form_field_id']}}" type="file" multiple >
                        <?php $previousImages = getPreviousArray($answer); ?>
                         <script>
                          $(document).on('ready', function() {
                              $("#input-{{$field['form_field_id']}}").fileinput({
                                  initialPreview: <?php echo $previousImages ?>,
                                  initialPreviewAsData: true,
                                  overwriteInitial: false,
                                  uploadUrl: '#', // you must set a valid URL here else you will get an error
                                  allowedFileExtensions: ['jpg','svg', 'png', 'gif'],
                                  overwriteInitial: false,
                                  maxFileCount: 10,
                                  minFileCount: 1,
                                  maxFileSize: 6500,
                                  //allowedFileTypes: ['image', 'video', 'flash'],
                                  slugCallback: function (filename) {
                                      return filename.replace('(', '_').replace(']', '_');
                                  }
                              });
                              $(".kv-file-zoom").attr("disabled",false)
                          });
                          </script>
                          @foreach($answer as $exitstingFile)
                          <input type="hidden" name="fields[{{$field['form_field_id']}}][upload_files][]" value="{{$exitstingFile}}" />      
                          @endforeach
                      @endif
                      <input type="hidden" class="fileuploaderid" value="{{$field['form_field_id']}}">
                      <div class="fileholders">
                      </div>
                    </div>
                 </div>
                @endif
              @endif
            <?php } ?>
             <!-- Attachment -->
             <?php if($field['form_field_type'] == OPTION_ATTACHMENT){ ?>
               @if( isset($question['form_field_options'] ))
                @if( $question['form_field_options'][1]['upload_files'] != "")
                  <div class="form-group form-mr-15 clearfix">
                    <div style="border: 1px solid;float: left;margin: 3px;padding: 10px;">
                        <?php //$URLs = PATH_UPLOAD_FORMS."master_temp_$FormTemplate->template_id/form_$formid/";?> 
                        <a href="{{ getImageS3($question['form_field_options'][1]['upload_files'])}}" target="_blank">Click here to preview file</a>
                    </div>
                  </div>
                @endif
               @endif
               
               @if( isset($question['form_field_ischangeable'] ))
                @if( $question['form_field_ischangeable'] == 1)
                  <div style="{{$TD_variables['TD_options_font_size']}}{{$TD_variables['TD_options_font_color']}}">
                    <div class="uploadimage fileupload form-mr-15">
                      <input type="file" multiple name="fields[{{$field['form_field_id']}}][upload_files][]" {{$required}}>
                      <input type="hidden" class="fileuploaderid" value="{{$field['form_field_id']}}">
                      <div class="fileholders">
                        @if(isset($answer) && $answer!='')
                          @foreach($answer as $Aindex => $file)
                          <div class="file-preview-frame">
                            <div class="col-sm-4"style="border: 1px solid;float: left;margin: 3px;padding: 10px;">
                              <a href="{{ $disk->url($file['path']) }}" target="_blank">{{ $file['name'] }}</a>
                            
                            <input type="hidden" name="fields[{{$field['form_field_id']}}][upload_filess][{{$Aindex}}][path]" value="{{$file['path']}}" />      
                            <input type="hidden" name="fields[{{$field['form_field_id']}}][upload_filess][{{$Aindex}}][name]" value="{{$file['name']}}" />      
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
    @endif
      </div>
 @endif
</div>
 @endforeach