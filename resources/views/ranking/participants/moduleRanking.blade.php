@extends('layouts.equetica2')

@section('custom-htmlheader')

@endsection

@section('main-content')

@if(Session::has('message'))
<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
@endif

     <!-- ================= CONTENT AREA ================== -->
<div class="main-contents">
    <div class="container-fluid">
        @php 

          $title = "Ranking";
          $added_subtitle = Breadcrumbs::render('ranking-moduleRanking-participant',nxb_encode($participant_id));
        @endphp
        @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle])

        <!-- Content Panel -->
        <div class="white-board">  
          <div class="row">
                <!-- <div class="col-md-12">
                    <div class="back"><a onclick="history.go(-1);" class="back-to-all-modules" href="#"><span class="glyphicon glyphicon-circle-arrow-left"></span> Back</a></div>
                </div> -->
            </div>
            
            <div class="row box-shadow bg-white p-4 mb-30">
                    <div class="col-md-12">
                            
                        <!-- TAB CONTENT -->
                        <div class="tab-content" id="myTabContent">
                            <!-- Tab Data Divisions -->
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="division-tab">
                              @include('admin.layouts.errors')
                                 <div class="row">
                                  <div class="col-sm-12">
                                    @if(!$participantResponse->count())
                                      <div class="row">
                                        <div class="col-lg-5 col-md-5 col-sm-6">{{NO_PARTICIPANT_RESPONSE}}</div>
                                      </div>
                                    @else

                                      @include('ranking.participants.overallRanking') 
                                    
                                    </div>
                                    
                                    @endif
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
    <script src="{{ asset('/js/vender/data.js') }}"></script>
    <script src="{{ asset('/js/vender/drilldown.js') }}"></script>
    <script src="{{ asset('/js/vender/no-data-to-display.js') }}"></script>
    
    <script src="{{ asset('/js/ranking-chart-js.js') }}"></script>
    <script src="{{ asset('/js/nxb-search-rapidly.js') }}"></script>
@endsection
