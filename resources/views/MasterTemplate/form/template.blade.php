<?php
    /**
     * This is Form Previewer. 
     * TD = Template Design (The template designing is stored in this variable)
     * TD_variables = Helper function variable for all the designing
     * form-preview css is the css for custom design by user.
     *
     * @author Faran Ahmed (Vteams)
     */
?>
<link href="{{ asset('/css/form-preview.css') }}" rel="stylesheet">

<div class="TD-main-container" style="{{$TD_variables['background_img_color']}}">
  @if(!empty($TD_variables["TD_logo_image"]))
    <!-- TD_logo_position = 1 TOP Position of the logo -->
    @if($TD_variables["TD_logo_position"] == 1)
       <div class="col-xs-12">
         <div class="">
            <div class="form-group {{ $TD_variables['TD_logo_allignment']}}">
              <img src="{{ getImageS3($TD_variables['TD_logo_image']) }}" width="{{$TD_variables['TD_logo_resolution_width']}}" height="{{$TD_variables['TD_logo_resolution_hight']}}">
            </div>
         </div>
       </div>
    @endif
    <!-- END: TD_logo_position = 1 TOP Position of the logo -->
  @endif

  <!-- Number of forms in Master Template to be previewed -->
    <div class="custom-class">
      @include('MasterTemplate.form.preview')
    </div>
    
  <!-- END: TD_logo_position = 1 TOP Position of the logo -->
  @if(!empty($TD_variables["TD_logo_image"]))
    <!-- TD_logo_position = 2 Bottom Position of the logo -->
    @if($TD_variables["TD_logo_position"] == 2)
      <div class="col-xs-12">
        <div class="">
            <div class="form-group {{ $TD_variables['TD_logo_allignment']}}">
              <img src="{{ getImageS3($TD_variables['TD_logo_image']) }}" width="{{$TD_variables['TD_logo_resolution_width']}}" height="{{$TD_variables['TD_logo_resolution_hight']}}">
            </div>
         </div>
       </div>
    @endif
    <!-- END: TD_logo_position = 2 Bottom Position of the logo -->
  @endif
  </div>
<div id="ajax-loading" class="loading-ajax"></div>
<br />
