@extends('layouts.equetica2')

@section('custom-htmlheader')
    <!-- Search populate select multiple-->
    <script src="{{ asset('/js/vender/bootstrap3-typeahead.min.js') }}"></script>
    <!-- END:Search populate select multiple-->
@endsection

@section('main-content')

<!-- ================= CONTENT AREA ================== -->
<div class="main-contents">
    <div class="container-fluid">

        <div class="page-menu">
            
            <div class="row">
                <div class="col left-panel">
                    <div class="d-flex flex-nowrap">
                    <span class="menu-icon mr-15 align-self-start" >
                        <img src="{{asset('img/icons/icon-page-common.svg') }}" />
                    </span>
                    <h1 class="title flex-shrink-1">All Employees
                        <small>{!! Breadcrumbs::render('master-template-employee-manager',$template_id,$app_id) !!}</small>
                    </h1>
                    </div>
                </div>
                <div class="right-panel">
                    <div class="desktop-view">
                        <form class="form-inline justify-content-end">
                         <!--    <button class="btn btn-sm btn-primary btn-rounded mr-10" type="button">Trake Price</button>
                            <button class="btn btn-sm btn-primary btn-rounded mr-10" type="button">Export All Assets</button>
                             -->
                            <div class="search-field mr-10">
                                <div class="input-group">
                                <input type="text" class="form-control" placeholder="" id="myInputTextField" aria-label="Username" aria-describedby="basic-addon1">
                                <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><img src="{{asset('img/icons/icon-search.svg') }}" /></span>
                                </div>
                                </div>
                            </div>
                          </form>
                    </div>
                    <div class="mobile-view">
                        <span class="menu-icon mr-15" data-toggle="collapse" href="#collapseMoreAction" role="button" aria-expanded="false" aria-controls="collapseMoreAction">
