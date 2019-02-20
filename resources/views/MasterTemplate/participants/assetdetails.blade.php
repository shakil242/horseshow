@extends('layouts.equetica2')
@section('custom-htmlheader')
    @include('layouts.partials.form-header')
@endsection
@section('main-content')
    <!-- ================= CONTENT AREA ================== -->
    <div class="container-fluid">
    @php
        $title = "Asset Details";
        $added_subtitle =Breadcrumbs::render('assets-details');
    @endphp
    @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle,'remove_search'=>1])
    <!-- Content Panel -->
        <div class="white-board">

            <div class="row">
                <div class="info">
                    @if(Session::has('message'))
                        <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                    @endif
                </div>
            </div>
            <!--- App listing -->
        @if($participant_collection->count())
        <div class="row">
          <div class="col-sm-12">
              <div class="row">

              <div class="col-md-2">
            <strong>App Name</strong>
            </div>
            <div class="col-md-6">
              {{GetTemplateName($participant_collection->template_id,$participant_collection->invitee_id)}}
            </div>

              </div>
              <hr>
          </div>
          <div class="col-sm-12">
              <div class="row">

              <div class="col-md-2">
            <strong>Asset Invited On</strong>
            </div>
            <div class="col-md-6">
              {{GetAssetNamefromId($participant_collection->asset_id)}}
            </div>

              </div>
              <hr>
          </div>
          <div class="col-sm-12">
              <div class="row">

              <div class="col-md-2">
            <strong>Invited By</strong>
            </div>
            <div class="col-md-6">
              {{getUserNamefromid($participant_collection->invitee_id)}}
            </div>
          </div>
              <hr>

          </div>

          <div class="col-sm-12">
              <div class="row">
            <div class="col-md-2">
            <strong>Location of the Asset</strong>
            </div>
              @if($participant_collection->location)
              <div class="col-md-6">
                {{$participant_collection->location}}
                 <div id="map_canvas"></div>
                  <script>

                  function initMap() {
                    var myLatLng = {lat: {{$participant_collection->latitude}}, lng: {{$participant_collection->longitude}} };

                    var map = new google.maps.Map(document.getElementById('map_canvas'), {
                      zoom: 15,
                      center: myLatLng
                    });

                    var marker = new google.maps.Marker({
                      position: myLatLng,
                      map: map,
                    });
                  }
                  </script>
                </div>
            @else
              <div class="col-md-6">
                <p>No Location given for this asset!</p>
              </div>
            @endif
          </div>
              <hr>
          </div>
          <div class="col-sm-12">
              <div class="row">
              <div class="col-md-2">
            <strong>Date Invited</strong>
            </div>
            <div class="col-md-6">
              {{getDates($participant_collection->created_at)}}
            </div>
          </div>
              <hr>
          </div>
          @if($participant_collection->description != '')
          <div class="col-sm-12">
              <div class="row">

              <div class="col-md-2">
            <strong>Details</strong>
            </div>
            <div class="col-md-6">
              {{$participant_collection->description}}
            </div>
          </div>
              <hr>
          </div>
          @endif

          </div>
        @else
          <div class="col-lg-5 col-md-5 col-sm-6">{{MASTER_TEMPLATE_NO_ASSET_INVITE_TEXT}}</div>     
		    @endif
@endsection

@section('footer-scripts')
    <script src="{{ asset('/js/google-map-location.js') }}"></script>
@endsection

