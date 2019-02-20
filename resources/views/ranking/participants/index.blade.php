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
          $added_subtitle = Breadcrumbs::render('ranking-index-participant',$participant_id);
        @endphp
        @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle,'remove_search'=>1])

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
                                @if(!$participantResponse->count())
                                  <div class="row">
                                    <div class="col-lg-5 col-md-5 col-sm-6">{{NO_PARTICIPANT_RESPONSE}}</div>
                                  </div>
                                @else
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="division-tab" data-toggle="tab" href="#overallRanking" role="tab" aria-controls="home" aria-selected="true">Overall Ranking</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="showclasses-tab" data-toggle="tab" href="#moduleRanking" role="tab" aria-controls="profile" aria-selected="false">Modules wise Ranking</a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    <!-- Tab 1 Content -->
                                    <div class="tab-pane fade show active" id="overallRanking" role="tabpanel" aria-labelledby="Overall-Ranking-tab">
                                       @include('ranking.participants.overallRanking') 
                                    </div>
                                    
                                    <!-- Tab 2 Content -->
                                    <div class="tab-pane fade" id="moduleRanking" role="tabpanel" aria-labelledby="showclasses-tab">
                                         <div id="ajax-loading" class="loading-ajax"></div>
                                        <br>
                                        <div class="back-to-all"><a href="#" class="back-to-all-modules" template-id="{{nxb_encode($template_id)}}"><span class="glyphicon glyphicon-circle-arrow-left"></span> Back to All modules</a></div>
                                        <div class="ajax-renders">
                                           @include('ranking.participants.modules') 
                                        </div>
                                    </div>
                                
                                </div>

       
                                
                                @endif
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
    
    <script src="{{ asset('/js/nxb-search-rapidly.js') }}"></script>
    <script src="{{ asset('/js/custome/ajax-call-ranking-modules-participants.js') }}"></script>
@endsection
