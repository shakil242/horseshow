@extends('layouts.equetica2')

@section('custom-htmlheader')

@endsection

@section('main-content')
	 	<div class="row">
		 	<div class="col-sm-8">
        Horse Rankings
      </div>
      	</div>
      	<div class="row">
          <com-ranking></com-ranking>
      	</div>
        <div class="row">
          <div class="col-sm-12">
            @if(!$participantResponse)
              <div class="row">
                <div class="col-lg-5 col-md-5 col-sm-6">{{NO_PARTICIPANT_RESPONSE}}</div>
              </div>
            @else
            <ul class="nav nav-tabs">
              <li class="active"><a data-toggle="tab" href="#overallRanking">Overall Ranking</a></li>
              <li><a data-toggle="tab" href="#moduleRanking">Modules wise Ranking</a></li>
            </ul>

            <div class="tab-content">
              <div id="overallRanking" class="tab-pane fade in active">
                @if($cumulativeCheck)
                  @include('ranking.cumulative.overallRanking')
                @else
                  @include('ranking.overallRanking') 
                @endif
              </div>
              <!-- Next Modules Tabs-->
              <div id="moduleRanking" class="tab-pane">
                  <div id="ajax-loading" class="loading-ajax"></div>
                  <br>
                  <div class="back-to-all"><a href="#" class="back-to-all-modules" template-id="{{nxb_encode($template_id)}}"><span class="glyphicon glyphicon-circle-arrow-left"></span> Back to All modules</a></div>
                  <div class="ajax-renders">
                    @include('ranking.modules') 
                  </div>
              </div>


            </div>
            
            @endif
          </div>
        </div>
        <!-- Tab containing all the data tables -->
   
		
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
    <script src="{{ asset('/js/custome/ajax-call-ranking-modules.js') }}"></script>
     <script src="{{ asset('/js/custom-tabs-cookies.js') }}"></script>
@endsection
