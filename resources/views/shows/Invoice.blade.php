@extends('layouts.equetica2')

@section('custom-htmlheader')
  <!-- Search populate select multiple-->
 <script src="{{ asset('/js/vender/bootstrap3-typeahead.min.js') }}"></script>
  <!-- END:Search populate select multiple-->
@endsection

@section('main-content')
	 	<div class="row">
  		 	<div class="col-sm-4">
          <h1>Shows</h1>
        </div>
        <div class="col-sm-5 action-holder pull-right">
          <!-- <form action="#">
            <div class="search-form">
              <input class="form-control input-sm" placeholder="Search Class Name" id="myInputTextField" type="search">
              <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
            </div>
          </form> -->
          <a href="{{URL::to('shows') }}/{{nxb_encode($manage_show_r_id)}}/{{nxb_encode($participant_id)}}/pdf/pay/invoice" class="btn btn-lg btn-primary"> Export PDF</a>
          <a href="#" onclick="window.print();" class="btn btn-lg btn-primary"> Print </a>
          
        </div>
        <div class="col-sm-3"></div>
      	</div>
    <div class="row">
      <div class="info">
      @if(Session::has('message'))
        <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
      @endif
      </div>
    </div>
         <div class="row">
        <div class="info">
            @if(Session::has('message'))
                <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
            @endif
        </div>
    </div>
      	<div class="row">
	        <div class="col-sm-12">
            {!! Breadcrumbs::render('shows-invoice',nxb_encode($MSR->id)) !!}
	    	  </div>
      	</div>
        <div class="row">
          <div class="col-sm-12">
            @if($assets == null)
              <div class="">
                <div class="col-lg-5 col-md-5 col-sm-6">{{NO_CLASSES_RESPONSE}}</div>
              </div>
            @else
                <div class="tab-content">
                  {{--{!! Form::open(['url'=>'shows/invoice/payment','method'=>'post','files'=>true,'class'=>'form-horizontal dropzone targetvalue']) !!}--}}
                  <input type="hidden" name="show_id" value="{{$MSR->manage_show_id}}">
                  <input type="hidden" name="MSR" value="{{$MSR->id}}">
                  
                  <div id="indivisual" class="tab-pane fade in active">
                            <div class="module-holer rr-datatable">
                                <table id="crudTabl" class="table primary-table">
                                <thead class="hidden-xs">
                                   <tr>
                                      <th style="width:5%">#</th>
                                      <th>Title</th>
                                      <th>Code</th>
                                      <th>Price</th>
                                      <th>Horses</th>
                                      <th>Penalty</th>
                                      <th>Total</th>
                                      <!-- <th>Date</th>
                                      <th>Location</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $assetTotal = 0;$serial =0 ?>
                                    @if(sizeof($assets)>0)
                                        @foreach($assets as $pResponse)
                                            @if(isset($pResponse->id))
                                            <?php 
                                              $serial = $serial + 1; 
                                              $roleName = '';
                                            ?>
                                            <tr class="tr-row">
                                                <td>{{ $serial }}</td>
                                                <td><strong class="visible-xs">Title</strong> {{GetAssetNamefromId($pResponse->id)}}<input type="hidden" name="assets[]" value="{{$pResponse->id}}"></td>
                                                <td><strong class="visible-xs">Code</strong>{{ GetSpecificFormField($pResponse->id,"Code")  }}</td>
                                                <td><strong class="visible-xs">Price</strong>@if(isset($pResponse->orignal_price)) ($){{$pResponse->orignal_price }}  @else No Price Set. @endif</td>
                                                <td><strong class="visible-xs">Horses</strong>
                                                      <?php $horse_count = 1; ?>
                                                    @if(isset($pResponse->horses)) 
                                                      <?php $horse_count = count($pResponse->horses); ?>
                                                        @foreach($pResponse->horses as $horse)
                                                          <?php $entry = modelShowHorse($horse,$pResponse->id,$participant->invite_asociated_key);
                                                          if(isset($entry->scratch) && $entry->scratch==HORSE_SCRATCHED){
                                                              $horse_count= $horse_count-1;
                                                            ?>
                                                            <div class='scratched-horses'>
                                                          <?php } ?>
                                                          {{GetAssetName($entry->horse)}} {!! FormateHorseRegisteration($entry->horse_reg) !!} <br>
                                                          <?php if ($entry->scratch==HORSE_SCRATCHED) {
                                                          ?>
                                                            </div>
                                                          <?php } ?>
                                                        @endforeach
                                                    @else No Price Set. @endif
                                                </td>

                                                <td><strong class="visible-xs">Penalty</strong>
                                                    <?php $horse_count = 1; $sumPenalty =0; $classPenaltyLine = 0; $classPenalty =0;$scratchPenaltyLine =0; ?>
                                                    @if(isset($pResponse->horses)) 
                                                      <?php $horse_count = count($pResponse->horses); ?>
                                                        @foreach($pResponse->horses as $horse)
                                                          <?php $entry = modelShowHorse($horse,$pResponse->id,$participant->invite_asociated_key); $addedPenalty ="";$JoiningPenalty=""; ?>
                                                          <!-- joing date penalty -->
                                                          <?php 
                                                          if (isset($entry->Joining_penalty)) {
                                                              $JoiningPenalty = getPenaltyImposed($entry->Joining_penalty,$entry->created_at,1); 
                                                              if ($JoiningPenalty != "") {
                                                                      $classPenalty = $entry->Joining_penalty->penality;
                                                                      $classPenaltyLine = $classPenaltyLine + $entry->Joining_penalty->penality;
                                                                      $assetTotal = $assetTotal + $classPenalty;
                                                              }
                                                          }
                                                          ?>
                                                          {!! $JoiningPenalty !!}
                                                                      

                                                          <?php if ($entry->scratch==HORSE_SCRATCHED) {
                                                                   $horse_count= $horse_count-1;
                                                                    if (isset($entry->penalty)) {
                                                                      $addedPenalty = getPenaltyImposed($entry->penalty,$entry->updated_at);
                                                                      if ($addedPenalty != "") {
                                                                      $sumPenalty = $entry->penalty->penality;
                                                                      $scratchPenaltyLine = $scratchPenaltyLine + $sumPenalty;
                                                                      $assetTotal = $assetTotal + $sumPenalty;
                                                                      }
                                                                    }
                                                          ?>
                                                            {!! $addedPenalty !!}
                                                          <?php } ?>
                                                        @endforeach
                                                    @else No Penalty @endif
                                                </td>
                                                <td><strong class="visible-xs">Price</strong>@if(isset($pResponse->orignal_price)) ($){{($pResponse->orignal_price*$horse_count)+$classPenaltyLine+$scratchPenaltyLine }}<?php $assetTotal = $assetTotal +($pResponse->orignal_price*$horse_count); ?> <input type="hidden" class="priceSet" value="{{($pResponse->price*$horse_count)}}"> @else No Price Set. @endif</td>
                                            </tr>
                                            @endif
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                       </div>
                       <div class="Totals row">
                           <div class="col-sm-5 pull-right">
                             
                              <div class="col-sm-3 border-bottom"><b> Class Price: </b></div>
                              <div class="col-sm-9 addAssetPrice border-bottom">($) {{$assetTotal}}<input type="hidden" class="AssetsPrice" name="assets_price" value="0"></div> 
                           </div>

             
                       </div>

                  </div>
                  <?php $prizeWon = 0; ?>
                  @if(sizeof($prize)>0)
                  <div>
                  <h3>Prize Money</h3>
                  <div id="indivisual" class="tab-pane fade in active">
                            <div class="module-holer rr-datatable">
                                <table id="crudTabl" class="table primary-table">
                                <thead class="hidden-xs">
                                   <tr>
                                      <th style="width:5%">#</th>
                                      <th>Class</th>
                                      <th>Position</th>
                                      <th>Prize Won</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $serial =0; ?>
                                        @foreach($prize as $one_asset)
                                          <?php $decode_asset = json_decode($one_asset->position_fields); ?>
                                          @foreach($decode_asset as $pResponse)
                                            @if(isset($pResponse->user_id) && $pResponse->user_id == $user_id)
                                            <?php  $serial = $serial + 1;?>
                                            <tr class="tr-row">
                                                <td>{{ $serial }}</td>
                                                <td><strong class="visible-xs">Class</strong>{{GetAssetNamefromId($one_asset->asset_id)}}</td>
                                                <td><strong class="visible-xs">Position</strong>{{getPostionText($pResponse->position)}}</td>
                                                <td><strong class="visible-xs">Price</strong>@if(isset($pResponse->price)) <div class="priceinqty"><?php $prizeWon = $prizeWon+$pResponse->price ?>($){{ $pResponse->price}}</div> @else No Price Set. @endif</td>
                                            </tr>
                                            @endif
                                          @endforeach
                                        @endforeach
                                        @if($serial ==0)
                                        <tr class="tr-row">
                                          <td colspan="4"> You have not won any position or prize!</td>
                                        </tr>
                                        @endif
                                </tbody>
                            </table>
                       </div>
                       <div class="Totals row">
                           <div class="col-sm-5 pull-right">
                             
                              <div class="col-sm-3 border-bottom"><b> Prize Won: </b></div>
                              <div class="col-sm-9 addAssetPrice border-bottom">($) {{$prizeWon}}<input type="hidden" class="PrizeWon" name="prize_won" value="{{$prizeWon}}"></div> 
                           </div>

             
                       </div>

                  </div>
                  </div>
                  @endif
                 @if(sizeof($SpliteCharges)>0)
                 <div>
                  <h3>Split Charges by Trainer</h3>
                  <div id="indivisual" class="tab-pane fade in active">
                            <div class="module-holer rr-datatable">
                                <table id="crudTabl" class="table primary-table">
                                <thead class="hidden-xs">
                                   <tr>
                                      <th style="width:5%">#</th>
                                      <th>Title</th>
                                      <th>Description</th>
                                      <th>Horses</th>
                                      <th>Trainer</th>
                                      <th>Price </th>
                                      <!-- <th>Date</th>
                                      <th>Location</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $splitPrice = 0;$serial =0 ?>
                                        @foreach($SpliteCharges as $pSresponse)
                                            <?php 
                                                if (isset($pSresponse->additional_fields)>0) {
                                                    $splite_additional = json_decode($pSresponse->additional_fields);
                                                }else{
                                                    $splite_additional = null;
                                                }

                                            ?>
                                          @if($splite_additional)
                                            @foreach($splite_additional as $pResponse)
                                             @if(isset($pResponse->id))
                                             <?php $serial = $serial + 1; ?> 
                                            <tr class="tr-row additiona-charges-row">
                                                <td>{{ $serial }}</td>
                                                <td><strong class="visible-xs">Title</strong> {{AdditionalCharge($pResponse->id)}}</td>
                                                <td><strong class="visible-xs">Description</strong>{{ AdditionalCharge($pResponse->id,1) }}</td>
                                                <td><strong class="visible-xs">Horses</strong>{{ GetAssetNamefromId($pSresponse->ClassHorse->horse_id) }}</td>
                                                <td><strong class="visible-xs">Trainer</strong>{{$pSresponse->TrainerUser->name}}</td>
                                                <td><strong class="visible-xs">Price</strong>@if(isset($pResponse->price)) <div class="priceinqty"><?php $splitPrice = $splitPrice + number_format(($pResponse->price*$pResponse->qty / $pSresponse->divided_amoung), 2) ?>($){{ number_format((($pResponse->price*$pResponse->qty) / $pSresponse->divided_amoung),2) }}</div> @else No Price Set. @endif</td>
                                            </tr>
                                            @endif
                                            @endforeach
                                          @endif
                                        @endforeach
                                </tbody>
                            </table>
                       </div>
                       <div class="Totals row">
                           <div class="col-sm-5 pull-right">
                             
                              <div class="col-sm-4 border-bottom"><b> Split Charges: </b></div>
                              <div class="col-sm-8 addAssetPrice border-bottom">($) {{$splitPrice}}<input type="hidden" class="splitcharges" name="split_charges" value="{{$splitPrice}}"></div> 
                           </div>

             
                       </div>

                  </div>
                  </div>
                 @endif

                  <h3>Additional Charges</h3>
                  <div id="indivisuals" class="tab-pane fade in active">
                            <div class="module-holer rr-datatable">
                                <table id="crudTabl" class="table primary-table">
                                <thead class="hidden-xs">
                                   <tr>
                                      <th style="width:5%">#</th>
                                      <th>Title</th>
                                      <th>Description</th>
                                      <th>Price</th>
                                      <th>Quantity</th>
                                      <th>Price x Qty</th>
                                      <!-- <th>Date</th>
                                      <th>Location</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $additionalPrice = 0;$serial =0; ?>
                                    @if(sizeof($additional_price)>0)
                                        @foreach($additional_price as $pResponse)
                                             @if(isset($pResponse->id))
                                             <?php 
                                              $serial = $serial + 1; 
                                            ?>
                                            <tr class="tr-row additiona-charges-row">
                                                <td>{{ $serial }}</td>
                                                <td><strong class="visible-xs">Title</strong> {{AdditionalCharge($pResponse->id)}}</td>
                                                <td><strong class="visible-xs">Description</strong>{{ AdditionalCharge($pResponse->id,1) }}</td>
                                                <td><strong class="visible-xs">Price</strong>@if(isset($pResponse->price)) ($){{$pResponse->price }} @else No Price Set. @endif</td>
                                                <td><strong class="visible-xs">Quantity</strong>{{$pResponse->qty}}</td>
                                                <td><strong class="visible-xs">Price</strong>@if(isset($pResponse->price)) <div class="priceinqty"><?php $additionalPrice = $additionalPrice + ($pResponse->price*$pResponse->qty) ?>($){{ $pResponse->price*$pResponse->qty}}</div> @else No Price Set. @endif</td>
                                            </tr>
                                            @endif
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                       </div>
                       <div class="Totals row">
                           <div class="col-sm-12">
                            <div class="col-sm-7 pull-right">
                              <div class="col-sm-7 border-bottom"><b> Additional Pricing: </b></div>
                              <div class="col-sm-5 addAdditionalPrice border-bottom">($) {{$additionalPrice}}<input type="hidden" class="additionalPrice" name="additional_price" value="{{$additionalPrice}}"></div>
                            </div>
                           </div>
                           <div class="col-sm-12">

                              <?php
                               $totalAmount = ($additionalPrice+$assetTotal)-$prizeWon;

                                $royaltyFinal =$totalAmount/100*$royalty;

                               ?>

                               <div class="col-sm-7 pull-right">
                                   <div class="col-sm-7 border-bottom"><b> Miscellaneous Charges: </b></div>
                                   <div class="col-sm-5 addAdditionalPrice border-bottom">($) {{$royaltyFinal}}
                                       <input type="hidden" class="royaltyPrice" name="royalty" value="{{$royaltyFinal}}">
                                       <input type="hidden" class="royaltyByOwner" name="royaltyByOwner" value="{{$royalty}}">

                                   </div>
                               </div>
                           </div>
                           <div class="col-sm-12">
                             <div class="col-sm-7 pull-right">
                              <div class="col-sm-7 border-bottom"><b> (Assets+Additonal+Miscellaneous Charges)-Prize: </b></div>
                              <div class="col-sm-5 addTotalPrice border-bottom">($) ({{$assetTotal}} + {{$additionalPrice}})+{{$royaltyFinal}} - {{$prizeWon}} = {{$total = ($additionalPrice+$assetTotal+$royaltyFinal)-$prizeWon }}</div>
                              @if(isset($splitPrice))
                              <div class="col-sm-7 border-bottom"><b> Total + Split Charges: </b></div>
                              <div class="col-sm-5 addTotalPrice border-bottom">($) {{$total}} + {{$splitPrice}}+{{$royaltyFinal}} = {{$total = $total+$splitPrice+$royaltyFinal}}</div>
                              @endif
                              <div class="col-sm-7 border-bottom"><b> Total: </b></div>
                              <div class="col-sm-5 addTotalPrice border-bottom">($) {{$total}} <input type="hidden" class="totalPrice" name="total_price" value="{{$total}}"></div>

                                <?php
                                 updateInvoiceDetail($total,$invoice->id);
                                 // to sync invoice total with the invoice table
                                 ?>
                                 <div class="col-sm-8" style="float:right;">
                                     {{--<input type="submit" class="btn btn-lg btn-primary checkout" value="Pay Invoice">--}}
                                     <div class="col-sm-7"> <a @if($invoice->payinoffice == 1) style="display: none; float: right" @endif id="invoicePayLink" class="btn btn-lg btn-primary checkout"
                                        data-content="{{ $invoice->amount }}"
                                        data-id="{{nxb_encode($invoice->id)}}"
                                        href="{{URL::to('master-template') }}/{{nxb_encode($invoice->id)}}/billing/singleInvoice/{{nxb_encode($total)}}">Pay Invoice</a>
                                      </div>
                                       <div class="col-sm-5">
                                        <label style="margin-top: 13px;"><input type="checkbox" class="btn btn-lg btn-primary pay-in-office" data-invoice="{{nxb_encode($invoice->id)}}" name="payInOffice" value="1" @if($invoice->payinoffice == 1) checked="checked" @endif > Pay in office</label>
                                      </div>
                                 </div>
                              </div>
                           </div>
                           
                       </div>

                  </div>



                  {{--{!! Form::close() !!}--}}

                </div>
            @endif
          </div>
        </div>
        <!-- Tab containing all the data tables -->
   
		
@endsection

@section('footer-scripts')
    <script src="{{ asset('/js/shows/pay-inoffice.js') }}"></script>
    @include('layouts.partials.datatable')
@endsection
