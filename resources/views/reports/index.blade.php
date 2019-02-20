@extends('layouts.equetica2')

@section('custom-htmlheader')
  <!-- Search populate select multiple-->

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
              if(isset($breadcrumb))
                $title = "Your Responses";
              else
                $title = "Participants Responses";
          
              if(isset($breadcrumb))
               $added_subtitle = Breadcrumbs::render("$breadcrumb", [nxb_encode($forms->template_id),nxb_encode($invitee_id)]); 
              else
               $added_subtitle = Breadcrumbs::render('master-template-participants-all-readonly', $forms->template_id);
  
          $remove_search = 1;
        @endphp
        @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle,'remove_search'=>1])

        <!-- Content Panel -->
        <div class="white-board">  
          <div class="row">
                <!-- <div class="col-md-12">
                    <div class="back"><a onclick="history.go(-1);" class="back-to-all-modules" href="#"><span class="glyphicon glyphicon-circle-arrow-left"></span> Back</a></div>
                </div> -->
            </div>
            
            <div class="row">
                    <div class="col-md-12">
                            
                        <!-- TAB CONTENT -->
                        <div class="tab-content" id="myTabContent">
                            <!-- Tab Data Divisions -->
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="division-tab">
                              @include('admin.layouts.errors')
                              <div class="row box-shadow bg-white p-4 mb-30">
                                  <div class="col-sm-12">
                                    {!! Form::open(['url'=>'report/assets/graphics/response/','method'=>'post']) !!}
                                    <input type="hidden" name="form_id" value="{{$forms->id}}">
                                    <div class="row form-group">
                                     <div class="col-sm-2"> <label style="padding-top:5px">Select Asset:</label></div>
                                      <div class="col-sm-7">
                                        <select id="basic" required multiple name="asset[]" class="selectpicker show-tick form-control" data-live-search="true">
                                        @foreach($assets as $asset_ids)
                                          <option value="{{$asset_ids}}" @if($selectedAssets != null) {{ (in_array($asset_ids, $selectedAssets) ? "selected":"") }}@else {{"selected"}} @endif> {{ GetAssetNamefromId($asset_ids) }}</option>
                                        @endforeach
                                      </select>
                                    </div>
                                    <div class="col-sm-3">
                                      <input type="submit" class="active link-summary btn btn-secondary" value="Filter">
                                    </div>
                                  </div>
                                  {!! Form::close() !!}
                                  </div>
                                   <div class="col-sm-12">
                                     <!--- All modules -->
                                    <section id="responses-holder" class="tab-content">
                                      <!---  Repsonses Summary -->
                                      
                                      <div id="Summary" class="summary-responses tab-pane slide in active">
                                          @include('reports.answersGraph') 
                                      </div>
                                      <!-- Indiviusal Repsonses -->
                                    </section>
                                    <!--- App listing -->
                                   </div>
                                    
                              </div>
                            </div>
                            
                        </div>
                        <!-- ./ TAB CONTENT -->
                    </div>
                </div>
        <!-- ./ Content Panel -->  
        </div>
    </div>
</div>

<!-- ================= ./ CONTENT AREA ================ -->
        
@endsection
@section('footer-scripts')
    @include('layouts.partials.datatable')
    <script src="{{ asset('/js/vender/highcharts.js') }}"></script>
    <script src="{{ asset('/js/vender/highcharts-3d.js') }}"></script>
    <script src="{{ asset('/js/vender/offline-exporting.js') }}"></script>
    <script src="{{ asset('/js/vender/exporting.js') }}"></script>

@endsection