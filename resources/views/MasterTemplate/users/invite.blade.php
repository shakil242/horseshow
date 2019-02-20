@extends('layouts.equetica2')


@section('main-content')

@if(Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
@endif

     <!-- ================= CONTENT AREA ================== -->
<div class="main-contents">
    <div class="container-fluid">
        @php 
          $ya_fields = getButtonLabelFromTemplateId($template_id,'ya_fields');
          $title = post_value_or($ya_fields,'invite_users','Invite Users');
          $added_subtitle =Breadcrumbs::render('master-template-participants',$template_id);
        @endphp
        @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle,'remove_search'=>1])

        <!-- Content Panel -->
        <div class="white-board">            
            <div class="row">
                <div class="col">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <!--<li class="nav-item">
                                <a class="nav-link active" id="division-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Orders</a>
                            </li>
                             <li class="nav-item">
                                <a class="nav-link" id="showclasses-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Show Classes</a>
                            </li> -->
                        </ul>
                    </div>

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
                                      
                                    
                                    {!! Form::open(['url'=>'master-template/invite/users/send/','method'=>'post','files'=>true,'class'=>'form-horizontal dropzone targetvalue']) !!}
                                      <div class="row">
                                      <input type="hidden" name="template_id" value="{{$template_id}}">
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
                                                  <input name="import_file" type="file" placeholder="Upload excel" class="" value="{{old('import_file')}}" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"/>
                                                </div>
                                                <div class="row">
                                                  <p><small><a href="{{ asset('uploads/excel/sample.xlsx') }}">View</a> sample file for excel file formate. </small></p>
                                                </div>
                                            </div>
                                          </div>
                                        </div>
                                      </div>

                                      <div class="previous-participants">
                                          <div class="row">
                                            <div class="col-sm-12 text-center">
                                              <button type="button" class="btn btn-success btn-add-new-user" data-type="plus">
                                                  <span class="fa fa-plus"></span>
                                              </button>
                                            </div>
                                          </div> 
                                      </div>
                                      <div class="padding-10"></div>

                                      <!-- Invite -->
                                       <!-- Historry of associated -->
                                        <div class="new-participants">

                                            
                                              <div class="col-sm-6">
                                                <br />
                                                <h2>Invite to Master Templates</h2>
                                                <br />
                                                <div class="form-group">
                                                  @if($associated)
                                                  <div class="col-xs-5">
                                                    <select name="invited_master_template[]" data-live-search="true" class="selectpicker width-long" multiple>
                                                      <option value="{{$template_id}}">{{GetTemplateName($template_id)}}</option>
                                                      @foreach($associated as $templat)
                                                        <option value="{{$templat->id}}">{{$templat->name}}</option>
                                                      @endforeach
                                                    </select>
                                                  </div>
                                                  @endif
                                                </div>
                                              </div>

                                       <div class="col-sm-12">
                                                    <br />
                                                    <div class="row">
                                                        <div class="col-sm-1">
                                                            <a href="{{route('user.dashboard')}}" class="btn btn-defualt btn-close"> Close </a>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <div class="">
                                                                <input type="submit" value="Invite Users " class="btn btn-primary submitVals" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                    
                                  </div>
                                  {!! Form::close() !!}
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
    <script src="{{ asset('/js/custome/select-deselect-script.js') }}"></script>
@endsection
