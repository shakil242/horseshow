    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <html>

    <head>

    <style>

        @page{

            height: auto;

        }

        h1 {
            margin:3px auto;
            font-size:18px;
            color:blue;
        }
        p {
            background:#fefeda;
            text-align:left;
        }
        a {
            border:1px dotted #01da02;
            font-size:16px;
            padding:4px;
        }
        table th
        {
            color: #FFF;
            font-size: 12px;
            background-color: #8593a3 !important;
            color: #FFF;
            padding: 11px 5px;
        }
        table td
        {
            color: #FFF;
            font-size: 12px;
            background-color: #ffffff !important;
            color: #333333;
            padding: 11px 5px;
            border:1px solid gray;
        }
        table{
            border-collapse: collapse;
            border:1px solid gray;
            background-color: #FFF;
            font-size: 16px;
            color: #39424C;
            width:100%;
        }
        body{
            margin: 0;
            padding: 0;
            font-family:arial;
            font-size: 18px;
        }

    </style>
    </head>

    <body>
    <div class="container">
    <div class="white-box">
        <div class="col-sm-12">
                @php $showNameContact = getShowNameContactAddress($suppliesOrders->show_id) @endphp
                <div style="text-aling:center">
                    <div style="text-align: center;"><div style=""><b>Show Name: </b>{{$showNameContact['title']}}</div></div>
                    <div style="text-align: center;"><div style=""><b>Show location:</b> {{$showNameContact['location']}}</div></div>
                    <div style="text-align: center;"><div style=""><b>Contact Information:</b> {{$showNameContact['contact_information']}}</div></div>
                </div>
           
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
                                            <td>{{AdditionalCharge($additional_fields[$i]['id'],2)}}</td>
                                            <td>{{ AdditionalCharge($additional_fields[$i]['id'],1)}}</td>
                                            <td>
                                                @if(isset($additional_fields[$i]['price']))
                                                    ${{$additional_fields[$i]['price'] }}
                                                    <input type="hidden" name="additional[{{$i}}][price]"  value="{{$additional_fields[$i]['price']}}">
                                                @else No Price Set. @endif
                                            </td>
                                            <td>
                                               
                                                @if($orderType==1)
                                                    {{$additional_fields[$i]['qty']}}
                                                @else
                                                    {{$additional_fields[$i]['qty']}}
                                                @endif
                                                <td>
                                                    @if($orderType==1)
                                                     {{$appQuantity}}
                                                    @else
                                                    {{$appQuantity}}
                                                    @endif


                                                </td>

                                                <td>
                                                    @if(isset($additional_fields[$i]['price']))
                                                        <div class="priceinqty">${{ $additional_fields[$i]['price']*$appQuantity }}</div>
                                                    @else No Price Set. @endif</td>

                                            <td>
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

        </div>
    </div>
    
    </div>
    </div>
    </body>
    </html>