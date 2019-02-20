@extends('admin.layouts.app')


@section('main-content')
        <div class="row">
            <div class="col-sm-8">
              <h1>Users for <strong style="color:#651e1c;font-family:Arial,sans,serif;">{{$template['name']}}</strong></h1>
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
                {!! Breadcrumbs::render('admin-template-users-view',$template['id']) !!}
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
                    <table id="crudTable2" class="table primary-table">
                    <thead class="hidden-xs">
                       <tr>
                          <th style="width:5%">#</th>
                          <th>Name</th>
                          <th style="width: 20%">Email</th>
                           <th style="width: 20%">Royalty</th>
                          <th>Created On</th>
                          <th style="width:10%">Status</th>
                          <th style="width:20%">Action</th>
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
                                    <td><strong class="visible-xs">Email</strong>{{  $template['email'] }}</td>
                                    <td><strong class="visible-xs">Royalty</strong>{{  $template['royalty'] }}</td>

                                    <td><strong class="visible-xs">Created On</strong>{{  getDates($template['created_at']) }}</td>
                                    <td><strong class="visible-xs">Status</strong>{{  EmailStatus($template['status']) }}</td>
                                    <td><strong class="visible-xs">Action</strong>
                                      @if($template['block'] == 0)
                                        <a href="{{URL::to('/admin/template')}}/{{$template['id']}}/block-user" class="btn-block-inviteduser"> Block User </a>
                                      @else
                                        <a href="{{URL::to('/admin/template')}}/{{$template['id']}}/unblock-user" class="btn-unblock-inviteduser"> Un-Block User </a>
                                      @endif
                                       <a href="{{URL::to('admin/'.getIdFromEmail($template['email']).'/'.$template['template_id'].'/participant/listings')}}" class="btn-participants-admin">Invited Participants</a> 
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>


          {{--<div class="buttons-holder bt-padit">--}}
            
            {{--<a class="btn-lg btn-primary btn-d" href="{{URL::to('/admin/create-template')}}">CREATE TEMPLATE</a>--}}
          
            <!-- <div class="pager">
              <a class="page-left" href="#"><i class="fa fa-angle-left" aria-hidden="true"></i></a>
              <span>Next Page</span>
              <a class="page-right" href="#"><i class="fa fa-angle-right" aria-hidden="true"></i></a>
            </div> -->
          {{--</div>--}}
    
@endsection
@section('footer-scripts')
    @include('admin.layouts.partials.datatable')
@endsection