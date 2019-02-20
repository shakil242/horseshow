@extends('layouts.equetica2')
@section('main-content')
@if(Session::has('message'))
<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
@endif

     <!-- ================= CONTENT AREA ================== -->
<div class="main-contents">
    <div class="container-fluid">
        @php 
          $title = getShowName($show_id);
          $added_subtitle = Breadcrumbs::render('shows-view-stall-request');
        @endphp
        @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle])

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
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="division-tab">
                              @include('admin.layouts.errors')
                             
                                    <!-- Accordion START -->
                                    @if($showStallRequest->count()>0)
                                      <div class="offset-md-10" style="">
                                        <a href="#viewRemainingStables" onclick="getRemainigStalls({{$show_id}})" style="color: #FFF; border-radius: 5px;" data-toggle="modal" class="btn btn-success">View Available Stalls</a>
                                    </div>


                                    <div class="display-success alert alert-success" style="display: none">
                                        Stall request has been responded successfully</div>
                                    <div class="row">

                                    <div class="col-sm-12">

                                    <table class="table table-line-braker mt-10 custom-responsive-md dataViews" id="">
                                        <thead class="hidden-xs">
                                        <tr>
                                            <th style="width:4%">#</th>
                                            <th style="width:15%">Name</th>
                                            <th style="width:15%">Stall Type</th>
                                            <th style="width:25%">Requested Quantity</th>
                                            <th  style="width:25%">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $serial=0; ?>
                                        @foreach($showStallRequest as $pResponse)
                                        <?php $serial = $loop->index + 1; ?>
                                                <tr>
                                                    <td>{{ $serial }}</td>
                                                    <td>{{$pResponse->user->name}}</td>
                                                    <td><strong class="visible-xs">Stall Type</strong>{{$pResponse->stallType->stall_type}}</td>
                                                    <td><strong class="visible-xs">Requested Quantity</strong>{{$pResponse->quantity}}</td>
                                                    <td class="viewResponseCon-{{$pResponse->id}}">

                                                        @if($pResponse->status!=0)
                                                            {!! getViewResponseData($pResponse->id) !!}
                                                         @endif

                                                        @if($pResponse->status==0)
                                                        <form action="javascript:"  onsubmit="submitRequestResponse($(this),'{{$pResponse->id}}')" id="requestResponse-{{$pResponse->id}}" name="form{{$pResponse->id}}" method="post">
                                                         <input type="hidden" value="{{$pResponse->stall_type_id}}" name="stall_type_id">

                                                            <div class="col-sm-12 row">
                                                            <div class="col-sm-1" style="margin-top: 4px; padding-right: 0px; margin-left: -5px;"> <strong></strong> </div>
                                                            <div class="col-sm-2" style="padding-left: 0px;">
                                                            <label>
                                                                <input id="{{$pResponse->id}}" class="approve-{{$pResponse->id}} appCheck"  type="checkbox" value="1" name="approve">
                                                                <span>Approve</span>
                                                            </label> 
                                                            </div>   
                                                            <div class="col-sm-1" style="margin-top: 4px; padding-left:10px;padding-right: 0px;"> <strong></strong> </div>
                                                            <div class="col-sm-2" style="padding-left: 6px;">
                                                            <label>
                                                                <input id="{{$pResponse->id}}" class="reject-{{$pResponse->id}} rejCheck" type="checkbox" value="2" name="approve">
                                                                <span>Reject</span>
                                                            </label>
                                                            </div>
                                                        </div>
                                                        <div class="stallContainer-{{$pResponse->id}} hide">
                                                            <div class="stallsMessage-{{$pResponse->id}} col-sm-12 mt-10" style="color: red"></div>
                                                            <div class="col-sm-12 mt-15">
                                                            <div class="row">

                                                        <div class="col-sm-3"  style="margin-top: 6px;"> <strong>Stable :</strong> </div>

                                                            <div class="col-sm-5">
                                                            <select name="stable[{{$pResponse->id}}]">
                                                            @foreach($stableCollection as $r)
                                                            <option value="{{$r->id}}">{{$r->name}}</option>
                                                            @endforeach
                                                        </select>
                                                            </div>
                                                        </div>
                                                        </div>
                                                        <div class="col-sm-12" style="margin-top: 5px;">
                                                            <div class="row">
                                                            <div class="col-sm-3" style="margin-top: 6px;">
                                                                <strong>Stall No :</strong>
                                                            </div>

                                                            @for($j=0; $j < $pResponse->quantity; $j++)
                                                             <div class="col-sm-7 @if($j>0) offset-md-3 @endif" style="margin-bottom: 5px;">
                                                                <input style="width: 70%" required type="text" class="form-group stallFileds" name="stallNumber[{{$pResponse->id}}][]">
                                                            </div>
                                                            @endfor
                                                            </div>
                                                        </div>
                                                            <div class="col-sm-12" style="margin-top: 5px;">
                                                                <div class="col-sm-7 offset-md-3"> <button class="btn btn-sm btn-success">Submit</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                            <div class="comments-{{$pResponse->id}} hide mt-15">

                                                                <div class="col-sm-8">
                                                                <textarea class="form-control"  placeholder="Enter Comments" name="comments"></textarea>

                                                                </div>

                                                                <div class="col-sm-12" style="margin-top: 5px;">
                                                                    <button style="border-radius: 5px;" class="btn btn-success">Submit</button>
                                                                </div>

                                                                </div>


                                                        </form>
                                                        @endif
                                                    </td>
                                                </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    </div>
                                    </div>
                                                

                                       
                                    @else
                                        <div class="panel panel-default">
                                            <label>No Request added yet!</label>
                                        </div>
                                    @endif
                               

                                <div id="viewRemainingStables" class="modal fade" role="dialog">
                                    <div class="modal-dialog">
                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Remaining Stalls</h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">

                                                    <div class="col-sm-12">

                                                        <table class="table table-line-braker mt-10 custom-responsive-md dataViews" id="">

                                                        <thead class="hidden-xs">
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Stable Name</th>
                                                            <th>Number Of Stalls</th>
                                                            <th>Remaining</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody class="remainingStalls">
                                                        @foreach($stableCollection as $pResponse)
                                                            <?php $serial = $loop->index + 1;
                                                            $show_id = $pResponse->show_id;
                                                            ?>
                                                            <tr>
                                                                <td>{{ $serial }}</td>
                                                                <td><strong class="visible-xs">Stable Name</strong>{{$pResponse->name}}</td>
                                                                <td><strong class="visible-xs">Number Of Stalls</strong>{!! getStallTypes($pResponse->stall_types) !!}</td>
                                                                <td><strong class="visible-xs">Remaining</strong>{!! getRemainingStallTypes($pResponse->id,$pResponse->stall_types) !!}</td>

                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>

                                                </div>
    </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                </div>

                                            </div>
                                        </div>


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
    @include('layouts.partials.datatable')
    <link href="{{ asset('/css/addMoreCollapse.css') }}" rel="stylesheet" />
    <script src="{{ asset('/js/stable.js') }}"></script>


@endsection