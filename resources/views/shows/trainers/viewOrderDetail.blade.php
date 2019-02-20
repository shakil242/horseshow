@extends('layouts.equetica2')

@section('custom-htmlheader')
    @include('layouts.partials.form-header')
@endsection

@section('main-content')
    <!-- ================= CONTENT AREA ================== -->
    <div class="main-contents">
        <div class="container-fluid">

        @php
            $title = "Order Detail View";
            if($orderType==2)
            $added_subtitle =  Breadcrumbs::render('show-supplies-order-details',["template_id"=>nxb_encode($suppliesOrders->template_id)]);
            else
             $added_subtitle = '';
        @endphp
        @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle])

        <!-- Content Panel -->
            <div class="white-board">

    <div class="row">
        <div class="info">
            @if(Session::has('message'))
                <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
            @endif
        </div>
    </div>
    <div class="">
        <div class="col-sm-12">
            <a href="{{URL::to('master-template') }}/ExportOrderSupplies/{{nxb_encode($order_id)}}/{{$orderType}}" class="btn btn-secondary"> Export As PDF </a>
        </div>
    </div>



    <div class="row ml-0">
        <div class="col-sm-12">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="row ml-0">
             <div class="col-md-12">
                 <label style=""><strong>Order As:</strong></label>
                <label>
                    @if($suppliesOrders->ordered_as == 1)
                        Trainer
                    @else
                        Rider
                    @endif
                    
                </label>
            </div>
            </div>
            @if($orderType==2)
            <div class="col-md-12">
                <label style=""><strong>@if($suppliesOrders->ordered_as == 1)   Trainer : @else Rider: @endif</strong></label>
                <label>{{getUserNamefromid($suppliesOrders->trainer_user_id)}}</label>
            </div>
            @endif
           
            @if(count($suppliesOrders->orderSupplie)>0)
           <div class="row ml-0">
            <div class="col-md-12">
                <label style=""><strong>Horses Selected:</strong></label>
                @foreach($suppliesOrders->orderSupplie as $secIndx => $selectedary)
                     
                    <label>
                        @if($secIndx!=0) , @endif
                    {!! getHorseNameFromHorseShowId($selectedary->horse_id,$suppliesOrders->show_id) !!} </label>
                @endforeach
            </div>
           </div>
            @endif
                    {!! Form::open(['url'=>'shows/trainer/order/supplies','method'=>'post','files'=>true,'class'=>'form-horizontal dropzone targetvalue']) !!}
                    <input type="hidden" name="step2" value="1">
                    <input type="hidden" name="show_id" value="{{$suppliesOrders->show_id}}">
                    <input type="hidden"  name="order_id" value="{{$suppliesOrders->id}}">
                    <input type="hidden" name="ordered_as" value="{{$suppliesOrders->ordered_as}}">

                    <div id="indivisuals" class="tab-pane">
                        <div class="row ml-0">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Order Title</strong></label>
                            <input  name="order_title" readonly class="form-control-inline col-md-10" value="{{$suppliesOrders->order_title}}">
                        </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label style=""><strong>Update Order Status</strong></label>
                            <select name="status" class="form-control-inline col-md-6 selectpicker">
                                <option value="">Select Order Status</option>
                                <option {{ ($suppliesOrders->status==0)?'selected':''  }} value="0">Open</option>
                                <option {{ ($suppliesOrders->status==1)?'selected':''  }} value="1">Closed</option>
                            </select>
                        </div>
                        </div>
                        </div>
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-line-braker mt-10 custom-responsive-md " id="crudTable3">
                                <thead class="hidden-xs">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Title</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Requested Quantity</th>
                                    <th scope="col">Completed Quantity</th>
                                    <th scope="col">Price x Qty</th>
                                    <th scope="col">Stable and Stall Location</th>

                                    <th class="action">Actions</th>
                                </tr>
                                </thead>
                                <tbody>

                                @if(sizeof($suppliesOrders)>0)

                                        <?php
                                        $roleName = '';
                                        $additional_fields = json_decode($suppliesOrders->additional_fields,true);
                                       //print_r(array_values($additional_fields));
                                        for($i=0;$i<count($additional_fields);$i++)
                                        {
                                        $status ='';

                                        ?>
                                        @if(isset($additional_fields[$i]['id']))
                                           <?php

                                           if(isset($additional_fields[$i]['approveQty']))
                                           {
                                               $appQuantity = $additional_fields[$i]['approveQty'];
                                           }
                                           else
                                           {
                                               $appQuantity = $additional_fields[$i]['qty'];
                                           }
                                           if($appQuantity == ""){
                                                $appQuantity =0;
                                           }
                                           if(isset($additional_fields[$i]['status']))
                                           {
                                              $status = $additional_fields[$i]['status'];
                                           }

                                           ?>

                                            <tr class="tr-row additiona-charges-row">
                                            <td>{{ $i+1 }}</td>
                                            <td><span class="table-title">Title</span>{{AdditionalCharge($additional_fields[$i]['id'],2)}}</td>
                                            <td><span class="table-title">Description</span>{{ AdditionalCharge($additional_fields[$i]['id'],1)}}</td>
                                            <td><span class="table-title">Price</span>
                                                @if(isset($additional_fields[$i]['price']))
                                                    ${{$additional_fields[$i]['price'] }}
                                                    <input type="hidden" name="additional[{{$i}}][price]"  value="{{$additional_fields[$i]['price']}}">
                                                @else No Price Set. @endif
                                            </td>
                                            <td>
                                                <span class="table-title">Quantity</span>
                                                @if($orderType==1)
                                                    {{$additional_fields[$i]['qty']}}
                                                @else
                                                <input readonly type="number" name="additional[{{$i}}][qty]" class="col-sm-8 numberQty requestedQty-{{$i}}" min="1" max="999" value="{{$additional_fields[$i]['qty']}}" placeholder="qty" data-unit-price="{{$additional_fields[$i]['price']}}"></td>
                                                @endif
                                                <td><span class="table-title">Approve Quantity</span>
                                                    @if($orderType==1)
                                                     {{$appQuantity}}
                                                    @else
                                                    <input type="number" name="additional[{{$i}}][approveQty]" class="col-sm-8 approvedNumberQty qty-{{$i}}" min="" max="999" value="{{$appQuantity}}" data-id="{{$i}}" placeholder="Approve Qty" data-unit-price="{{$additional_fields[$i]['price']}}">
                                                    @endif


                                                </td>

                                                <td><span class="table-title">additional Price</span>
                                                    @if(isset($additional_fields[$i]['price']))
                                                        <div class="priceinqty">${{ $additional_fields[$i]['price']*$appQuantity }}</div>
                                                    @else No Price Set. @endif</td>

                                            <td><strong class="table-title">Stall Location</strong>
                                            @php 
                                                $destination=NULL;
                                               
                                                if(isset($additional_fields[$i]['destination_stall'])){
                                                    $destination = $additional_fields[$i]['destination_stall'];
                                                }
                                            @endphp
                                                @if(count($destination)>0)
                                                    @foreach($destination as $indx => $stall)
                                                        @if($indx!=0) , @endif
                                                        {{$stall}}
                                                        <input type="hidden" name="additional[{{$i}}][destination_stall][{{$indx}}]" value="{{$stall}}">
                                                    @endforeach
                                                @else
                                                @endif
                                            </td>

                                             <td class="action">
                                                <span class="table-title">Actions</span>
                                               <div>
                                                    
                                                   @if($orderType==1)
                                                       <strong style="text-transform: capitalize">{{$status}}</strong>
                                                   @else
                                                        <select  class="form-control orderStatus" onchange="updateStatus($(this),'{{$i}}')" name="additional[{{$i}}][status]">
                                                            <option @if($status=='completed') selected="selected" @else {{ '' }} @endif  value="completed">Completed</option>
                                                            <option @if($status=='pending') selected="selected" @else {{ '' }} @endif  value="pending">Pending</option>
                                                            <option  @if($status=='rejected') selected="selected" @else {{ '' }} @endif value="rejected">Rejected</option>
                                                        </select>
                                                 @endif
                                               </div>
                                                <input name="additional[{{$i}}][id]" class="checkbox-additional" data-attr="additional-charges" type="hidden" value="{{$additional_fields[$i]['id']}}">
                                            </td>
                                        </tr>
                                         @endif
                                       <?php } ?>

                                @endif
                                </tbody>
                            </table>
                        </div>
                            <div class="col-md-12">
                            <div class="Totals row">
                                <div class="col-md-6">
                                    <strong>Trainer Comments<br></strong>
                                        @if($suppliesOrders->trainer_comments!='')
                                            <span>{{$suppliesOrders->trainer_comments}}</span>
                                        @else
                                            <span>No Comments here</span>
                                        @endif
                                <div class="mt-20">
                                    <strong>Show Management Comments <br></strong>
                                    @if($orderType==1)
                                        @if(!is_null($suppliesOrders->show_owner_comments))
                                            <span>{{$suppliesOrders->show_owner_comments}}</span>
                                        @else
                                            <span>No Comments here</span>
                                        @endif
                                    @else
                                    <textarea name="show_owner_comments"  class="form-control" placeholder="Please enter comments here">{{$suppliesOrders->show_owner_comments}}</textarea>
                                    @endif
                                </div>

                                </div>
                                <div class="col-sm-6">
                                    <div class="row">
                                    <div class="col-sm-6 border-bottom"><b> Total Amount: </b></div>
                                    <div class="col-sm-6 addAdditionalPrice border-bottom">($) {{$suppliesOrders->total_amount}}
                                        <input type="hidden" class="additionalPrice" name="additional_price" value="{{$suppliesOrders->total_amount}}"></div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <input type="hidden"  name="supplierId" value="{{$suppliesOrders->trainer_user_id}}">

                    @if($orderType==2)
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="submit" value="Update Status" class="btn btn-large btn-success">
                        </div>
                    </div>
                    <br>
                    @endif
                    {!! Form::close() !!}

        </div>
    </div>
    <!-- Tab containing all the data tables -->


@endsection

@section('footer-scripts')
    <script src="{{ asset('/js/shows/classes-pricing.js') }}"></script>
    <script src="{{ asset('/js/custom-function.js') }}"></script>

    @include('layouts.partials.datatable')

    <style>

        label {
            text-transform: uppercase;
            float: left;
            padding-left: 0px;
            margin-left: 0px;
            margin-right: 10px;
            line-height: 35px;
        }
        .form-control1 {
            display: block;
            width: 90%;
            height: 34px;
            padding: 6px 12px;
            font-size: 14px;
            line-height: 1.42857143;
            color: #555;
            background-color: #fff;
            background-image: none;
            /*border: 1px solid #ccc;*/
            border-radius: 4px;
            -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,0.075);
            box-shadow: inset 0 1px 1px rgba(0,0,0,0.075);
            -webkit-transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
            -o-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
            transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
            float: left;
        }

    </style>
    
@endsection