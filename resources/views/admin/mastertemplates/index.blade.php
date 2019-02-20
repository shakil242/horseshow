@extends('admin.layouts.app')

@section('htmlheader_title')
    Log in
@endsection

@section('main-content')
        
        <div class="row">
            <div class="col-sm-8">
              <h1>Master Templates</h1>
            </div>
            <div class="col-sm-4 action-holder">
              <form action="#">
                <div class="search-form">
                  <input type="text" placeholder="Search By Name" id="myInputTextField"/>
                  <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                </div>
              </form>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-8">
                {!! Breadcrumbs::render('dashboard') !!}
            </div>
          </div>
          <div class="row">
            <div class="info">
                @if(Session::has('message'))
                <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
            </div>
          </div>

          <!--- template list -->
                <div class="module-holer rr-datatable">
                    <table id="crudTable2" class="table primary-table" >
                    <thead class="hidden-xs">
                       <tr>
                          <th style="width:5%">#</th>
                          <th>Name</th>
                          <th>Created On</th>
                          <th style="width:25%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>

                        @if(sizeof($collection)>0)
                            @foreach($collection as $template)
                                <?php 
                                $serial = $loop->index + 1; 
                                $roleName = '';
                                ?>
                                <tr>
                                    <td>{{ $serial }}</td>
                                    <td><strong class="visible-xs">Name</strong>{{ $template['name'] }}</td>
                                    <td><strong class="visible-xs">Created On</strong>{{  getDates($template['created_at']) }}</td>
                                     <td>
                                          <input type="hidden" class="royalty" value="{{ $template['royalty'] }}">
                                          <input type="hidden" class="heretemplateid" value="{{ $template['id'] }}">

                                          <strong class="visible-xs">Actions</strong>
                                          <a href="{{URL::to('admin/template/delete') }}/{{ $template['id'] }}" data-toggle="tooltip" data-placement="top" title="Delete Template" onclick="return confirm('Are you sure you want to delete? All the Forms and Modules associated with this master template will be deleted permanently');"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                          <a href="{{URL::to('admin/template/edit') }}/{{ $template['id'] }}" data-toggle="tooltip" data-placement="top" title="Edit Template"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                          <a class="invite-users" data-toggle="modal" data-target=".bs-example-modal-sm" href="#" data-toggle="tooltip" data-placement="top" title="Invite Users"><i class="fa fa-user-plus" aria-hidden="true"></i></a>
                                          <a href="{{URL::to('admin/template') }}/{{ $template['id'] }}/users" data-toggle="tooltip" data-placement="top" title="Manage Users"><i class="fa fa-cog" aria-hidden="true"></i></a>
                                          <a href="{{URL::to('admin/template') }}/{{ $template['id'] }}/template-buttons" data-toggle="tooltip" data-placement="top" title="Template button labels"><i class="fa fa-code" aria-hidden="true"></i></a>

                                          <a href="{{URL::to('master-template') }}/{{nxb_encode($template['id'])}}/modules/launch" data-toggle="tooltip" data-placement="top" title="View Master Template"><i class="fa fa-eye" aria-hidden="true"></i></a>

                                         <a  data-id="{{$template['id']}}" data-title="{{$template['name']}}" class="duplicate_template" data-toggle="modal" data-target=".duplicate_template_dialogue" href="#" data-toggle="tooltip" data-placement="top" title="Make a copy of this Template"><span class="glyphicon glyphicon-duplicate"></span>
                                         </a>



                                     </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>





          <div class="buttons-holder bt-padit">
            
            <a class="btn-lg btn-primary btn-d" href="{{URL::to('/admin/create-template')}}">CREATE TEMPLATE</a>
              <a class="btn-lg btn-primary btn-d" data-toggle="modal" data-target=".manage-registration" href="javascript:" data-toggle="tooltip">Manage Registration Templates</a>


            <!-- <div class="pager">
              <a class="page-left" href="#"><i class="fa fa-angle-left" aria-hidden="true"></i></a>
              <span>Next Page</span>
              <a class="page-right" href="#"><i class="fa fa-angle-right" aria-hidden="true"></i></a>
            </div> -->
          </div>
    
