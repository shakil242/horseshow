@extends('layouts.equetica2')

@section('custom-htmlheader')
  <!-- Search populate select multiple-->
 <script src="{{ asset('/js/vender/bootstrap3-typeahead.min.js') }}"></script>
  <!-- END:Search populate select multiple-->
@endsection

@section('main-content')
@if(Session::has('message'))
<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
@endif
     <!-- ================= CONTENT AREA ================== -->
<div class="main-contents">
    <div class="container-fluid">
        @php 
          $ya_fields = getButtonLabelFromTemplateId($template_id,'ya_fields');
          $title = post_value_or($ya_fields,'project_overview','Project Overview')." Listing";
          $added_subtitle = Breadcrumbs::render('master-template-projectoverview',$template_id);
          
        @endphp
        @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle,''])

         <!-- Content Panel -->
        <div class="white-board">            
            
          <div class="row">
              <div class="col">
                
                @if($AssetsForms->count())
                  <ul id="display-assets-tables" class="nav nav-tabs" id="myTab" role="tablist">
                      @php $active = 0 @endphp
                      @foreach($AssetsForms as $index => $row)
                        <li class="nav-item">
                          <a class="@if($active == 0) active @endif nav-link" data-toggle="tab" href="#tab_{{$row->id}}" data-attr="{{$row->id}}">
                            {{$row->name}}
                          </a>
                        </li>
                          @php $active = 1 @endphp
                      @endforeach
                  </ul>
                @endif
            </div>
            <div class="col-md-2">
                <div class="form">
                    <div class="button-group dropdown-multi-selection">
                        <button type="button" class="btn-sm label" data-toggle="dropdown" aria-expanded="false">
                            <span class="caret text-left">Column (Show/Hide)</span>
<!--                                    <span class="icon fa fa-angle-down"></span>-->
                        </button>
                        <div class="ToggleColumb">
                           
                        </div>
                    </div>
                </div>
              </div>
        </div>
        <!-- Tab containing all the data tables -->
        <div class="tab-pane active">
          <!--- App listing -->
          @if($AssetsForms->count())
            <div class="row">
            <div class="col-sm-12 pull-right">
            <form method="post" action="{{URL::to('master-template') }}/user/add/project-overview">
                  <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="form_id" id="form_id_assets" value="0">
                <input type="submit" class="add-assetform-btn btn btn-primary pull-right" value="Add">
                <a target="_blank" href="{{URL::to('master-template') }}/{{nxb_encode($template_id)}}/project-overview/email/full-list" class="btn btn-secondary btn-success pull-right mr-10">View Emails</a>
            </form>
            </div>
          </div>
            @endif

          @if($AssetsForms->count())
          <div class="module-holer rr-datatable">
            <div id="tableDiv"></div>
          </div>
          @else
           <div class="row">
              <div class="col-lg-5 col-md-5 col-sm-6">You have not added any asset for this template yet!</div>
           </div>
          @endif

          

        </div>
        <!-- ./ Content Panel -->  
        </div>

        <div id="getQrCode" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">QR Code</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <div class="modal-body">

                        <div id="QrCode">

                        </div>
                        <!-- The template for adding new field -->
                        <div class="modal-footer">
                            {{--<button type="submit" class="btn btn-default">Submit</button>--}}
                            <a class="btn btn-success qrCodeCon" href="data:image/png;base64,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" download>Download QR Code</a>

                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>



                    </div>
                </div>


            </div>

        </div>


    </div>
</div>

<!-- ================= ./ CONTENT AREA ================ -->
@endsection

@section('footer-scripts')
<div id="ajax-loading" class="loading-ajax" style="display: none;"></div>
    <script src="{{ asset('/js/project-overview/ajax-dynamic-assets-table.js') }}"></script>
    <script src="{{ asset('/js/export-excel-assets.js') }}"></script>
    @include('admin.layouts.partials.datatable')
        <script src="{{ asset('/js/custom-tabs-cookies.js') }}"></script>
        <script type="text/javascript">

          $( document ).on( "click", "a.linkss-selected", function() {
            $(".dropdown-values").toggle();
          });

          function getQrCode(id) {

              var url = '/master-template/'+id+"/getQrCode";
              $.ajax({
                  url: url,
                  type: "GET",
                  success: function (data) {
                      $("#getQrCode").modal('show');
                      $("#QrCode").html(data['qrCode']);
                      $(".qrCodeCon").attr("href",data['qrCodeUrl']);
                  }, error: function () {
                      alert("error!!!!");
                  }
              });

          }

        </script>
@endsection