<!--                            <i class="fa fa-expand"></i>-->
                            <i class="fa fa-navicon"></i>
                        </span>
                        
                        <div class="collapse navbar-collapse-responsive navbar-collapse justify-content-end" id="navbarSupportedContent">
                            <form class="form-inline justify-content-end">
                            <!-- <button class="btn btn-sm btn-primary btn-rounded mr-10" type="button">Trake Price</button>
                            <button class="btn btn-sm btn-primary btn-rounded mr-10" type="button">Export All Assets</button>
                             -->
                            <div class="search-field">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="myInputTextField" placeholder="" aria-label="Username" aria-describedby="basic-addon1">
                                <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><img src="{{asset('img/icons/icon-search.svg') }}" /></span>
                                </div>
                                </div>
                            </div>
                          </form>

                          </div>
                    </div>
                </div>
            </div>

            <div class="collapse-box menu-holder">
                
                <div class="collapse menu-box MobileViewRightPanel" id="collapseMoreAction">
                    <span class="close-menu" data-toggle="collapse" href="#collapseMoreAction" role="button" aria-expanded="false" aria-controls="collapseMoreAction">
                        <img src="{{asset('img/icons/icon-close.svg') }}" />
                    </span>
                    <div class="menu-links">
                        <div class="row">
                            <!-- col-md-6  -->
                            <div class="col-md-6 mb-10">
                                <form class="form-inline justify-content-end">
                                <div class="search-field">
                                    <div class="input-group">
                                    <input type="text" class="form-control" placeholder="" aria-label="Username" aria-describedby="basic-addon1">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1"><img src="{{asset('img/icons/icon-search.svg') }}" /></span>
                                    </div>
                                    </div>
                                </div>
                                </form>  
                            </div>
                            <!-- /.col-md-6  -->
                            <!-- col-md-6  -->
                           <!--  <div class="col-md-6 text-center-sm">
                                <button class="btn btn-sm btn-primary btn-rounded mr-10 mb-10" type="button">Trake Price</button>
                                <button class="btn btn-sm btn-primary btn-rounded mr-10 mb-10" type="button">Export All Assets</button>
                            </div> -->
                            <!-- /.col-md-6  -->

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Panel -->
        <div class="white-board">

            <div class="row">
                <div class="info text-center col-md-12 mt-10">
                    @if(Session::has('message'))
                        <div class="alert {{ Session::get('alert-class', 'alert-success') }}" role="alert">
                            {{ Session::get('message') }}
                        </div>
                    @endif
                </div>
            </div>


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
            <div class="row" style="padding: 10px 15px 0px 0px">

                <a data-toggle="modal" data-target=".employeePermission"  style="float:right" class="btn btn-secondary" href="javascript:" >Add Employee</a>

            </div>
            <div class="row">
                    <div class="col-md-12">
                            
                        <!-- TAB CONTENT -->
                        <div class="tab-content" id="myTabContent">
                            <!-- Tab Data Divisions -->
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="division-tab">
                                <div class="table-responsive">
                                    <table class="table table-line-braker mt-10 custom-responsive-md " id="crudTable9">
                                        <thead class="hidden-xs">
                                                <tr>
                                                    <th style="width:5%">#</th>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Designation</th>
                                                    <th>Permissions</th>
                                                    <th>Created On</th>
                                                    <th class="action">Action</th>

                                                    <!-- <th style="width:22%">Type</th> -->
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php $ya_fields = getButtonLabelFromTemplateId($template_id,'ya_fields');

                                                ?>

                                                @if(sizeof($collection)>0)
                                                    @foreach($collection as $employee)
                                                        <?php

                                                        $permissions = [];
                                                        $serial = $loop->index + 1;
                                                        $roleName = '';
                                                        if(!is_null($employee->permissions))
                                                            $permissions =  json_decode($employee->permissions);


                                                       // exit;
                                                        ?>
                                                        <tr>
                                                            <td>{{ $serial }}</td>
                                                            <td>{{ $employee->name }}</td>
                                                            <td>{{  $employee->email }}</td>
                                                            <td>{{  $employee->designation }}</td>

                                                            <td>

                                                                @if(is_array($permissions))
                                                                    <?php $str = ''; ?>
                                                                    @foreach(config('empPermissions.empRights') as $key=>$value)
                                                                        <?php
                                                                        $labelArray = explode('|',$value);
                                                                         $label = post_value_or($ya_fields,$labelArray[1],$labelArray[0]);
                                                                         $str .= in_array($key,$permissions)? $label.',<br/>':''
                                                                        ?>
                                                                    @endforeach
                                                                @endif
                                                                <?php echo substr(trim($str), 0, -6); ?>
                                                            </td>
                                                            <td>{{  getDates($employee->created_at) }}</td>

                                                            <td class="action">
                                                                <div class="TD-left">
                                                                        <a onclick="return confirm('Are you sure?')" href="{{URL::to('employee') }}/delete/{{nxb_encode($employee->id)}}" data-original-title="Delete Employee" data-placement="top" data-toggle="tooltip">
                                                                        <i aria-hidden="true" class="fa fa-trash-o"></i></a>
                                                                     <a  onclick="viewEmployee({{$employee->id}})" data-original-title="Edit Assets"
                                                                        data-placement="top" data-toggle="tooltip" href="javascript:"><i aria-hidden="true" class="fa fa-pencil"></i></a>
                                                                    <a onclick="viewEmployee({{$employee->id}})" data-original-title="View Employee Data" data-placement="top" data-toggle="tooltip" href="javascript:">
                                                                        <i aria-hidden="true" class="fa fa-eye"></i></a>

                                                                    @if($employee->status==1)
                                                                        <a data-original-title="Block User" data-placement="top" data-toggle="tooltip" onclick="return confirm('Are you sure you want to take back privileges?')" title="Take Back Privileges" href="{{URL::to('employee') }}/updateStatus/{{nxb_encode($employee->id)}}/0">
                                                                             <i aria-hidden="true" title="Take Back Privileges" class="fa fa-close"></i>
                                                                        </a>
                                                                    @else
                                                                        <a data-original-title="Un-Block User" data-placement="top" data-toggle="tooltip" onclick="return confirm('Are you sure?')" href="{{URL::to('employee') }}/updateStatus/{{nxb_encode($employee->id)}}/1">
                                                                            <i aria-hidden="true" class="fa fa-check"></i>
                                                                        </a>
                                                                    @endif

                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                <tr><td colspan="5" style="text-align: center">No Employee Exist</td></tr>
                                                @endif
                                                </tbody>
                                    </table>
                                </div>
                                <!-- PAGINATION -->
                                <div class="">

                                    {{-- {{$suppliesOrders->links('layouts.pagination')}} --}}
                                </div>
                                <!-- ./ PAGINATION -->
                                
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

    <div class="modal fade bs-example-modal-sm  employeePermission" tabindex="-1" role="dialog"
         aria-labelledby="myLargeModalLabel">


        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="exampleModalLabel">Enter Employee Details</h2>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">

                    <form method="post" action="{{URL::to('employee') }}/addEmployee" name="addEmployee" id="addEmployee">
                        <div class="row" style="margin-top: 40px;">
                            {{csrf_field()}}
                            <div class="col-sm-12">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="account_number">Name:</label>
                                        <input type="text" class="form-control" id="name"  value="{{old('name')}}" required name="name">
                                    </div>
                                    <div class="form-group">
                                        <label for="account_number">Email:</label>
                                        <input type="text" class="form-control" value="{{old('email')}}" id="email" required name="email">
                                        {!! $errors->first('email', '<span style="color:#a94442" class="help-block text-danger">:message</span>') !!}
                                    </div>

                                    <div class="form-group">
                                        <label for="account_number">Job Status:</label>
                                        <input type="text" class="form-control"  value="{{old('designation')}}" id="designation"  name="designation">
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-2" style="padding-left: 0px;">
                                            <strong>Permissions:</strong>
                                        </div>
                                        <div class="col-sm-4 col-sm-offset-0">
                                            <label>
                                                <input type="checkbox" id="checkall">
                                                <span>Check All</span>
                                            </label>
                                       </div>
                                    </div>
                                    <div class="row">


                                    <?php $cc = 0;


                                        ?>
                                    @foreach(config('empPermissions.empRights') as $key=>$value)
                                    <?php
                                   if( $cc%2 == 0)
                                    echo '</div><div class="row" style="margin-bottom: 10px;">';
                                            $cc = $cc + 1;
                                          $labelArray = explode('|',$value);
                                            ?>
                                        <div class="col-md-6">
                                       <label style="font-weight: normal" class="form-check-label">
                                            <input class="check form-check-input" id="permissions-{{$key}}" @if($errors->any()) {{in_array($key,old('permissions'))?'checked':''}}  @endif type="checkbox" name="permissions[]" value="{{$key}}"> 
                                            <span>{{post_value_or($ya_fields,$labelArray[1],$labelArray[0])}}</span>
                                        </label>
                                       </div>
                                        @endforeach
                                    </div>
                                </div>
                                <hr>

                            </div>

                            <input type="hidden" name="template_id" value="{{$template_id}}">
                            <input type="hidden" name="app_id" value="{{$app_id}}">


                            <input type="hidden" id="employee_id" name="employee_id" value="">


                            <div class="col-md-4">
                                <input type="submit" name="submit" class="btn btn-secondary btn-close" value="Submit">
                            </div>
                            <div class="col-md-4">

                            <button data-dismiss="modal" class="btn btn-primary btn-close cancel" type="button">Cancel</button>
                            </div>

                        </div>
                    </form>

                </div>


            </div>
        </div>
    </div>



@endsection
@section('footer-scripts')
    @include('layouts.partials.datatable')
    <script type="text/javascript" src="{{asset('/js/employee.js')}}"></script>
    @if($errors->any())
        <script>
            $(".employeePermission").modal("show");
        </script>
    @endif
@endsection
