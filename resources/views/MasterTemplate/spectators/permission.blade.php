@extends('layouts.equetica2')

@section('custom-htmlheader')
  <!-- Search populate select multiple-->
 <script src="{{ asset('/js/vender/bootstrap3-typeahead.min.js') }}"></script>
  <!-- END:Search populate select multiple-->
@endsection

@section('main-content')
        <div class="row">
          <div class="col-sm-8">
             <h1>Set Permission</h1>
          </div>
        </div>
      	<div class="row">
	        <div class="col-sm-12">
		          {!! Breadcrumbs::render('master-template-participants',$data['template_id']) !!}
	    	  </div>
      	</div>
          @include('admin.layouts.errors')

                  <!--- Invite participants -->
            </div>


            <div class="invite-participants-table">
              <h2>Modules Access</h2>
              <div class="participants-filters">
                <div class="row">
                  <!-- <label class="col-sm-4 permission-labels"><input type="checkbox" /> Master Scehduler </label>
                  <label class="col-sm-3 permission-labels"><input type="checkbox" /> Feedback </label>
                   --><div class="col-sm-5 action-holder">
                    
                      <div class="search-form">
                        <input type="text" placeholder="Search By Name" id="myInputTextField">
                        <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                      </div>
                    
                  </div>
                </div>
              </div>
            <br />
            
            @if($forms)
            <table id="crudTable2" class="primary-table permission">
              <thead class="hidden-xs">
                <tr>
                  <th>Form Name</th>
                  <th class="short-one">Give Access</th>
                </tr>
              </thead>
              <tbody>
                @foreach($forms as $form)
                <tr>
                  <td>
                    <storng class="visible-xs">Form Name</storng>
                    {{$forms["name"]}}
                  </td>
                  <td>
                    <label><input type="checkbox" /></label>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
            @else
            <div class="row">
              <div class="col-lg-5 col-md-5 col-sm-6">{{MASTER_TEMPLATE_NO_MODULES_TEXT}}</div>
            </div>
            @endif
          </div>
          
          <!-- Historry of associated -->
          <div class="new-participants">

            <div class="row">
              <div class="col-sm-6">
                <br />
                <h2>Invite to Master Templates</h2>
                <p> (Optional)</p>
                <br />
                <div class="form-group">
                  @if($associated)
                  <select name="invited_master_template">
                  <option value="">Please Select</option>
                  <option value="{{$data['template_id']}}">{{GetTemplateName($data['template_id'])}}</option>
                  @foreach($associated as $templat)
                    <option value="{{$templat->id}}">{{$templat->name}}</option>
                  @endforeach
                  </select>
                  @endif
                </div>
              </div>
              <div class="col-sm-12">
                <br />

                <div class="row">
                  <div class="col-sm-4">
                    <input type="submit" class="btn btn-lg btn-primary submitVals" value="INVITE" />
                  </div>
                  <div class="col-sm-4">
                    <input type="submit" class="btn btn-lg btn-defualt" value="CLOSE" />
                  </div>
                </div>
              </div>
            </div>
          </div>
          {!! Form::close() !!}
      	 <!-- <div class="row">
      		<div class="col-lg-5 col-md-5 col-sm-6">You have not added any asset for this template yet!</div>
      	 </div> -->

   
		
@endsection

@section('footer-scripts')
    @include('layouts.partials.datatable')
    <script src="{{ asset('/js/google-map-script.js') }}"></script>
@endsection
