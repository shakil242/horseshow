@extends('layouts.equetica2')

@section('custom-htmlheader')
  <!-- Search populate select multiple-->
 <script src="{{ asset('/js/vender/bootstrap3-typeahead.min.js') }}"></script>
  <!-- END:Search populate select multiple-->
@endsection

@section('main-content')
        <div class="row">
          <div class="col-sm-8">
            <?php $ya_fields = getButtonLabelFromTemplateId($template_id,'ya_fields'); ?>
            <h1>{{post_value_or($ya_fields,'invite_spectator','Invite Spectators')}}</h1>
          </div>
        </div>
      	<div class="row">
	        <div class="col-sm-12">
		          {!! Breadcrumbs::render('master-template-participants',$template_id) !!}
	    	  </div>
      	</div>
          @include('admin.layouts.errors')

                  <!--- Invite participants -->
          {!! Form::open(['url'=>'master-template/invite/spectators/','files'=> true,'method'=>'post','class'=>'form-horizontal dropzone targetvalue']) !!}
            <input type="hidden" name="template_id" value="{{$template_id}}">
            <div class="row" style="padding: 20px;">

                <div class="invite-participants-table">

                    <div class="new-participants">
                        <div class="row" style="margin-bottom: 20px;">

                            <h3 style="padding-left: 15px">Spectator Detail</h3>
                            <!-- Multiple users add -->
                            <div class="">
                                  <div class="col-sm-7 add-more-participants-fields">
                                  @if(old('emailName'))
                                    <?php $indexer = 0 ?>
                                    @foreach(old('emailName') as $emailNameVal)
                                      <div class="row number-emails">
                                        <div class="col-sm-5">
                                          <div class="">
                                            <input name="emailName[{{$indexer}}][name]" type="text" placeholder="Name" class="form-control" value="{{$emailNameVal['name']}}"/>
                                          </div>
                                        </div>
                                        <div class="col-sm-5">
                                          <div class="">
                                            <input name="emailName[{{$indexer}}][email]" type="email" placeholder="Email" class="form-control" value="{{$emailNameVal['email']}}"/>
                                          </div>
                                        </div>
                                        <div class='col-sm-2'>
                                          <div class='pull-left'>
                                          <button type='button' class='close remove-this-entry' aria-label='Close'>
                                            <span aria-hidden='true'>&times;</span>
                                          </button>
                                          </div>
                                        </div>
                                      </div>
                                      <?php $indexer = $indexer+1; ?>
                                    @endforeach
                                  @else
                                  <div class="row number-emails">
                                  <div class="col-sm-5">
                                    <div class="">
                                      <input name="emailName[0][name]" type="text" placeholder="Name" class="form-control" value="{{old('name')}}"/>
                                    </div>
                                  </div>
                                  <div class="col-sm-5">
                                    <div class="">
                                      <input name="emailName[0][email]" type="email" placeholder="Email" class="form-control" value="{{old('email')}}"/>
                                    </div>
                                  </div>
                                  </div>
                                  @endif
                                  </div>
                                  <div class="col-sm-1"> <h1> OR </h1></div>
                                  <div class="col-sm-4">
                                    <div class="excel-participants">
                                      <div style="padding-left:5px"><h3>Import via Excel</h3></div>
                                      <div class="col-sm-12">
                                          <div class="row">
                                            <input name="import_file" type="file" placeholder="Upload excel" class="form-control" value="{{old('import_file')}}" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"/>
                                          </div>
                                          <div class="row">
                                            <p><small><a href="{{ asset('uploads/excel/sample.xlsx') }}">View</a> sample file for excel file formate. </small></p>
                                          </div>
                                      </div>
                                    </div>
                                  </div>

                                
                                </div>
                                
                        </div>
                    </div>
                    <div class="previous-participants">
                        <div class="row">
                          <div class="col-sm-12 text-center">
                            <button type="button" class="btn btn-success btn-add-new-user" data-type="plus">
                                <span class="glyphicon glyphicon-plus"></span>
                            </button>
                          </div>
                        </div> 
                    </div>
                    <div class="padding-10"></div>

                @if($forms)
                        <table id="crudTable2" class="primary-table permission">
                            <thead class="hidden-xs">
                            <tr>
                                <th>Scheduler Module Name</th>
                                <th class="short-one">Give Access   <span><input class="all" type="checkbox" id="checkForm"> </span></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($forms as $form)
                                <tr>
                                    <td>
                                        <storng class="visible-xs">Form Name</storng>
                                        {{$form["name"]}}
                                    </td>
                                    <td>
                                        <label><input type="checkbox" value="{{$form["id"]}}" name="form_id[]" class="checkSpectators" /></label>
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
                                        <option value="{{$template_id}}">{{GetTemplateName($template_id)}}</option>
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
                                    <a href="{{route('user.dashboard')}}" class="btn btn-lg btn-defualt btn-close"> Close </a>
                                </div>
                                <div class="col-sm-4">
                                    <div class="">
                                        <input type="submit" value="Invite Spectators" class="btn btn-lg btn-primary" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}


            </div>



      	 <!-- <div class="row">
      		<div class="col-lg-5 col-md-5 col-sm-6">You have not added any asset for this template yet!</div>
      	 </div> -->
		
@endsection



@section('footer-scripts')


    <script src="{{ asset('/js/custome/select-deselect-script.js') }}"></script>
@endsection
