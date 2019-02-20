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
                    <h1 class="title flex-shrink-1">Users for <strong>{{getTemplateName($template_id)}}</strong>
                        <small>{!! Breadcrumbs::render('mastertemplate-list-users',nxb_encode($template_id)) !!}</small>
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
                                                <th style="width: 20%">Email</th>
                                                <th style="width: 10%">Allowed to Submit</th>
                                                <th>Asset</th>
                                                <th>Created On</th>
                                                <th style="width:10%">Status</th>
                                                <th class="action">Action</th>
                                              </tr>
                                          </thead>
                                          <tbody>

                                              @if(sizeof($collection)>0)
                                                  @foreach($collection as $invite)
                                                      <?php 
                                                      $serial = $loop->index + 1; 
                                                      $roleName = '';
                                                      ?>
                                                      <tr>
                                                          <td>{{ $serial }}</td>
                                                          <td>{{ $invite->name }}</td>
                                                          <td>{{ $invite->email }}</td>
                                                          <td>{{ $invite->allowed_time}} Time(s)</td>
                                                          <td>@if(isset($invite->asset)) {{ GetAssetName($invite->asset) }} @endif</td>
                                                          <td>{{ getDates($invite->created_at) }}</td>
                                                          <td>{{ EmailStatus($invite->status) }}</td>
                                                          <td class="action">
                                                            @if($invite->block == 0)
                                                              
                                                              <a data-original-title="Block This Invite" data-placement="top" class="btn-block-inviteduser" data-toggle="tooltip" onclick="return confirm('Are you sure?')" href="{{URL::to('/master-template')}}/{{nxb_encode($invite->id)}}/block-invite-user">
                                                                             <i aria-hidden="true" class="fa fa-trash"></i>
                                                              </a>
                                                            @else
                                                             <a data-original-title="Un-Block This Invite" data-placement="top" data-toggle="tooltip" onclick="return confirm('Are you sure?')" href="{{URL::to('/master-template')}}/{{nxb_encode($invite->id)}}/unblock-invite-user" class="btn-unblock-inviteduser">
                                                                  <i aria-hidden="true" class="fa fa-check"></i>
                                                             </a>
                                                             @endif
                                                          </td>
                                                      </tr>
                                                  @endforeach
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

    
@endsection
@section('footer-scripts')
      @include('layouts.partials.datatable')
@endsection