<!-- Modal for inviting users-->
<div class="modal duplicate_template_dialogue" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h2 class="modal-title" id="exampleModalLabel">Duplicate Template</h2>
      </div>
      {!! Form::open(['url'=>'admin/template/duplicateTemplate','method'=>'post']) !!}

      <div class="modal-body">
        <div class="invite-wrapper">
          <div class="invite-holder">


            <input type="hidden" name="template_id" value="" class="addtemplateid">
            <a class="btn-remove"><i class="fa fa-times" aria-hidden="true"></i></a>

              <div class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    <input name="template_name" required type="text" class="form-control template_name"  placeholder="Enter new Name">
                  </div>
                </div>
              </div>
          </div>
        </div>
        <div class="modal-buttons">
          <div class="row">
            <div class="col-sm-4">
              <input type="submit" value="Submit" class="btn btn-lg btn-primary" />
            </div>

          </div>
        </div>
      </div>
      {!! Form::close() !!}
    </div>
  </div>
</div>


        <div class="modal fade manage-registration" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
            <div class="modal-dialog  modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h2 class="modal-title" id="exampleModalLabel">Manage Registration Templates</h2>
                    </div>
                    {!! Form::open(['url'=>'admin/template/manageRegistration','method'=>'post','id'=>'test']) !!}

                    <div class="modal-body">

                        <div class="panel-body table-responsive">

                        <div class="module-holer rr-datatable" style="margin-top: 0px;">
                            <table  class="table primary-table display" style="border: none; margin-bottom: 0px;">
                                <thead class="hidden-xs">
                                <tr>
                                    <th style="width: 50px!important;">#</th>
                                    <th>Name</th>
                                    <th>Royalty</th>
                                    <th>Created On</th>
                                    <th>Enable Registration</th>

                                </tr>
                                </thead>
                                <tbody>

                                @if(sizeof($regisCollection)>0)
                                    @foreach($regisCollection as $template)
                                        <?php
                                        $serial = $loop->index + 1;
                                        $roleName = '';
                                        ?>

                                <tr>
                                    <td>{{$serial}}</td>
                                    <td><strong class="visible-xs">Name</strong>{{ $template['name'] }}</td>
                                    <td><strong class="visible-xs">Royalty</strong>{{  $template['royalty'] }}</td>
                                    <td><strong class="visible-xs">Created On</strong>{{  getDates($template['created_at']) }}</td>
                                    <td><label><input type="checkbox" {{ ($template['is_registration_on']==1)?'checked':'' }} name="enableRegistration[{{$template['id']}}]" ></label> </td>
                                </tr>
                                @endforeach
                                @endif
                                </tbody>
                            </table></div>
                    </div>
                        <div class="modal-buttons">
                            <div class="row">
                                <div class="col-sm-4">
                                    <input type="submit" value="Update" class="btn btn-lg btn-primary" />
                                </div>

                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>


        <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h2 class="modal-title" id="exampleModalLabel">Invite Users</h2>
                    </div>
                    {!! Form::open(['url'=>'admin/template/sendInvite','method'=>'post']) !!}

                    <div class="modal-body">
                        <div class="invite-wrapper">
                            <div class="invite-holder">
                                <input type="hidden" name="template_id" value="" class="addtemplateid">
                                <a class="btn-remove"><i class="fa fa-times" aria-hidden="true"></i></a>

                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <input name="template[1][name]" required type="text" class="form-control" id="user-n" placeholder="Enter Name">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <input name="template[1][email]" required type="email" data-error="Please enter a valid email address." class="form-control" id="user-em" placeholder="Enter Email">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <input name="template[1][royalty]" type="text" class="form-control add-royalty" id="user-em" placeholder="Royalty %">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-buttons">
                            <div class="row">
                                <div class="col-sm-4">
                                    <input type="submit" value="Invite" class="btn btn-lg btn-primary" />
                                </div>
                                <div class="col-sm-4">
                                    <input type="button" value="Invite More" class="btn btn-lg btn-success btn-invite-more" />
                                </div>
                                <div class="col-sm-4">
                                    <input type="button" value="Cancel" data-dismiss="modal" aria-label="Close" class="btn btn-lg btn-defualt" />
                                </div>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>


@endsection
@section('footer-scripts')
    @include('admin.layouts.partials.datatable')
    <script>
        $(function() {

            $(".display").dataTable({
                scrollResize: true,
                scrollY: 400,
                scrollCollapse: true,
                paging: false
            });

            $("form#test").submit(function( event ) {
                //event.preventDefault();
                $(".dataTables_filter .input-sm").val("");
                $(".dataTables_filter .input-sm").keyup();

            });

        });

    </script>
    <style>

       .display{ width: 812px!important;}
        .dataTables_filter {
            float: right !important;
        }
    </style>

@endsection