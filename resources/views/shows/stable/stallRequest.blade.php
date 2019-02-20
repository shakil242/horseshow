@extends('layouts.equetica2')
@section('main-content')


    <div class="container-fluid">
        <div class="page-menu">
            <div class="row">
                <div class="col left-panel">
                    <div class="d-flex flex-nowrap">
                    <span class="menu-icon mr-15 align-self-start" >
                        <img src="{{asset('img/icons/icon-page-common.svg') }}" />
                    </span>
                        <h1 class="title flex-shrink-1">{{getShowName($show_id)}}
                            <small>{!! Breadcrumbs::render('shows-stall-request') !!}</small>
                        </h1>
                    </div>
                </div>
                <div class="right-panel">
                    <div class="desktop-view">
                        <form class="form-inline justify-content-end">
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
                            <i class="fa fa-navicon"></i>
                        </span>

                        <div class="collapse navbar-collapse-responsive navbar-collapse justify-content-end" id="navbarSupportedContent">
                            <form class="form-inline justify-content-end">
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

                        </div>
                    </div>
                </div>
            </div>
        </div>

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

        <!-- Accordion START -->
            <div class="row mb-30 ml-30">

                @if(count($collection)<=0)
                    <div class="col-md-12">
                        <div class="text-center">No Stall Type added yet!</div>
                    </div>
                @else
                {!! Form::open(['url'=>'/shows/submitStallRequest','method'=>'post','class'=>'form-horizontal']) !!}
                <div class="row">

                <input type="hidden" value="{{$show_id}}" name="show_id">
                     @foreach($collection as $row)
                        <?php $serial = $loop->index + 1;?>
                        <div class="col-md-12 assignContainer ml-0 pl-0">
                            <div class="row">
                            <div class="col-md-3" >
                               <fieldset class="form-group">
                                   <label class="text-content-dark" for=""><strong>{{$row->stall_type}}</strong></label>
                                   <input name="quantity[{{$row->id}}]" class="form-control-inline border-bottom pull-right quantity form-control-bb-only" id="" placeholder="Enter Quantity" type="text">
                               </fieldset>
                            </div>
                             @if($row->is_utility==0)
                            <div class="col-xs-2 mt-10">
                            <strong>Assign</strong>
                            </div>
                             @endif
                            @if($row->is_utility==0)
                                <div class="fieldsContainer col-md-7">
                                <div class="row recordCon">

                                <div class="col-md-4">
                                    <fieldset class="form-group select-bottom-line-only">
                                    <select name="assign[{{$row->id}}][riders][]" class="form-control form-control-bb-only assign" onchange="getHorses($(this),'{{$show_id}}')">
                                    <option value="">Select Rider</option>
                                    @foreach($userArr as $k=>$v)
                                            <option value="{{$k}}">{{$v}}</option>
                                    @endforeach
                                </select>
                                    </fieldset>
                            </div>
                            <div class="col-md-4">
                                <fieldset class="form-group select-bottom-line-only">
                                <select  name="assign[{{$row->id}}][horses][]" class="form-control form-control-bb-only assign horseContainer">
                                    <option value="">-- Horse --</option>
                                </select>
                                </fieldset>
                            </div>
                            <div class="col-md-2">
                            <button type="button" style="padding:0px 5px; margin-top: 6px; margin-left: 8px;" class="btn btn-default addButton1">
                                <i class="fa fa-plus"></i></button>
                                <button type="button" style="padding:0px 5px; margin-top: 6px; margin-left: 8px;" onclick="removeCurrent($(this))" class="btn hide btn-default removeButton">
                                    <i class="fa fa-minus"></i></button>
                            </div>
                            </div>
                            </div>
                            @endif
                            <hr>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="row">
                <div class="col-md-6 text-left pl-0">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
    {!! Form::close() !!}

                    <div class="col-md-6">
                            <a style="color:#FFFFFF;" class="btn btn-primary divide-utility-stalls pull-right" data-toggle="modal" data-target="#divideUtilityStall">Add Utility Stall Charges on Invoice</a>
                            @include('shows.stable.partials.modal', [
                                 'modalData' => [
                                     'title' => 'Add Stall Utility Charges for Horse(s)',
                                     'id' => 'divideUtilityStall',
                                     "theLooper" => $HRS,
                                     "labeltitle" => 'Select horse(s) that utility stalls will be split between and add to invoice',
                                     'status' => UNPAID,
                                     'url' => "shows/stalls/add-utility-stall",
                                   ],
                                 ])
                    </div>
                </div>
                </div>

                    {{--<div class="row">--}}
                    {{--<div class="col-xs-12 text-right">--}}
                      {{--<button class="btn btn-primary divide-utility-stalls pull-right" data-toggle="modal" data-target="#divideUtilityStall">Add Utility Charges on Invoice</button>--}}
                       {{--@include('shows.stable.partials.modal', [--}}
                            {{--'modalData' => [--}}
                                {{--'title' => 'Add Utility Charges for horses',--}}
                                {{--'id' => 'divideUtilityStall',--}}
                                {{--"theLooper" => $HRS,--}}
                                {{--"labeltitle" => 'Select Horses',--}}
                                {{--'status' => UNPAID,--}}
                                {{--'url' => "shows/stalls/add-utility-stall",--}}
                              {{--],--}}
                            {{--])--}}
                    {{--</div>--}}
                    {{--</div>--}}
                        <div class="table-responsive">
                            <div class="display-success alert alert-success" style="display: none">
                                Horses has been associated with Stall numbers successfully</div>
                            <table class="table table-line-braker mt-10 custom-responsive-md " id="crudTable9">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Stall Type</th>
                                    <th  scope="col">Requested Quantity</th>
                                    <th  scope="col">Stable</th>
                                    <th  scope="col">Stall Number</th>
                                    <th  scope="col">Requested On</th>
                                    <th  scope="col">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php($serial=0)
                                @foreach($showStallRequest as $pResponse)

                                    @php($serial = $loop->index + 1)
                                    <tr>
                                        <td>{{ $serial }}</td>
                                        <td>{{$pResponse->stallType->stall_type}}</td>
                                        <td>{{$pResponse->quantity}}</td>
                                        <td>@if($pResponse->stable){{$pResponse->stable->name}}@endif</td>
                                        <td>
                                        @if ($pResponse->stall_number != "")
                                        <?php
                                        $stalls = [];
                                        $stall_number_arr = explode(',', $pResponse->stall_number);
                                        ?>
                                        @if(count($stall_number_arr)>0)
                                            @foreach($stall_number_arr as $st)
                                            <?php $stalls[] = $st;
                                             if($st!='') {
                                            ?>
                                            # {{$st}}<br>
                                            <?php } ?>
                                            @endforeach
                                        @endif
                                        @endif

                                        </td>
                                        <td>{{getDates($pResponse->created_at)}}</td>
                                        <td>
                                            <form action="javascript:"  onsubmit="stallRequest($(this),'{{$pResponse->id}}')" id="requestResponse-{{$pResponse->id}}" name="form{{$pResponse->id}}" method="post">
                                                <input type="hidden" name="stall_type_id" value="{{$pResponse->stallType->id}}">
                                                <input type="hidden" name="show_id" value="{{$show_id}}">
                                                <input type="hidden" name="stall_request_id" value="{{$pResponse->id}}">


                                                @if($pResponse->stall_number!='' && $pResponse->status==1 && $pResponse->is_utility==0)
                                                    <div class="col-dm-12 asignRiders-{{$pResponse->id}}">

                                                        {!! getstallSavedValues($pResponse->id,$userArr,$pResponse->stallHorse) !!}

                                                        @if($pResponse->stallHorse->count()>0 && $pResponse->status==0)
                                                            <div class="fieldsContainer col-xs-12" style="padding: 0px;">
                                                                <div class="row">
                                                                <div class="col-md-4" style="padding: 0px;">
                                                                    <select  style="padding: 0px; width: 80%" name="riders[{{$pResponse->id}}][]" class="form-control assign" onchange="getHorses($(this),'{{$show_id}}')">
                                                                        <option value="">Rider</option>
                                                                        @foreach($userArr as $k=>$v)
                                                                            <option value="{{$k}}">{{$v}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-4" style="padding: 0px;">
                                                                    <select  style="padding: 0px; width: 80%" name="horses[{{$pResponse->id}}][]" class="form-control assign horseContainer">
                                                                        <option value="">Horse</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-3" style="padding: 0px;">
                                                                    <select  style="padding: 0px; width: 80%" name="stallNumber[{{$pResponse->id}}][]" class="form-control assign">
                                                                        <option value="">Stalls</option>
                                                                        @foreach($stalls as $st)
                                                                            <option value="{{$st}}">{{$st}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-1" style="padding: 0px;">
                                                                    <button type="button" style="padding:0px 5px; margin-top: 6px; margin-left: 8px;" class="btn btn-default addUtility" data-id="{{$pResponse->id}}" data-quantity="{{$pResponse->quantity}}">
                                                                        <i class="fa fa-plus"></i></button>
                                                                    <button type="button" style="padding:0px 5px; margin-top: 6px; margin-left: 8px;" onclick="removeCurrent($(this))" class="btn hide btn-default removeButton">
                                                                        <i class="fa fa-minus"></i></button>
                                                                </div>
                                                                </div>
                                                                <div class="col-sm-12" style="margin-top: 5px; padding-left: 0px;">
                                                                    <button style="border-radius: 5px;" class="btn btn-success">Save</button>
                                                                </div>
                                                            </div>

                                                        @endif

                                                    </div>
                                                @elseif($pResponse->status==2)
                                                    <div class="col-dm-12">
                                                        <div class="row">
                                                        <div class="col-sm-4"><strong>Status</strong></div>
                                                        <div class="col-sm-8">Rejected</div>
                                                       </div>
                                                    <div class="col-dm-12">
                                                        <div class="row">
                                                        <div class="col-sm-4"><strong>Comments</strong></div>
                                                        <div class="col-sm-8">{{$pResponse->comments}}</div>
                                                        </div>

                                                    </div>
                                                @else
                                                    Pending
                                                @endif

                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>


                @endif
            </div>
        </div>
    </div>
       @endsection

       @section('footer-scripts')
           @include('layouts.partials.datatable')
           <link href="{{ asset('/css/addMoreCollapse.css') }}" rel="stylesheet" />
           <script src="{{ asset('/js/stable.js') }}"></script>

       <style>
           .marginClass{ margin-top: 10px;}

       </style>

       @endsection