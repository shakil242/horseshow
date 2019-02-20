            <div class="col-md-12">
              <div class="template-create">
                <h4>Logo</h4>
                <div class="holder">
                  {!! Form::file('logo_image') !!}
                    <?php 
                    if (isset($design_template->logo_image)) { ?>
                      <div style="border: 1px solid;float: left;margin: 3px;padding: 5px;">
                        <a href="{{URL::to('admin/template/design') }}/{{ $design_template->id }}/logo-delete" data-toggle="tooltip" data-placement="top" title="Delete Image" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                        <img src="{{ getImageS3($design_template->logo_image) }}" height="50" width="50" />
                      </div>
                    <?php } ?>
                  <div class="logo-resolution">
                    <label>Logo Resolution</label>
                    <div class="row">
                      <div class="col-sm-5">
                        <div class="form-group">
                         {!! Form::text('logo_resolution_width', null , ['class' =>"form-control",'placeholder'=>"Size"]) !!}
                        </div>
                      </div>
                      <div class="col-sm-2">
                        <span class="multiply">X</span>
                      </div>
                      <div class="col-sm-5">
                        <div class="form-group">
                         {!! Form::text('logo_resolution_hight', null , ['class' =>"form-control",'placeholder'=>"Size"]) !!}
                      </div>
                      </div>
                    </div>
                    <br />
                    <label>Position</label>
                    <div class="row">
                      <div class="col-sm-4">
                        <label class="desgin-labels">
                        {{ Form::radio('logo_position', '1', true) }}
                        Top</label>
                      </div>
                      <div class="col-sm-4">
                       
                        <label class="desgin-labels"> {{ Form::radio('logo_position', '2') }}Bottom</label>
                      </div>
                    </div>
                    <br />
                    <label>Alignment</label>
                    <div class="row">
                      <div class="col-sm-4">
                        <label class="desgin-labels">{{ Form::radio('logo_allignment', '1', true) }}Left</label>
                      </div>
                      <div class="col-sm-4">
                        <label class="desgin-labels">{{ Form::radio('logo_allignment', '2') }}Center</label>
                      </div>
                      <div class="col-sm-4">
                        <label class="desgin-labels">{{ Form::radio('logo_allignment', '3') }}Right</label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="template-create">
                <h4>Background</h4>
                <div class="holder">
                <div class="col-sm-10">
                  <label>background Color</label>
                  <div class="row">
                    <div class="col-sm-6">
                      <div class="form-group">
                         {!! Form::text('background_color', null , ['class' =>"form-control colorboxadd",'placeholder'=>"Color"]) !!}
                      </div>
                    </div>
                    <div class="col-sm-6">
                      {!! Form::file('background_image') !!}
                    </div>
                  </div>
                  <br />
                  <label>Background Properties</label>
                  <div class="row">
                    <div class="col-sm-4">
                      <label class="desgin-labels">{{ Form::radio('background_image_repeat', '1', true) }}Stretch</label>
                    </div>
                    <div class="col-sm-4">
                      <label class="desgin-labels">{{ Form::radio('background_image_repeat', '2') }}Original</label>
                    </div>
                    <div class="col-sm-4">
                      <label class="desgin-labels">{{ Form::radio('background_image_repeat', '3') }}Repeat</label>
                    </div>
                  </div>
                </div>
                <div class="col-sm-2">
                  <?php 
                    if (isset($design_template->background_image)) { ?>
                      <div style="border: 1px solid;float: left;margin: 3px;padding: 5px;">
                        <a href="{{URL::to('admin/template/design') }}/{{ $design_template->id }}/background-delete" data-toggle="tooltip" data-placement="top" title="Delete Image" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                        <img src="{{getImageS3($design_template->background_image) }}" height="100" width="150" />
                      </div>
                    <?php } ?>
                </div> 
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="template-create">
                <h4>Tilte</h4>
                <div class="holder">

                  <div class="row">
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label>Font Size</label>
                        <!-- {!! Form::text('title_font_size', null , ['class' =>"form-control",'placeholder'=>"Font Size"]) !!} -->
                      
                          <div class="row">
                            <div class="col-sm-8">
                              <?php 
                              if (isset($design_template->title_font_size)) {
                                $selected_v = $design_template->title_font_size;
                              }else{
                                $selected_v = "";
                              }
                              echo CreateFontsizeDrp("title_font_size",$selected_v,8,70)  ?>
                            </div>
                      
                          </div>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label>Font Color</label>
                        {!! Form::text('title_font_color', null , ['class' =>"form-control colorboxadd",'placeholder'=>"Font Color"]) !!}
                      </div>
                    </div>
                  </div>
                  <br />
                  <label>Alignment</label>
                  <div class="row">
                    <div class="col-sm-4">
                      <label class="desgin-labels">{{ Form::radio('title_font_allignment', '1',true) }}Left</label>
                    </div>
                    <div class="col-sm-4">
                      <label class="desgin-labels">{{ Form::radio('title_font_allignment', '2') }}Center</label>
                    </div>
                    <div class="col-sm-4">
                      <label class="desgin-labels">{{ Form::radio('title_font_allignment', '3') }}Right</label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="template-create">
                <h4>Field</h4>
                <div class="holder">

                  <div class="row">
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label>Font Size</label>
                        <!-- {!! Form::text('field_font_size', null , ['class' =>"form-control",'placeholder'=>"Font Size"]) !!} -->
                       
                          <div class="row">
                            <div class="col-sm-8">
                              <?php 
                              if (isset($design_template->field_font_size)) {
                                $selected_v = $design_template->field_font_size;
                              }else{
                                $selected_v = "";
                              }
                              echo CreateFontsizeDrp("field_font_size",$selected_v,8,50)  ?>
                            </div>
                      
                          </div>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label>Font Color</label>
                        {!! Form::text('field_font_color', null , ['class' =>"form-control colorboxadd",'placeholder'=>"Font Color"]) !!}
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="template-create">
                <h4>Options</h4>
                <div class="holder">
                  <div class="row">
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label>Font Size</label>
                        <!-- {!! Form::text('options_font_size', null , ['class' =>"form-control",'placeholder'=>"Font Size"]) !!} -->
                           <div class="row">
                            <div class="col-sm-8">
                              <?php 
                              if (isset($design_template->options_font_size)) {
                                $selected_v = $design_template->options_font_size;
                              }else{
                                $selected_v = "";
                              }
                              echo CreateFontsizeDrp("options_font_size",$selected_v,8,50)  ?>
                            </div>
                      
                          </div>

                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label>Font Color</label>
                        {!! Form::text('options_font_color', null , ['class' =>"form-control colorboxadd",'placeholder'=>"Font Color"]) !!}
                        
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

<script type="text/javascript">
  $(function() { 
    //For Upload Lable on Browse lable
    setTimeout(function(){$(".jcf-upload-button span").html("UPLOAD IMAGE"); }, 100);
    //For Color Picker
    $('input.colorboxadd').colorpicker({
      format: "hex"
    });
  });
</script>
