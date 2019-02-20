@extends('admin.layouts.app')

@section('htmlheader_title')
    Log in
@endsection

@section('main-content')
          <div class="row">
            <div class="col-sm-6">
              <h1>Edit Master Templates</h1>
            </div>
            <div class="col-sm-6 action-holder">
              <!-- <a href="#" class="btn-action">Upload Like Icon</a> -->
              <a href="{{URL::to('admin/master-template-design/create') }}/{{$template->id}}" class="btn-action">Design App</a>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-8">
                {!! Breadcrumbs::render('admin-edit-m-template',$template->id) !!}
            </div>
          </div>
          <div class="row">
            <div class="info">
                @if(Session::has('message'))
                <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
            </div>
          </div>
                    <!--- create template panel -->
          <div>
                  @if (count($errors) > 0)

                    <div class="box box-solid box-danger">
                   <!--  <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                     --><ul>
                      @foreach ($errors->all() as $error)
                        <li style="color:#DD4B39">{{ $error }}</li>
                      @endforeach
                    </ul>

                    </div>
                  @endif
                  
          </div>

          <div class="gray-box">
            <div class="create-template">
            {!! Form::model($template , ['method'=>'PATCH', 'action'=>['admin\AdminController@update', $template->id]]) !!}
                  @include('admin.mastertemplates._form', ['targets'=>$collection,'associates' => $associate_array,'template' => $template,'btn_title'=>'Update' ])
            </div>
          </div>
              <div class="row">
            <div class="col-sm-6">
              <h1>Modules</h1>
            </div>
            <div class="col-sm-6 action-holder">
              <a href="{{URL::to('admin/modules-managment/reset-launcher') }}/{{$template->id}}" class="btn-action">Reset Launcher</a>
              <a href="{{URL::to('admin/modules-managment/create') }}/{{$template->id}}" class="btn-action">Add Module</a>
            </div>
          </div>
          <div class="module-holer innerdatatable">
            <table id="crudTable_modals" class="primary-table">
              <thead class="hidden-xs">
                <tr>
                  <th style="width:5%"> #</th>
                  <th>Name</th>
                  <th>Created On</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>

                        @if(sizeof($modules_collection)>0)
                            @foreach($modules_collection as $modules)
                                <?php 
                                $serial = $loop->index + 1; 
                                $roleName = '';
                                ?>
                                <tr>
                                    <td>{{ $serial }}</td>
                                    <td><strong class="visible-xs">Name</strong>{{ $modules['name'] }}</td>
                                    <td><strong class="visible-xs">Created On</strong>{{  getDates($modules['created_at']) }}</td>
                                     <td>
                                          <strong class="visible-xs">Actions</strong>
                                           <label>
                                           <input type="radio" name="module_launch_radio" value="{{ $modules['id'] }}" <?php echo ($template->module_launch_id == $modules['id'] ?  'checked="checked"' : ''); ?> />Launcher</label>
                                          <a href="{{URL::to('admin/modules-managment/delete') }}/{{ $modules['id'] }}" onclick="return confirm('Are you sure you want to delete it?');" data-toggle="tooltip" data-placement="top" title="Delete Module"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                          <a href="{{URL::to('admin/modules-managment') }}/{{ $modules['id'] }}/edit " data-toggle="tooltip" data-placement="top" title="Edit Module"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                      </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
             </table>
          </div>
          <!--- Forms list -->
          <div class="row">
            <div class="col-sm-6">
              <h1>Forms</h1>
            </div>
            <div class="col-sm-6 action-holder">
              <a href="{{URL::to('admin/forms-managment/create') }}/{{$template->id}}" class="btn-action">Add Forms</a>
            </div>
          </div>
          <div class="module-holer">
            
            <table id="crudTable_forms" class="primary-table">
              <thead class="hidden-xs">
                <tr>
                  <th>Name</th>
                  <th>Type</th>
                    <th>Link To</th>
                  <th>Associated Modules</th>
                  <th>Created On</th>
                  <th class="short">Actions</th>
                </tr>
              </thead>
              <tbody>
                        @if(sizeof($forms_collection)>0)
                            @foreach($forms_collection as $forms)
                                <?php 
                                $serial = $loop->index + 1; 
                                $roleName = '';
                                ?>
                                <tr>
                                    <td><strong class="visible-xs">Name</strong>{{ $forms['name'] }}</td>
                                    <td>
                                    <strong class="visible-xs">Type</strong>{{ $forms['formtypes']['name'] }}</td>

                                    <td>
                                        <strong class="visible-xs">Link To</strong>
                                        @if($forms['invoice']!=0) Invoice @endif
                                        @if($forms['invoice']!=0 && $forms['scheduler']==1 ) , @endif
                                        @if($forms['scheduler']==1) Scheduler @endif
                                    </td>


                                    <td>@if($forms['moduleAttached']['name'])
                                        {{$forms['moduleAttached']['name'] }} 
                                       @else
                                        No Module Attached
                                       @endif
                                    </td>
                                    <td><strong class="visible-xs">Created On</strong>{{ getDates($forms['created_at']) }}</td>
                                     <td>
                                          <strong class="visible-xs">Actions</strong>
                                           <label>
                                          <a target="_blank" href="{{URL::to('admin/template') }}/{{ nxb_encode($forms['id']) }}/preview" data-toggle="tooltip" data-placement="top" title="Preview form" data-original-title="Preview Form"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                          <a href="{{URL::to('admin/forms-managment/') }}/{{ $forms['id'] }}/delete" onclick="return confirm('Are you sure you want to delete it?');" data-toggle="tooltip" data-placement="top" title="Delete Form"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                          <a href="{{URL::to('admin/forms-managment') }}/{{ $forms['id'] }}/edit " data-toggle="tooltip" data-placement="top" title="Edit Form"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                      </td>
                                </tr>
                            @endforeach
                        @endif
              </tbody>
            </table>
          </div>
          <?php if($template->module_launch_id == ""){ $templauchval=0;}else{ $templauchval=$template->module_launch_id;} ?>
          <input type="hidden" name="module_launch_id" id="module_launch_hidden" value="{{$templauchval}}">
          <input type="hidden" name="actionafterstore" id="afterstore" value="">
          <div class="buttons-holder">
           {!! Form::submit("Save And Close" , ['class' =>"btn btn-lg btn-primary",'id'=>'storeandlist']) !!} 
           {!! Form::submit("Save" , ['class' =>"btn btn-lg btn-success",'id'=>'storeonly']) !!} 
            <a  href="{{URL::to('/admin')}}" class="btn btn-lg btn-default" value="Cancel">Cancel</a>
          </div>
           {!! Form::close() !!}


@endsection
@section('footer-scripts')
    @include('admin.layouts.partials.datatable')
@endsection