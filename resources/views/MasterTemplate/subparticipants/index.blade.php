@extends('layouts.equetica2')

@section('custom-htmlheader')
  @include('layouts.partials.form-header')
@endsection

@section('main-content')

  <!-- ================= CONTENT AREA ================== -->
  <div class="main-contents">
    <div class="container-fluid">

    @php
      $ya_fields = getButtonLabelFromTemplateId($template_id,'ya_fields');
      $title = post_value_or($ya_fields,'sub_participants','Invite Sub Participants');
      $added_subtitle ='';
    @endphp
    @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle,'remove_search'=>1])

    <!-- Content Panel -->
      <div class="white-board">


      @include('admin.layouts.errors')

      <!--- Invite participants -->
        {!! Form::open(['url'=>'master-template/invite/sub-participants/select/','method'=>'post','files'=> true,'class'=>'form-horizontal dropzone targetvalue']) !!}
        <input type="hidden" name="template_id" value="{{$template_id}}">
        <input type="hidden" name="key_id" value="{{$invite_key}}">

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <div class="col-sm-3"> <strong>Asset</strong> </div>
              @foreach($assetArr as $asset)
                <div class="col-sm-9">
                  <label>{{GetAssetNamefromId($asset)}} <input type="hidden" name="asset[]" value="{{$asset}}"></label>
                </div>
              @endforeach

              @foreach($participantArr as $participant)
                <input type="hidden" name="participant_id[]" value="{{$participant}}">
              @endforeach

            </div>

          </div>
          <div class="col-md-6 form-group">
            <textarea name="description" class="invite-textarea form-control form-control-lg" placeholder="Description">{{old("description")}}</textarea>
          </div>
        </div>
        <hr>
          <div class="participants-holder">
            <h2>Participants</h2>

            <div class="invite-participants-table">
              <div class="row">
              <div class="participants-filters col-sm-9">

                <div class="form-group">
                  <div class="form-check">
                    <label class="form-check-label">
                      <input class="form-check-input select-past-participant" name="legendRadio" id="legendCheck1" type="checkbox">
                      <span>Select all past participants</span>
                    </label>
                  </div>
                </div>

              </div>
              {{--<div class="col-sm-3">--}}
                {{--<div class="search-field mr-10">--}}
                  {{--<div class="input-group">--}}
                    {{--<input class="form-control" placeholder="Search By Name,Location or Average Cost" id="mySearchTerm" aria-label="Username" aria-describedby="basic-addon1" type="text">--}}
                    {{--<div class="input-group-prepend">--}}
                      {{--<span class="input-group-text" id="basic-addon1"><img src="{{asset('/img/icons/icon-search.svg')}}"></span>--}}
                    {{--</div>--}}
                  {{--</div>--}}
                {{--</div>--}}
              {{--</div>--}}
              </div>

                <div class="table-responsive">
                  <table class="table table-line-braker mt-10 custom-responsive-md dataTableView">
                <thead class="hidden-xs">
                  <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Location</th>
                    <th scope="col">Invite</th>
                  </tr>
                </thead>
                <tbody>
                  @if($pastParticipants->count())
                  @foreach($pastParticipants as $PP)
                  <tr>
                    <td>
                      <span class="table-title">Name</span>
                      {{getUserNamefromEmail($PP->email)}}
                    </td>
                   
                    <td>
                      <span class="table-title">email</span>
                      {{$PP->email}}
                    </td>
                     <td>
                      <span class="table-title">Location</span>
                      {{$PP->location}}
                     
                    </td>
                    <td>
                      <span class="table-title">Invite</span>
                      <a href="javascript:void(0)" data-email="{{$PP->email}}" class="btn-invite-parti">Select for Invite</a>
                    </td>
                  </tr>
                  @endforeach
                  @endif
                </tbody>
              </table>
            </div>
              <hr>

          <div class="new-participants">
            <h2>Invite New Sub Participants</h2>
            <div class="row">
            <div class="col-md-6 add-more-participants-fields mt-30">
              @if(old('emailName'))
                <?php $indexer = 0 ?>
                @foreach(old('emailName') as $emailNameVal)

                  <div class="row number-emails">
                 <div class="col-md-12">
                   <div class="row">
                    <div class="col-sm-6">
                      <div class="form-group">
                        <input name="emailName[{{$indexer}}][name]" type="text" placeholder="Name" class="form-control" value="{{$emailNameVal['name']}}"/>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <input name="emailName[{{$indexer}}][email]" type="email" placeholder="Email" class="form-control" value="{{$emailNameVal['email']}}"/>
                      </div>
                    </div>
                    {{--<div class='col-sm-1'>--}}
                      {{--<div class='pull-left'>--}}
                      {{--<button type='button' class='close remove-this-entry' aria-label='Close'>--}}
                        {{--<span aria-hidden='true'>&times;</span>--}}
                      {{--</button>--}}
                      {{--</div>--}}
                    {{--</div>--}}
                   </div>
                 </div>
                  </div>
                  <?php $indexer = $indexer+1; ?>
                @endforeach
              @else
              <div class="row number-emails">
              <div class="col-sm-6">
                <div class="">
                  <input name="emailName[0][name]" type="text" placeholder="Name" class="form-control" value="{{old('name')}}"/>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="">
                  <input name="emailName[0][email]" type="email" placeholder="Email" class="form-control" value="{{old('email')}}"/>
                </div>
              </div>
              </div>
              @endif
              </div>
              <div class="col-sm-2 mt-30" style="height: 50px;">
                <button type="button" class="btn btn-success btn-add-new-user" data-type="plus">
                  Add More
                </button></div>
              <div class="col-sm-1 mt-30"> <h1> OR </h1></div>
              <div class="col-sm-3">
                <div class="excel-participants">
                  <div style="padding-left:5px"><h3>Import via Excel</h3></div>
                  <div class="col-sm-12">
                      <div class="row">
                        <input name="import_file" type="file" placeholder="Upload excel" class="" value="{{old('import_file')}}" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"/>
                      </div>
                      <div class="row">
                        <p><small><a href="{{ asset('uploads/excel/sample.xlsx') }}">View</a> sample file for excel file formate. </small></p>
                      </div>
                  </div>
                </div>
              </div>
            </div>

          </div>
          <div class="previous-participants">
            <div class="padding-25"></div>
            <div class="row">
              <div class="col-sm-12 text-center">
                <button type="button" class="btn btn-success btn-add-new-user" data-type="plus">
                    <span class="glyphicon glyphicon-plus"></span>
                </button>
              </div>
            </div> 
          </div>

          <div class="row">
                <div class="col-sm-12">
                  <div class="">
                    <input type="submit" value="Next" class="btn btn-primary mt-30 mb-30" />
                  </div>
                </div>
            </div>
          
          {!! Form::close() !!}
@endsection

@section('footer-scripts')
    @include('layouts.partials.datatable')
    <script src="{{ asset('/js/google-map-script.js') }}"></script>
    <script src="{{ asset('/js/custome/select-deselect-script.js') }}"></script>
    <script src="{{ asset('/js/invite-participant.js') }}"></script>
    <script src="{{ asset('js/cookie.js') }}"></script>

    <script>
        Cookies.set("radioButtonValues", null, { path: '/' });
    </script>

@endsection
