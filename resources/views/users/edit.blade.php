@extends('layouts.equetica2')

@section('custom-htmlheader')
<link href="{{ asset('/adminstyle/css/vender/bootstrap-colorpicker.css') }}" rel="stylesheet">
@endsection

@section('main-content')

@if(Session::has('message'))
<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
@endif

     <!-- ================= CONTENT AREA ================== -->
<div class="main-contents">
    <div class="container-fluid">
        @php 
          $title = "Edit your app";
          $added_subtitle = Breadcrumbs::render('template-user-settings',$template_id);
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
                            <div class="row box-shadow bg-white p-4 mb-30" id="home" role="tabpanel" aria-labelledby="division-tab">
                                <div class="col-sm-12">
                                  @if(!$apps->count())
                                    
                                    <div class="row">
                                      <div class="col-lg-5 col-md-5 col-sm-6">No template attached!</div>
                                    </div>
                                  @else
                                    <div class="row">
                                      <div class="col-sm-8">
                                        <h3>App name</h3>
                                      </div>
                                    </div>
                                    <div class="col-sm-12 padding-25">
                                    {!! Form::open(['url'=>'master-template/setting/name/edit','method'=>'post','class'=>'form-horizontal']) !!}
                                      <input type="hidden" name="invited_user_id" value="{{$invited_user_id}}">
                                      <input type="hidden" name="template_id" value="{{$template_id}}">
                                      <input type="hidden" name="invite_templatenames_id" value="{{$invite_templatenames_id}}">
                                      <div class="row form-group">
                                      <div class="col-sm-1"> <label class="col-sm-2 control-label" style="color:#651E1C">Name</label></div>
                                      <div class="col-sm-8"><input type="text" name="template_name" value="{{ $name }}" class="form-control" required="required"> </div>
                                      <div class="col-sm-2"> {!! Form::submit("Save" , ['class' =>"btn btn-secondary btn-close"]) !!}    </div>
                                      </div>
                                    {!! Form::close() !!}
                                    </div>
                                  @endif

                                  <div class="row">
                                      <div class="col-sm-8">
                                        <h3>Transfer your App</h3>
                                      </div>
                                  </div>
                                  <div class="col-sm-12 padding-25">
                                    {!! Form::open(['url'=>'master-template/setting/transfer','method'=>'post','class'=>'form-horizontal']) !!}
                                      <input type="hidden" name="template_id" value="{{$template_id}}">
                                      <input type="hidden" name="invited_user_id" value="{{$invited_user_id}}">
                                      <input type="hidden" name="invited_id" value="{{$invited_id}}">
                                      <div class="row form-group">
                                        <div class="col-sm-1"> <label class="col-sm-2 control-label" style="color:#651E1C">email</label></div>
                                        @if($transferedReq) 
                                          <div class="col-sm-4"><input type="email" placeholder="name@example.com" name="transferemail" value="{{$transferedReq->invite_email}}" disabled="disabled" class="form-control" required="required"> </div>
                                          <div class="col-sm-4"><textarea name="notes" class="form-control" placeholder="Add Message or notes here" disabled="disabled">{{$transferedReq->notes}}</textarea> </div>
                                          <div class="col-sm-2"><p>Waiting for approvel from {{$transferedReq->invite_email}}</p></div>
                                        @else 
                                          <div class="col-sm-4"><input type="email" placeholder="name@example.com" name="transferemail" class="form-control" required="required"> </div>
                                          <div class="col-sm-4"><textarea name="notes" class="form-control" placeholder="Add Message or notes here"></textarea> </div>
                                          <div class="col-sm-2"> {!! Form::submit("Transfer" , ['class' =>"btn btn-secondary btn-close"]) !!}    </div>
                                        @endif
                                      </div>
                                    {!! Form::close() !!}
                                  </div>

                                    <div class="invite-participant-history">
                                        <table class="primary-table dataTableView">
                                        <thead class="hidden-xs">
                                          <tr>
                                            <th style="width:5%">#</th>
                                            <th>Transfered By</th>
                                            <th>Transfered to</th>
                                            <th>Date of transfer</th>
                                            <th>Notes</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                          @if($transferedHistory != null) 
                                            @foreach($transferedHistory as $hResponse)
                                            <?php $serial = $loop->index + 1; ?>
                                            <tr>
                                              <td>{{ $serial }}</td>
                                              <td><strong class="visible-xs">Transfered By</strong>{{ getUserNamefromid($hResponse->sender_id) }}</td>
                                              <td><strong class="visible-xs">Transfered to</strong>{{ $hResponse->invite_email }}</td>
                                              <td><strong class="visible-xs">Date of transfer</strong>{{ getDates($hResponse->updated_at) }}</td>
                                              <td><strong class="visible-xs">Notes</strong>{{  $hResponse->notes }}</td>
                                            </tr>
                                            @endforeach
                                          @else
                                          <td>
                                            <label>No History Associated yet!</label>
                                          </td>
                                      @endif
                                    </tbody>
                                  </table>
                                    </div>
                                </div>

                                @if($premitedToChange == TEMPLATE_DESIGN_CUSTOMIZABLE)
                                  <div class="design-template">
                                      <div class="col-12">
                                        <h1>Design Master Template</h1>
                                      
                                        @if (count($errors) > 0)
                                          <div class="box box-solid box-danger">
                                            <ul>
                                              @foreach ($errors->all() as $error)
                                                <li style="color:#DD4B39">{{ $error }}</li>
                                              @endforeach
                                            </ul>
                                          </div>
                                        @endif
                                        </div>
                                    @if (isset($design_template))
                                      {!! Form::model($design_template , ['method'=>'post','enctype'=>'multipart/form-data','action'=>['TemplateController@storeDesign', $design_template->id]]) !!}
                                      <input type="hidden" name="design_template_id" value="{{$design_template->id}}"> 
                                      @else
                                      {!! Form::open(['url'=>'master-template/setting/save/design','enctype'=>'multipart/form-data','method'=>'post']) !!}
                                      @endif
                                      <div class="col-8 customizable-label">
                                        
                                      </div>
                                              <!--- create template panel -->
                                      <div class="row">
                                          <input type="hidden" name="admin_design" value="{{$admin_design}}">
                                          <input type="hidden" name="invited_user_id" value="{{$invited_user_id}}">
                                            @include('users._form')

                                            <input type="hidden" name="template_id" value="{{ $template_id }}">
                                        <div class="desgin-buttons col-sm-12">
                                            {!! Form::submit('Save' , ['class' =>"btn btn-primary",'id'=>'storeonly']) !!} 
                                           
                                        </div>
                                    </div>
                                     {!! Form::close() !!}

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


        <!-- Tab containing all the data tables -->
   
		
@endsection

@section('footer-scripts')
    <script src="{{ asset('/adminstyle/js/vender/bootstrap-colorpicker.js') }}"></script>
    @include('layouts.partials.datatable')
@endsection
