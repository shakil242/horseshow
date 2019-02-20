@extends('layouts.equetica2')

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
                    <h1 class="title flex-shrink-1">Add / Scratch Entries
                        <small></small>
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
           <!--  <div class="row">
                <div class="col-md-12">
                    <button type="button" class="btn btn-info pull-right" data-toggle="modal" data-target="#myModal">Send Email</button>
                </div>
            </div> -->
            <div class="row">
                    <div class="col-md-12">
                            
                        <!-- TAB CONTENT -->
                        <div class="tab-content" id="myTabContent">
                            <!-- Tab Data Divisions -->
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="division-tab">
                                <div class="table-responsive">
                                    <table class="table table-line-braker mt-10 custom-responsive-md dataViews" id="">
                                        <thead class="hidden-xs">
                                            <tr>
                                                <th style="width:5%">#</th>
                                                <th>User Name</th>
                                                <th>Show Name</th>
                                                <th>Amount</th>
                                                <th>Registered On</th>
                                                <th class="action">Action</th>

                                                <!-- <th style="width:22%">Type</th> -->
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @php
                                            $emailUsers = [];
                                            @endphp
                                            @if(sizeof($registerList)>0)
                                                @foreach($registerList as $row)
                                                    <?php
                                                    
                                                    $manageRegister =  $row->ManageShowRegister;
                                                    ?>
                                                    @foreach($manageRegister as $presponse)
                                                    <?php $serial = $loop->index + 1;
                                                    $emailUsers[] = getUserEmailfromid($presponse->user_id);
                                                     ?>
                                                    <tr>
                                                        <td>{{ $serial }}</td>
                                                        <td>{{ getUserNamefromid($presponse->user_id) }}</td>
                                                        <td>{{ $row->title }}</td>
                                                        
                                                        <td>($){{  $presponse->total_price }}</td>
                                                        <td>{{  getDates($presponse->created_at) }}</td>
                                                        <td class="action">
                                                            <a href="{{url('shows')}}/trainer/rider-detail/{{nxb_encode($presponse->id)}}"  data-original-title="View details" data-placement="top" data-toggle="tooltip" >
                                                                <i aria-hidden="true" class="fa fa-eye"></i>
                                                            </a>
                                                             
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                @endforeach
                                            @else
                                                <tr><td colspan="5" style="text-align: center">No Entry Exist</td></tr>
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
                            
                            <!-- Tab Data Show Classes -->
                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="showclasses-tab">
                                showclasses
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
        <script src="{{ asset('/js/vender/tinymce/tinymce.min.js') }}"></script>
        <script>tinymce.init({ selector:'textarea' });</script>
        <!-- Modal -->
         <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade" style="display: none;">
             <div class="modal-dialog">
                 <div class="modal-content">
                     <div class="modal-header">
                         <h4 class="modal-title">Compose</h4>
                         <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                     </div>
                     <div class="modal-body">
                           {!! Form::open(['route' => 'send-marketing-email', 'files' => true,'class'=>"form-horizontal"]) !!}

                             <div class="form-group adding-extras">
                                 <!-- <label class="col-lg-2 control-label">Cc / Bcc</label> -->
                                @php $emailResult = array_unique($emailUsers); @endphp
                                @foreach($emailResult as $key => $emails)
                                 <div class="row col-lg-12 container-extra">
                                   <div class="col-lg-11">
                                       <input name="model_to[]" type="email" value="{{$emails}}" placeholder="To" class="form-control">
                                   </div>
                                   <div class="col-lg-1">
                                    @if($key == 0)
                                        <button type="button" class="btn btn-xs add-cc-bcc"><span class="glyphicon glyphicon-plus"></span></button>
                                    @else
                                        <button type="button" class="btn btn-xs remove-cc-bcc"><span class="fa fa-minus"></span></button>
                                    @endif
                                  </div>
                                </div>
                                @endforeach

                             </div>
                             <div class="form-group">
                                 <!-- <label class="col-lg-2 control-label">Subject</label> -->
                                 <div class="col-lg-12">
                                     <input name="model_subject" required type="text" placeholder="Subject"  class="form-control">
                                 </div>
                             </div>
                             <div class="form-group">
                                 <!-- <label class="col-lg-2 control-label">Message</label> -->
                                 <div class="col-lg-12">
                                     <textarea name="model_body" rows="10" cols="60" class="form-control" placeholder="Enter Message"></textarea>
                                 </div>
                             </div>

                             <div class="form-group">
                                 <div class="col-lg-10">
                                     <span class="btn btn-small btn-success green fileinput-button">
                                       <i class="fa fa-plus fa fa-white"></i>
                                       <span>Attachment</span>
                                       <input name="uplaod_attachment[]" class="uploadedfiles" type="file" multiple="multiple">
                                     </span>

                                     <button class="btn btn-send" type="submit">Send</button>
                                 </div>
                                 <div class="col-lg-10">
                                    <div class="upload_prev"></div>
                                 </div>
                             </div>
                        {!! Form::close() !!}
                     </div>
                 </div><!-- /.modal-content -->
             </div><!-- /.modal-dialog -->
         </div><!-- /.modal -->

@endsection
@section('footer-scripts')
    <script src="{{ asset('/js/marketing-email.js') }}"></script>
    @include('layouts.partials.datatable')
@endsection
