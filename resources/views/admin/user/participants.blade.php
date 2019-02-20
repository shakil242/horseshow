@extends('admin.layouts.app')


@section('main-content')
        <div class="row">
            <div class="col-sm-8">
              <h1>Participants Users for {{GetTemplateName($template_id)}}</h1>
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
                {!! Breadcrumbs::render('admin-users-participants',$template_id) !!}
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
                          <th>Email</th>
                          <th>Invited On</th>
                          <th>Assets</th>
                          <!-- <th style="width:22%">Type</th> -->
                        </tr>
                    </thead>
                    <tbody>

                        @if(sizeof($collection)>0)
                            @foreach($collection as $user)
                                <?php 
                                $serial = $loop->index + 1; 
                                $roleName = '';
                                ?>
                                <tr>
                                    <td>{{ $serial }}</td>
                                    <td><strong class="visible-xs">Name</strong>{{ $user['name'] }}</td>
                                    <td><strong class="visible-xs">Email</strong>{{  $user['email'] }}</td>
                                    <td><strong class="visible-xs">Created On</strong>{{  getDates($user['created_at']) }}</td>
                                    <td><strong class="visible-xs">Master-Templates</strong>{{  GetAssetNamefromId($user['asset_id']) }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>


          <div class="buttons-holder bt-padit">
            
           <!--  <a class="btn-lg btn-primary btn-d" href="{{URL::to('/admin/create-template')}}">CREATE TEMPLATE</a>
           -->
            <!-- <div class="pager">
              <a class="page-left" href="#"><i class="fa fa-angle-left" aria-hidden="true"></i></a>
              <span>Next Page</span>
              <a class="page-right" href="#"><i class="fa fa-angle-right" aria-hidden="true"></i></a>
            </div> -->
          </div>
    
@endsection
@section('footer-scripts')
    @include('admin.layouts.partials.datatable')
@endsection