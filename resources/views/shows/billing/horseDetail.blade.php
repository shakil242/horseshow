@extends('layouts.equetica2')
    @section('main-content')
   
@if(Session::has('message'))
<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
@endif
      <!-- ================= CONTENT AREA ================== -->
<div class="main-contents">
    <div class="container-fluid">

        @php 
          $title = getShowName($show_id)." Invoice";
          $added_subtitle = "";
        @endphp
        @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle,'remove_search'=>1])

        <!-- Content Panel -->
        <div class="white-board">            
            <div class="row">
                <div class="col">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                           <li class="nav-item">
                                <a class="nav-link active" id="division-tab" data-toggle="tab" href="#pendinginv" role="tab" aria-controls="pendinginv" aria-selected="true">Pending Invoices</a>
                            </li>
                             <li class="nav-item">
                                <a class="nav-link" id="showclasses-tab" data-toggle="tab" href="#paidinv" role="tab" aria-controls="paidinv" aria-selected="false">Paid Invoices</a>
                            </li> 
                        </ul>
                    </div>

            </div>
            
            <div class="row">
                    <div class="col-md-12">
                            
                        <!-- TAB CONTENT -->
                        <div class="tab-content" id="myTabContent">
                            <!-- Tab Data Divisions -->
                            <div class="tab-pane fade show active" id="pendinginv" role="tabpanel" aria-labelledby="pendinginv">
                              @include('shows.modal', [
                              'modalData' => [
                              'title' => 'Print Un-Paid Invoices',
                              'id' => 'printUnPaidInvoices',
                              "theLooper" => $collection,
                              'status' => UNPAID,
                              'url' => "shows/pdf/print/app-owner/horse-invoice",
                              ],
                              ])
                              {!! Form::open(['url'=>'shows/horse/payment','method'=>'post','files'=>true,'class'=>'form-horizontal dropzone targetvalue']) !!}
                                @if(count($collection)>0)
                                  <div class="row">
                                      <div class="col-sm-9"></div>
                                       <div class="col-sm-1">
                                           <button style="margin-left: 15px;" type="submit" class="btn btn-small btn-primary checkout" name="payAll" value="Pay All Invoices">Pay Invoices</button>
                                       </div>

                                    <div class="col-sm-2">
                                      <a href="#" class="btn btn-primary btn-small pull-right" data-toggle="modal" data-target="#printUnPaidInvoices">Print Invoice</a>
                                    </div>
                                </div>
                                  @foreach($collection as $horses)
                                  <?php $additionalArray=array(); $CHArray=array(); $additionalExistingids=array(); 
                                  $assetTotal = 0; $paid_on=$horses->paid_on; $divisionClassesInc=array(); ?>
                                  <div class="row horse-invoice">
                                   <div class="col-sm-12 invoice-details box-shadow bg-white p-4 mt-30 mb-30">
                                         @include('shows.billing.partials.header-info')
                                          <hr class="hr-dark hr-thik">
                                          <div class="pb-50"> </div>
                                        <div class="invoice-table">
                                          <!-- Divisions -->
                                          @php
                                            $DivisionHorses = getDivisionForHorse($horses->horse_id,$show_id,UNPAID);
                                            $divisionTotal= 0;
                                            if (count($DivisionHorses)>0) {
                                          @endphp
                                          <div class="classes-title">
                                              <h3>Divisions</h3>
                                              <div class="rr-datatable">
                                                  <table id="crudTabl" class="table primary-table">
                                                    <thead class="hidden-xs">
                                                       <tr>
                                                          <th>Division</th>
                                                          <th>Code</th>
                                                          <th>Registered</th>
                                                          <th>Price</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php  foreach ($DivisionHorses as $index => $class) { 
                                                       // $divarray = getDivisionClassCount($horses->horse_id,$show_id,$class->division_id);
                                                       // if (($class->total_classes != $divarray["counter"] && $class->primary_required != 1) ||  $divarray["counter"] ==0) {
                                                       //    $divisionClassesInc[] = $divarray["ids"];
                                                       // }else{
                                                        // $divarray = getDivisionClasses($horses->horse_id,$show_id,$class->division_id);
                                                        // dd($divarray);
                                                         $divisionClassesInc[$index]['division_id'] = $class->division_id;
                                                         $divisionClassesInc[$index]['primary_required'] = $class->primary_required;
                                                         $CHArray[]=$class->classhorses->id;
                                                        ?>
                                                        <tr>
                                                         <td><strong class="visible-xs">Division</strong> {{getAssetName($class->pclass)}}</td>
                                                         <td><strong class="visible-xs">Code</strong> {{ GetSpecificFormField($class->pclass,"Code") }} </td>
                                                         <td><strong class="visible-xs">Registered</strong> {{ getDates($class->created_at) }} 
                                                           @php 
                                                            $divPenalty = getDivJoiningDatePanlety($class->pclass->id,$horses->horse_id);
                                                          @endphp
                                                          {!! $divPenalty['html'] !!}
                                                         </td>
                                                         <td><strong class="visible-xs">Price</strong>
                                                          @if(isset($class->price)) ($){{twodecimalformate($class->price+$divPenalty['totalPenalty'])}} <?php $divisionTotal= $divisionTotal+$class->price+$divPenalty['totalPenalty']; ?> @else No Price Set. @endif
                                                         </td>
                                                        
                                                      </tr>
                                                        <?php   
                                                      }
                                                      ?> 

                                                    </tbody>
                                                  </table>
                                              </div>
                                              <div class="Totals">
                                                 <div class="col-sm-12 row">
                                                    <div class="col-sm-9 border-bottom  mr-10"><b> Division Price: </b></div>
                                                    <div class="col-sm-2 addAssetPrice border-bottom pull-right">($) {{$divisionTotal}}<input type="hidden" class="DivisionPrice" name="Invoices[{{$horses->horse_id}}][division_price]" value="{{$divisionTotal}}"></div>
                                                 </div>
                                             </div>
                                          </div>
                                            @php } @endphp

                                          <!-- Classes -->
                                          <div class="classes-title">
                                              @if($MS->template->category == TRAINER || $MS->template->category == HORSE)
                                              <h3>Training Services</h3>
                                              @else
                                              <h3>Classes</h3>
                                              @endif
                                              <div class="rr-datatable">
                                                  <table id="crudTabl" class="table primary-table">
                                                    <thead class="hidden-xs">
                                                       <tr>
                                                          @if($MS->template->category == TRAINER || $MS->template->category == HORSE)
                                                          <th>Training Services</th>
                                                           <th>Qty x Price</th>
                                                           @else
                                                          <th>Classes</th>
                                                          <th>Code</th>
                                                          <th># of Entries</th>
                                                          <th>Penalty</th>
                                                           @endif

                                                          <th>Registered</th>

                                                          <th>Price</th>
                                                          <!-- <th>Date</th>
                                                          <th>Location</th> -->
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                      <?php 
                                                      $ListHorses = getUnPaidHorses($horses->horse_id,$show_id,$divisionClassesInc);
                                                        if (count($ListHorses['collection'])>0) {
                                                          foreach ($ListHorses['collection'] as $index => $class) {
                                                            if ($class->pclass != null) {
                                                      ?>
                                                     
                                                      <?php $JoiningPenalty=["html"=>"",'panelty'=>0]; $addedPenalty=["html"=>"",'panelty'=>0]; $sumPenalty =0; $horseCount=1;$classPenaltyLine = 0; $classPenalty =0;$scratchPenaltyLine =0;  $combinedClassId =0;?>

                                                      <tr>
                                                         <td><strong class="visible-xs">Class</strong> {{getAssetName($class->pclass)}} 
                                                                @if(isset($class->combinedClass)) 
                                                                 @if(IsCSInShow($class->combinedClass->combined_class_id,$show_id) > 0)
                                                                  <strong>Combined Class</strong>
                                                                 @endif
                                                                @php 
                                                                  $combinedClassId = $class->combinedClass->combined_class_id;
                                                                @endphp
                                                                @endif
                                                                @if(isset($class->splitClass->splitedclass->SchedulerRestriction) 
                                                                    && count($class->splitClass->splitedclass->SchedulerRestriction)>0 ) 
                                                                 <strong>California Split</strong> 
                                                                @endif
                                                                @if(isset($class->qualifing_check) && $class->qualifing_check == 1) 
                                                                 <strong>Qualifying Price:($){{$class->qualifing_price}}</strong> 
                                                                @endif
                                                          </td>

                                                          @if($MS->template->category == TRAINER || $MS->template->category == HORSE)
                                                              <td><strong class="visible-xs">Qty x Price</strong>
                                                              {{$class->horse_quantity}} x {{$class->price}}</td>
                                                          @endif

                                                        @if($MS->template->category != TRAINER)
                                                         <td><strong class="visible-xs">Code</strong> {{ GetSpecificFormField($class->pclass,"Code") }} </td>
                                                         <td><strong class="visible-xs"># of Entries </strong> {{getClassParticipants($class->class_id,$show_id,$combinedClassId)}} </td>
                                                         <td><strong class="visible-xs">Penalty</strong>
                                                          <?php
                                                              if (count($class->Joining_penalty)>0) {
                                                                  $JoiningPenalty = getPenaltyImposed($class->Joining_penalty,$class->created_at,1);
                                                                  if ($JoiningPenalty['html'] != "") {
                                                                          $classPenalty = $JoiningPenalty['panelty'];
                                                                          $classPenaltyLine = $classPenaltyLine + $classPenalty;
                                                                          $assetTotal = $assetTotal + $classPenalty;
                                                                  }
                                                              }
                                                          ?>
                                                          {!! $JoiningPenalty['html'] !!}
                                                          <?php if ($class->scratch==HORSE_SCRATCHED) {
                                                                      $horseCount =0;
                                                                      if (count($class->penalty)>0) {
                                                                        $addedPenalty = getPenaltyImposed($class->penalty,$class->updated_at);
                                                                        if ($addedPenalty['html'] != "") {
                                                                          
                                                                          $sumPenalty = $addedPenalty['panelty'];
                                                                          $scratchPenaltyLine = $scratchPenaltyLine + $sumPenalty;
                                                                          $assetTotal = $assetTotal + $sumPenalty;
                                                                        }
                                                                      }
                                                            ?>
                                                              {!! $addedPenalty['html'] !!}
                                                            <?php } ?>
                                                         </td>
                                                          @endif


                                                          <td><strong class="visible-xs">Registered</strong> {{ getDates($class->created_at) }} </td>

                                                          <td><strong class="visible-xs">Price</strong>
                                                         
                                                         @include('shows.billing.partials.judgesFee')



                                                             @if($MS->template->category == TRAINER || $MS->template->category == HORSE)
                                                                @if(isset($class->price) || $scratchPenaltyLine != 0 || $classPenaltyLine !=0) ($){{($class->price*$horseCount*$class->horse_quantity)+$classPenaltyLine+$scratchPenaltyLine }}<?php $assetTotal = $assetTotal +($class->price*$horseCount*$class->horse_quantity); ?> <input type="hidden" class="priceSet" value="{{($class->price*$horseCount*$class->horse_quantity)}}"> @else No Price Set. @endif
                                                             @else
                                                                 @if(isset($class->price) || $scratchPenaltyLine != 0 || $classPenaltyLine !=0) ($){{($class->price*$horseCount)+$classPenaltyLine+$scratchPenaltyLine }}<?php $assetTotal = $assetTotal +($class->price*$horseCount); ?> <input type="hidden" class="priceSet" value="{{($class->price*$horseCount)}}"> @else No Price Set. @endif
                                                            @endif


                                                         </td>


                                                         <?php
                                                         //Split Charges , CH = ClassHorses
                                                          $CHArray[]=$class->id;
                                                         //Additional Charges
                                                         if (in_array($class->invite_asociated_key, array_column($additionalArray, 'invited_key') ) ) {
                                                           //Due to change in requirements, Removed charges
                                                           //echo "already have";
                                                         }else{
                                                                $additionalArray[$index]["charges"] = $class->additional_charges;
                                                                if($MS->template->category == TRAINER)
                                                                $additionalArray[$index]["divided"] = 1;
                                                               else
                                                                $additionalArray[$index]["divided"] = getInvoiceDividedUser($class->invite_asociated_key);

                                                                $additionalArray[$index]["invited_key"] = $class->invite_asociated_key;
                                                                $additionalArray[$index]["joined"] = getDates($class->created_at);
                                                         }
                                                          ?>
                                                      </tr>
                                                      <?php
                                                              }
                                                           }
                                                        }
                                                      ?>
                                                      
                                                      <?php 
                                                        if(count($ListHorses['divClasses'])>0){
                                                          foreach($ListHorses['divClasses'] as $class){
                                                              if (!isset($index)) {
                                                                $index = 0;
                                                              }
                                                              $index = $index+1;
                                                              
                                                              if (in_array($class->invite_asociated_key, array_column($additionalArray, 'invited_key') ) ) {
                                                                 //Due to change in requirements, Removed charges
                                                                 
                                                              }else{
                                                                  $additionalArray[$index]["charges"] = $class->additional_charges;
                                                                  $additionalArray[$index]["divided"] = getInvoiceDividedUser($class->invite_asociated_key);
                                                                  $additionalArray[$index]["invited_key"] = $class->invite_asociated_key;
                                                                  $additionalArray[$index]["joined"] = getDates($class->created_at);
                                                             }
                                                            
                                                          }
                                                        }
                                                            
                                                      ?>

                                                    </tbody>
                                                  </table>
                                              </div>
                                               <div class="Totals">
                                                   <div class="col-sm-12 row">
                                                      <div class="col-sm-9 border-bottom mr-10"><b>  Total: </b></div>
                                                      <div class="col-sm-2 addAssetPrice border-bottom">($) {{$assetTotal}}<input type="hidden" class="AssetsPrice" name="Invoices[{{$horses->horse_id}}][assets_price]" value="{{$assetTotal}}"></div> 
                                                   </div>
                                               </div>
                                          </div>
                                          <!-- Prize Money -->
                                           @php
                                              $prizeWon = 0;
                                              $myPrizeobj=new stdClass();
                                              $myPrizeobj->prizeWon=$prizeWon;
                                              $invoice_status=UNPAID;
                                            @endphp
                                            @include('shows.billing.partials.prize',['myPrizeobj'=>$myPrizeobj])
                                            @php $prizeWon=$myPrizeobj->prizeWon @endphp
                                        
                                          <!-- Split Charges. -->
                                          <?php
                                            $splitPrice = 0;
                                            $SpliteCharges = getSplitCharges($CHArray,UNPAID);
                                          ?>
                                        @if(sizeof($SpliteCharges)>0)
                                         <div>
                                          <h3>Split Charges by Trainer</h3>
                                          <div id="indivisual" class="">
                                                    <div class="rr-datatable">
                                                        <table id="crudTabl" class="table primary-table">
                                                        <thead class="hidden-xs">
                                                           <tr>
                                                              <th>Title</th>
                                                              <th>Description</th>
                                                              <th>Date</th>
                                                              <th>Trainer</th>
                                                              <th>Price </th>
                                                              <!-- <th>Date</th>
                                                              <th>Location</th> -->
                                                            </tr>
                                                        </thead>
                                                        <tbody>
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
                                                                    <tr class="tr-row additiona-charges-row">

                                                                        <td><strong class="visible-xs">Title</strong> {{AdditionalCharge($pResponse->id)}}</td>
                                                                        <td><strong class="visible-xs">Description</strong>{{ AdditionalCharge($pResponse->id,1) }}</td>
                                                                          <td><strong class="visible-xs">Date</strong>{{ getDates($pSresponse->created_at) }}</td>
                                                                        <td><strong class="visible-xs">Trainer</strong>{{$pSresponse->TrainerUser->name}}</td>
                                                                        <td><strong class="visible-xs">Price</strong>
                                                                            @if(isset($pResponse->price)) 
                                                                              <div class="priceinqty"><?php 
                                                                                $lineVla = ($pResponse->price*$pResponse->qty / $pSresponse->divided_amoung);
                                                                              $splitPrice =$splitPrice + $lineVla  ?>{{getpriceFormate($lineVla)}}</div> @else No Price Set. @endif</td>
                                                                    </tr>
                                                                    @endif
                                                                    @endforeach
                                                                    @if(isset($pSresponse->comment) && $pSresponse->comment != "")
                                                                      <tr>
                                                                        <td >Comments for Above split:</td>
                                                                        <td colspan="3">{{$pSresponse->comment}}</td>
                                                                      </tr>
                                                                    @endif
                                                                  @endif
                                                                @endforeach
                                                        </tbody>
                                                    </table>
                                               </div>
                                              <div class="Totals">
                                                   <div class="col-sm-12 row">
                                                     
                                                      <div class="col-sm-9 border-bottom  mr-10"><b> Split Charges: </b></div>
                                                      <div class="col-sm-2 addAssetPrice border-bottom">($) {{twodecimalformate($splitPrice)}}<input type="hidden" class="splitcharges" name="Invoices[{{$horses->horse_id}}][split_charges]" value="{{$splitPrice}}"></div> 
                                                   </div>

                                     
                                               </div>
                                          </div>
                                          </div>
                                         @endif

                                          <!-- Additional Charges. -->
                                            <div class="additional-charges">
                                              <h3>Additional Charges</h3>
                                              <div class="rr-datatable">
                                                  <table id="crudTabl" class="table primary-table">
                                                    <thead class="hidden-xs">
                                                       <tr>
                                                          <th>Title</th>
                                                          <th>Date Register</th>
                                                          <th>Description</th>
                                                          <th>Qty x Price</th>
                                                          <th>Divided Horses</th>
                                                          <th>Total Price</th>
                                                          <!-- <th>Date</th>
                                                          <th>Location</th> -->
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                      @if(!empty($additionalArray))
                                                        <?php $additionalPrice=0;?>
                                                        @foreach($additionalArray as $allCharges)
                                                          <?php
                                                            if (isset($allCharges["charges"])) {
                                                               $charge = json_decode($allCharges["charges"]);
                                                           ?>
                                                          @if(count($charge)>0)

                                                          @foreach($charge as $pResponse)
                                                              @if(isset($pResponse->id))
                                                                  <?php if (!in_array($pResponse->id, $additionalExistingids)) {
                                                                      $additionalExistingids[] = $pResponse->id ?>
                                                                  
                                                                  <tr class="tr-row additiona-charges-row">
                                                                      <td><strong class="visible-xs">Title</strong> {{AdditionalCharge($pResponse->id)}}</td>
                                                                      <td><strong class="visible-xs">Date</strong> {{AdditionalCharge($pResponse->id,2)}}</td>
                                                                      <td><strong class="visible-xs">Description</strong>{{ AdditionalCharge($pResponse->id,1) }}</td>
                                                                      <td><strong class="visible-xs">Price</strong>@if(isset($pResponse->price)) {{ $pResponse->qty}} x {{$pResponse->price }} = ($){{$pResponse->price*$pResponse->qty}} @else No Price Set. @endif</td>
                                                                      <td><strong class="visible-xs">Divided Horses</strong>{{$allCharges["divided"]}}</td>

                                                                      <td>
                                                                          <strong class="visible-xs">Price</strong>@if(isset($pResponse->price)) <div class="priceinqty"><?php $additionalPrice = $additionalPrice + (($pResponse->price*$pResponse->qty)/$allCharges["divided"]) ?>($){{ (($pResponse->price*$pResponse->qty)/$allCharges["divided"]) }}</div> @else No Price Set. @endif</td>
                                                                  </tr>
                                                                  <?php } ?>
                                                                @endif
                                                            @endforeach
                                                          @endif
                                                          <?php } ?>
                                                        @endforeach
                                                        
                                                      @else
                                                          <?php //$additionalPrice=0;?>
                                                      @endif
                                                      <!-- order purchased. -->
                                                      @php
                                                        $myOrderSuppliesobj=new stdClass();
                                                        $myOrderSuppliesobj->suppliesPrice=$additionalPrice;
                                                      @endphp
                                                      @include('shows.billing.partials.orderedSupplies',['myOrderSuppliesobj'=>$myOrderSuppliesobj])
                                                      @php $additionalPrice=$myOrderSuppliesobj->suppliesPrice @endphp
                                                          

                                                    </tbody>
                                                  </table>
                                              </div>
                                          </div>

                                          <!-- stall purchased. -->
                                            @php 
                                              $myStallobj=new stdClass();
                                              $myStallobj->stallPrice=0;
                                              $stallPrice = 0;
                                             $invoice_status=UNPAID;
                                            @endphp
                                           @include('shows.billing.partials.stallprice',['myStallobj'=>$myStallobj])
                                          @php $stallPrice=twodecimalformate($myStallobj->stallPrice) @endphp
                                        </div>
                                    
                                    <div class="Totals">
                                              
                                              <div class="row p-3">
                                                <div class="col-sm-9"> <div class="border-bottom pb-2"><b> Additional Pricing: </b></div></div>
                                                <div class="col-sm-3 addAdditionalPrice"><div class="border-bottom pb-2">($){{twodecimalformate($additionalPrice)}}<input type="hidden" class="additionalPrice" name="Invoices[{{$horses->horse_id}}][additional_price]" value="{{twodecimalformate($additionalPrice)}}"></div></div>
                                              </div>
                                              <div class="row p-3">
                                                  <?php
                                                 //$totalAmount = ($additionalPrice+$assetTotal)-$prizeWon;
                                                  if($prizeWon < 600)
                                                  {
                                                      $totalAmount = ($additionalPrice+$assetTotal+$divisionTotal+$stallPrice)-$prizeWon+$splitPrice;
                                                  }else{

                                                      if(prizeClaimCount($horses->horse_id,$show_id) > 0)
                                                          $totalAmount =  ($additionalPrice+$assetTotal+$divisionTotal+$stallPrice)-$prizeWon+$splitPrice;
                                                      else
                                                          $totalAmount = ($additionalPrice+$assetTotal+$divisionTotal+$stallPrice)+$splitPrice;
                                                  }
                                                  $royaltyFinal =twodecimalformate($totalAmount/100*$royalty);

                                                 ?>
                                                 <div class="col-sm-9"><div class="border-bottom pb-2"><b> Miscellaneous Charges: </b></div></div>
                                                 <div class="col-sm-3 addAdditionalPrice"><div class="border-bottom pb-2">($) {{$royaltyFinal}}</div>
                                                     <input type="hidden" class="royaltyPrice" name="Invoices[{{$horses->horse_id}}][royalty]" value="{{$royaltyFinal}}">
                                                     <input type="hidden" class="royaltyByOwner" name="Invoices[{{$horses->horse_id}}][royaltyByOwner]" value="{{$royalty}}">

                                                 </div>
                                              </div>

                                              <div class="row p-3">
                                                @php 
                                                $myTotalCount=new stdClass();
                                                $myTotalCount->total=0;
                                                @endphp
                                               @include('shows.billing.partials.totalcount')  
                                               @php $total=twodecimalformate($myTotalCount->total) @endphp
                                              </div>

                                              @if(isset($splitPrice))
                                              <div class="row p-3">
                                                <div class="col-sm-9"><div class="border-bottom pb-2"><b> Total + Split Charges: </b></div></div>
                                                <div class="col-sm-3 addTotalPrice"><div class="border-bottom pb-2">($) {{$total}} + {{twodecimalformate($splitPrice)}} = {{twodecimalformate($total = $total+$splitPrice)}}</div></div>
                                              </div>
                                              @endif
                                              <div class="row p-3">
                                                <div class="col-sm-9"><div class="border-bottom pb-2"><b> (Federal + State Tax) + Total: </b></div></div>
                                                <?php 
                                                    $tfederal = $MS->federal_tax; 
                                                    $tstate = $MS->state_tax; 
                                                    $taxFederal = ($tfederal*$total)/100;
                                                    $taxState = ($tstate*$total)/100;
                                                ?>
                                                <input type="hidden" name="Invoices[{{$horses->horse_id}}][federal_tax]" value="{{$taxFederal}}">
                                                <input type="hidden" name="Invoices[{{$horses->horse_id}}][state_tax]" value="{{$taxState}}">
                                                <div class="col-sm-3 addTotalPrice"><div class="border-bottom pb-2">($) ( {{twodecimalformate($taxFederal)}} + {{twodecimalformate($taxState)}} ) + {{twodecimalformate($total)}} = {{twodecimalformate($total = $taxState+$taxFederal+$total)}}</div></div>
                                              </div>
                                              <div class="row p-3">
                                                <div class="col-sm-9"><div class="border-bottom pb-2"><b> Total: </b></div></div>
                                                <div class="col-sm-2 addTotalPrice"><div class="border-bottom pb-2">($) {{twodecimalformate($total)}} <input type="hidden" class="totalPrice" name="Invoices[{{$horses->horse_id}}][total_price]" value="{{twodecimalformate($total)}}"></div></div>
                                              </div>
                                              <?php
                                               //updateInvoiceDetail($total,$invoice->id);
                                               // to sync invoice total with the invoice table
                                               ?>
                                            

                                    </div>
                                               <input type="hidden" name="Invoices[{{$horses->horse_id}}][horse_id]" value="{{$horses->horse_id}}">
                                               <input type="hidden" name="Invoices[{{$horses->horse_id}}][show_id]" value="{{$show_id}}">
                                           <div class="col-sm-12">
                                             <div class="offset-9 col-sm-3">
                                               <label>
                                               
                                                  @php 
                                                  $PIOchecked = ""; 
                                                  if(getPayInOfficeStatus($show_id,$horses->horse_id)){
                                                    $PIOchecked = "checked='checked'";
                                                  }
                                                  @endphp
                                                 <input type="checkbox" class="PIO-checkBox" value="{{$horses->horse_id}}" data-show="{{$show_id}}" {{ $PIOchecked }}>
                                                 <span>Pay in Cash</span>
                                               </label>
                                               
                                             </div>
                                           </div>
                                           <div class="row col-sm-12">
                                             <div class="col-sm-8">
                                                <?php $commentG = getInvoiceAddedComment($show_id,$horses->horse_id); ?>
                                                 <span>
                                                      <label>Comments: </label>
                                                      @if(isset($commentG->comment))
                                                        {{$commentG->comment}}
                                                        <input type="hidden" name="HIC_id" value="{{$commentG->id}}">
                                                      @else
                                                        None
                                                      @endif
                                                  </span>
                                              
                                               <input type="hidden" name="show_id" value="{{$show_id}}">
                                               <br>
                                              
                                               
                                              </div>
                                            @if(count($sponsers)>0)
                                            <div class="col-sm-4">
                                              <p style="margin: 25px 0; text-align: center;">
                                              <b>
                                                This show is sponsored by
                                                  <?php $i = 0; ?>
                                                  @foreach($sponsers as $sponsor)
                                                    @if(isset($sponsor->sponsor->fields))
                                                      @if($i != 0)
                                                       <span>,</span>
                                                      @endif
                                                      <strong class="text-info">{{getFirstFieldAnswer($sponsor->sponsor)}} </strong> 
                                                      <?php $i++; ?>
                                                    @endif
                                                  @endforeach
                                                </b>
                                              </p>
                                            </div>
                                            @endif
                                           </div>
                                           
                                   
                                   <input type="hidden" name="Invoices[{{$horses->horse_id}}][horse_id]" value="{{$horses->horse_id}}">
                                   <input type="hidden" name="horse_id[]" value="{{$horses->horse_id}}">
                                    
                                    </div>
                                  </div>
                                  @endforeach

                                        <div class="row">
                                            <div class="col-sm-offset-9 col-sm-3"><input style="margin-left: 15px;" type="submit" class="btn btn-lg btn-primary checkout" name="payall" value="Pay Invoices"></div>
                                        </div>
                                @else
                                    <div class="row" style="margin-top: 20px;text-align: center;">
                                        <div class="col-sm-12 invoice-details box-shadow bg-white p-4 mt-30 mb-30">
                                            <p>No pending invoice exist</p></div>
                                    </div>

                                    @endif


                                <input type="hidden" name="show_id" value="{{$show_id}}">

                              {!! Form::close() !!}
                                

                            </div>
                            <div class="tab-pane fade show" id="paidinv" role="tabpanel" aria-labelledby="paidinv">
                                    <div class="row">
                                      <div class="col-sm-12">
                                        <a href="#" class="btn btn-primary btn-small pull-right" data-toggle="modal" data-target="#printPaidInvoices">Print Invoice</a>
                                      </div>
                                    </div>
                                    <div class="row">
                                      <div class="col-sm-12">
                                      @if(count($paidCollection)>0)
                                      @include('shows.modal', [
                                        'modalData' => [
                                            'title' => 'Print Paid Invoices',
                                            'id' => 'printPaidInvoices',
                                            "theLooper" => $paidCollection,
                                            'status' => PAID,
                                            'url' => "shows/pdf/print/app-owner/horse-invoice",
                                          ],
                                        ])
                                      @foreach($paidCollection as $horses)
                                      <?php $additionalArray=array(); $CHArray=array(); $additionalExistingids=array(); 
                                      $assetTotal = 0; $paid_on=$horses->paid_on;$divisionClassesInc=array();?>
                                      <div class="row horse-invoice ">
                                       <div class="col-sm-12 invoice-details box-shadow bg-white p-4 mt-30 mb-30">
                                          @include('shows.billing.partials.header-info')
                                          <hr class="hr-dark hr-thik">
                                          <div class="pb-50"> </div>
                                            <div class="invoice-table">
                                          @php
                                            $DivisionHorses = getDivisionForHorse($horses->horse_id,$show_id,PAID,$paid_on);
                                            $divisionTotal= 0;
                                            if (count($DivisionHorses)>0) {
                                          @endphp
                                          <div class="classes-title">
                                              <h3>Divisions</h3>
                                              <div class="rr-datatable">
                                                  <table id="crudTabl" class="table primary-table">
                                                    <thead class="hidden-xs">
                                                       <tr>
                                                          <th>Division</th>
                                                          <th>Code</th>
                                                          <th>Registered</th>
                                                          <th>Price</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php  foreach ($DivisionHorses as $index => $class) { 
                                                         $divisionClassesInc[$index]['division_id'] = $class->division_id;
                                                         $divisionClassesInc[$index]['primary_required'] = $class->primary_required;
                                                         $CHArray[]=$class->classhorses->id;
                                                        
                                                        ?>
                                                        <tr>
                                                         <td><strong class="visible-xs">Division</strong> {{getAssetName($class->pclass)}}</td>
                                                         <td><strong class="visible-xs">Code</strong> {{ GetSpecificFormField($class->pclass,"Code") }} </td>
                                                         <td><strong class="visible-xs">Registered</strong> {{ getDates($class->created_at) }} 
                                                           @php 
                                                            $divPenalty = getDivJoiningDatePanlety($class->pclass->id,$horses->horse_id);
                                                          @endphp
                                                          {!! $divPenalty['html'] !!}
                                                         </td>
                                                         <td><strong class="visible-xs">Price</strong>@if(isset($class->price)) ($){{twodecimalformate($class->price+$divPenalty['totalPenalty'])}} <?php $divisionTotal= $divisionTotal+$class->price+$divPenalty['totalPenalty']; ?> @else No Price Set. @endif</td>
                                                        
                                                      </tr>
                                                        <?php   
                                                      }
                                                      ?> 

                                                    </tbody>
                                                  </table>
                                              </div>
                                              <div class="Totals">
                                             <div class="col-sm-12 row">
                                                <div class="col-sm-9 border-bottom mr-10"><b> Division Price: </b></div>
                                                <div class="col-sm-2 addAssetPrice border-bottom">($) {{$divisionTotal}}<input type="hidden" class="DivisionPrice" name="Invoices[{{$horses->horse_id}}][division_price]" value="{{$divisionTotal}}"></div>
                                             </div>
                                         </div>
                                          </div>
                                          <?php } ?>
                                          <!-- Classes -->
                                              <div class="classes-title">
                                                  @if($MS->template->category == TRAINER || $MS->template->category == HORSE)
                                                  <h3>Training Services</h3>
                                                  @else
                                                  <h3>Classes</h3>
                                                  @endif
                                                  <div class="rr-datatable">
                                                      <table id="crudTabl" class="table primary-table">
                                                        <thead class="hidden-xs">
                                                           <tr>
                                                               @if($MS->template->category == TRAINER || $MS->template->category == HORSE)
                                                              <th>Training Services</th>
                                                               <th>Qty x Price</th>
                                                               @else
                                                              <th>Classes</th>
                                                              <th>Code</th>
                                                              <th># of Entries</th>
                                                              <th>Penalty</th>
                                                               @endif
                                                               <th>Registered</th>

                                                              <th width="29%">Price</th>
                                                              <!-- <th>Date</th>
                                                              <th>Location</th> -->
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                          <?php $ListHorses = getPaidHorses($horses->horse_id,$show_id,$divisionClassesInc,$paid_on);


                                                          $paidONInvoice = '';
                                                            if (count($ListHorses['collection'])>0) {
                                                              foreach ($ListHorses['collection'] as $index => $class) {
                                                                if ($class->pclass != null) {
                                                          ?>
                                                          <?php $JoiningPenalty=["html"=>"",'panelty'=>0]; $addedPenalty=["html"=>"",'panelty'=>0]; $sumPenalty =0; $horseCount=1;$classPenaltyLine = 0; $classPenalty =0;$scratchPenaltyLine =0;$combinedClassId=0; ?>

                                                          <tr>
                                                             <td><strong class="visible-xs">Class</strong> {{getAssetName($class->pclass)}} 
                                                                @if(isset($class->combinedClass)) 
                                                                   <strong>Combined Class</strong> 
                                                                   @php 
                                                                    $combinedClassId = $class->combinedClass->combined_class_id;
                                                                   @endphp
                                                                @endif
                                                                @if(isset($class->splitClass->splitedclass->SchedulerRestriction) 
                                                                    && count($class->splitClass->splitedclass->SchedulerRestriction)>0 ) 
                                                                 <strong>California Split</strong> 
                                                                @endif
                                                                @if(isset($class->qualifing_check) && $class->qualifing_check == 1) 
                                                                 <strong>Qualifying Price:($){{$class->qualifing_price}}</strong> 
                                                                @endif
                                                          </td>
                                                              @if($MS->template->category == TRAINER || $MS->template->category == HORSE)
                                                                 <td><strong class="visible-xs">Qty x Price</strong>
                                                                {{$class->horse_quantity}} x {{$class->price}}</td>
                                                                @endif
                                                            @if($MS->template->category != TRAINER)
                                                            <td><strong class="visible-xs">Code</strong> {{ GetSpecificFormField($class->pclass,"Code") }} </td>
                                                            <td><strong class="visible-xs"># of Entries </strong> {{getClassParticipants($class->class_id,$show_id)}} </td>
                                                            <td><strong class="visible-xs">Penalty</strong>
                                                            <?php
                                                                if (count($class->Joining_penalty)>0) {
                                                                    $JoiningPenalty = getPenaltyImposed($class->Joining_penalty,$class->created_at,1);
                                                                    if ($JoiningPenalty['html'] != "") {
                                                                            $classPenalty = $JoiningPenalty['panelty'];
                                                                            $classPenaltyLine = $classPenaltyLine +$classPenalty;
                                                                            $assetTotal = $assetTotal + $classPenalty;
                                                                    }
                                                                }
                                                            ?>
                                                            {!! $JoiningPenalty['html'] !!}

                                                            <?php if ($class->scratch==HORSE_SCRATCHED) {
                                                                        $horseCount =0;
                                                                        if (count($class->penalty)>0) {
                                                                          $addedPenalty = getPenaltyImposed($class->penalty,$class->updated_at);
                                                                          if ($addedPenalty['html'] != "") {
                                                                            $sumPenalty = $addedPenalty['panelty'];
                                                                            $scratchPenaltyLine = $scratchPenaltyLine + $sumPenalty;
                                                                            $assetTotal = $assetTotal + $sumPenalty;
                                                                          }
                                                                        }
                                                              ?>
                                                                {!! $addedPenalty['html'] !!}
                                                              <?php } ?>
                                                            </td>
                                                            @endif
 <td><strong class="visible-xs">Registered</strong> {{ getDates($class->created_at) }} </td>

<td><strong class="visible-xs">Price</strong>
      @include('shows.billing.partials.judgesFee')
    @if($MS->template->category == TRAINER || $MS->template->category == HORSE)
      <span class="prise-contents">
        @if(isset($class->price))<span class="price-display">
          ($){{($class->price*$horseCount*$class->horse_quantity)+$classPenaltyLine+$scratchPenaltyLine }}</span>
          <?php $assetTotal = $assetTotal +($class->price*$horseCount*$class->horse_quantity); ?>
          <input type="hidden" class="priceSet" value="{{($class->price*$horseCount*$class->horse_quantity)+$classPenaltyLine+$scratchPenaltyLine}}">
        @else
          <span class="price-display"> No Price Set.</span>
          <input type="hidden" class="priceSet" value="0">
        @endif
      </span>
    @else
        <span class="prise-contents">
        @if(isset($class->price))<span class="price-display">
          ($){{($class->price*$horseCount)+$classPenaltyLine+$scratchPenaltyLine }}</span>
            <?php $assetTotal = $assetTotal +($class->price*$horseCount); ?>
            <input type="hidden" class="priceSet" value="{{($class->price*$horseCount)+$classPenaltyLine+$scratchPenaltyLine}}">
            @else
                <span class="price-display"> No Price Set.</span>
                <input type="hidden" class="priceSet" value="0">
            @endif
      </span>
    @endif
        <input type="hidden" class="ch_id" value="{{$class->id}}">

</td>

<?php
//Split Charges , CH = ClassHorses
$CHArray[]=$class->id;
$paidONInvoice = $class->paid_on;
//Additional Charges
if (in_array($class->invite_asociated_key, array_column($additionalArray, 'invited_key') ) ) {
 //Due to change in requirements, Removed charges
 //echo "already have";
}else{
      $additionalArray[$index]["ch_id"] = $class->id;
      $additionalArray[$index]["charges"] = $class->additional_charges;


      if($MS->template->category == TRAINER)
      $additionalArray[$index]["divided"] = 1;
      else
      $additionalArray[$index]["divided"] = getInvoiceDividedUser($class->invite_asociated_key);
      $additionalArray[$index]["invited_key"] = $class->invite_asociated_key;
      $additionalArray[$index]["joined"] = getDates($class->created_at);
}
?>
</tr>
<?php
    }
 }
}
?>
</tbody>
</table>
</div>
<div class="Totals">
<div class="col-sm-12 row">
<div class="col-sm-9 border-bottom mr-10"><b> Total: </b></div>
<div class="col-sm-2 addAssetPrice border-bottom">($) {{$assetTotal}}<input type="hidden" class="AssetsPrice" name="Invoices[{{$horses->horse_id}}][assets_price]" value="{{$assetTotal}}"></div>
</div>
</div>
</div>

<!-- Prize Money -->
@php
$prizeWon = 0;
$myPrizeobj=new stdClass();
$myPrizeobj->prizeWon=$prizeWon;
$invoice_status=PAID;
@endphp
@include('shows.billing.partials.prize',['myPrizeobj'=>$myPrizeobj])
@php $prizeWon=$myPrizeobj->prizeWon @endphp

<!-- Split Charges. -->
@php
$splitPrice = 0;
$SpliteCharges = getSplitCharges($CHArray,PAID,$paid_on);
@endphp
@if(sizeof($SpliteCharges)>0)
<div>
<h3>Split Charges by Trainer</h3>
<div id="indivisual" class="">
<div class="rr-datatable">
<table id="crudTabl" class="table primary-table">
<thead class="hidden-xs">
 <tr>
    <th>Title</th>
    <th>Description</th>
    <th>Date</th>
    <th>Trainer</th>
    <th width="30%">Qty x Price </th>
    <th width="8%">Split</th>
    <th>Price </th>
    <!-- <th>Date</th>
    <th>Location</th> -->
  </tr>
</thead>
<tbody>
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
          <tr class="tr-row additiona-charges-row">

              <td><strong class="visible-xs">Title</strong> {{AdditionalCharge($pResponse->id)}}</td>
              <td><strong class="visible-xs">Description</strong>{{ AdditionalCharge($pResponse->id,1) }}</td>
              <td><strong class="visible-xs">Date</strong>{{ getDates($pSresponse->created_at) }}</td>
              <td><strong class="visible-xs">Trainer</strong>{{$pSresponse->TrainerUser->name}}</td>
              <td><strong class="visible-xs">Price</strong>
                <span class="additional-divi">
                  @if(isset($pResponse->price)) {{ $pResponse->qty}} x {{$pResponse->price }} = ($){{$pResponse->price*$pResponse->qty}} @else No Price Set. @endif
                </span>
                  <input type="hidden" class="trainer-split" value="1">
                  <input type="hidden" class="additonal-price" value="{{$pResponse->price }}">
                  <input type="hidden" class="additonal-qty" value="{{ $pResponse->qty}}">
                  <input type="hidden" class="additional-split-id" value="{{$pSresponse->id}}">
                  <input type="hidden" class="additonal-row-id" value="{{$pResponse->id}}">


            </td>
            <td><strong class="visible-xs">Split</strong>{{$pSresponse->divided_amoung}}</td>

              <td><strong class="visible-xs">Price</strong>
                @if(isset($pResponse->price))
                  <div class="priceinqty">
                    <?php $lineVla =  ($pResponse->price*$pResponse->qty / $pSresponse->divided_amoung);
                      $splitPrice =$splitPrice +$lineVla  ?>{{getpriceFormate($lineVla) }}</div> @else No Price Set. @endif
                </td>
          </tr>
          @endif
          @endforeach
          @if(isset($pSresponse->comment) && $pSresponse->comment != "")
            <tr>
              <td >Comments for Above split:</td>
              <td colspan="3">{{$pSresponse->comment}}</td>
            </tr>
          @endif
        @endif
      @endforeach
</tbody>
</table>
</div>
<div class="Totals">
<div class="col-sm-12 row">

<div class="col-sm-9 border-bottom mr-10"><b> Split Charges: </b></div>
<div class="col-sm-2 addAssetPrice border-bottom">($) {{twodecimalformate($splitPrice)}}<input type="hidden" class="splitcharges" name="Invoices[{{$horses->horse_id}}][split_charges]" value="{{$splitPrice}}"></div>
</div>


</div>

</div>
</div>
@endif

<!-- Additional Charges. -->
<div class="additional-charges">
<h3>Additional Charges</h3>
<div class="rr-datatable">
<table id="crudTabl" class="table primary-table">
<thead class="hidden-xs">
<tr>
<th>Title</th>
<th>Date Register</th>
<th>Description</th>
<th width="30%">Qty x Price</th>
<th width="8%">Divided</th>
<th>Total Price</th>
<!-- <th>Date</th>
<th>Location</th> -->
</tr>
</thead>
<tbody>
<?php  $additionalPrice=0;?>
@if(!empty($additionalArray))
@foreach($additionalArray as $allCharges)
<?php
  if (isset($allCharges["charges"])) {
     $charge = json_decode($allCharges["charges"]);
 ?>
@if(count($charge)>0)
@foreach($charge as $pResponse)
    @if(isset($pResponse->id))
    <?php
    if (!in_array($pResponse->id, $additionalExistingids)) {
        $additionalExistingids[] = $pResponse->id ?>
        <tr class="tr-row additiona-charges-row">
            <td><strong class="visible-xs">Title</strong> {{AdditionalCharge($pResponse->id)}}</td>
            <td><strong class="visible-xs">Date</strong> {{AdditionalCharge($pResponse->id,2)}}</td>
            <td><strong class="visible-xs">Description</strong>{{ AdditionalCharge($pResponse->id,1) }}</td>
            <td><strong class="visible-xs">Price</strong>
                <span class="additional-divi">
                  @if(isset($pResponse->price))
                    {{ $pResponse->qty}} x {{$pResponse->price }} = ($){{$pResponse->price*$pResponse->qty}}

                    @else No Price Set.

                    @endif
                </span>
                 <input type="hidden" class="additonal-qty" value="{{ $pResponse->qty}}">
                  <input type="hidden" class="ch_id" value="{{$allCharges['ch_id']}}">
                  <input type="hidden" class="additonal-row-id" value="{{$pResponse->id}}">


            </td>

            <td><strong class="visible-xs">Divided</strong>{{$allCharges["divided"]}}</td>
            <td><strong class="visible-xs">Price</strong>@if(isset($pResponse->price)) <div class="priceinqty">
            <?php $additionalPrice = $additionalPrice + (($pResponse->price*$pResponse->qty)/$allCharges["divided"]) ?>($){{ (($pResponse->price*$pResponse->qty)/$allCharges["divided"]) }}</div> @else No Price Set. @endif</td>
        </tr>
        <?php } ?>
      @endif
  @endforeach
@endif
<?php } ?>
@endforeach
@endif

<!-- order purchased. -->
@php
$myOrderSuppliesobj=new stdClass();
$myOrderSuppliesobj->suppliesPrice=$additionalPrice;
@endphp
@include('shows.billing.partials.orderedSupplies',['myOrderSuppliesobj'=>$myOrderSuppliesobj])
@php $additionalPrice=$myOrderSuppliesobj->suppliesPrice @endphp

</tbody>
</table>
</div>
</div>

<!-- stall purchased. -->
@php
$myStallobj=new stdClass();
$myStallobj->stallPrice=0;
$stallPrice = 0;
$invoice_status=PAID;

@endphp
@include('shows.billing.partials.stallprice',['myStallobj'=>$myStallobj])
@php $stallPrice=twodecimalformate($myStallobj->stallPrice) @endphp

</div>

<div class="Totals">

<div class="row p-3">
<div class="col-sm-9"> <div class="border-bottom pb-2"><b> Additional Pricing: </b></div></div>
<div class="col-sm-3 addAdditionalPrice"><div class="border-bottom pb-2">($){{twodecimalformate($additionalPrice)}}<input type="hidden" class="additionalPrice" name="Invoices[{{$horses->horse_id}}][additional_price]" value="{{twodecimalformate($additionalPrice)}}"></div></div>
</div>


<div class="row p-3">
<?php
//$totalAmount = ($additionalPrice+$assetTotal)-$prizeWon;

if($prizeWon < 600)
{
$totalAmount = ($additionalPrice+$assetTotal+$divisionTotal+$stallPrice)-$prizeWon+$splitPrice;
}else{

if(prizeClaimCount($horses->horse_id,$show_id) > 0)
$totalAmount =  ($additionalPrice+$assetTotal+$divisionTotal+$stallPrice)-$prizeWon+$splitPrice;
else
$totalAmount = ($additionalPrice+$assetTotal+$divisionTotal+$stallPrice)+$splitPrice;
}
$royaltyFinal =twodecimalformate($totalAmount/100*$royalty);

?>
<div class="col-sm-9"><div class="border-bottom pb-2"><b> Miscellaneous Charges: </b></div></div>
<div class="col-sm-3 addAdditionalPrice"><div class="border-bottom pb-2">($) {{$royaltyFinal}}</div>
<input type="hidden" class="royaltyPrice" name="Invoices[{{$horses->horse_id}}][royalty]" value="{{$royaltyFinal}}">
<input type="hidden" class="royaltyByOwner" name="Invoices[{{$horses->horse_id}}][royaltyByOwner]" value="{{$royalty}}">
<input type="hidden" class="divisionTotal" name="Invoices[{{$horses->horse_id}}][division_price]" value="{{$divisionTotal}}">
<input type="hidden" class="stallPrice" name="Invoices[{{$horses->horse_id}}][stall_price]" value="{{$stallPrice}}">

</div>
</div>
<div class="row p-3">
@php
$myTotalCount=new stdClass();
$myTotalCount->total=0;
@endphp
@include('shows.billing.partials.totalcount')
@php $total=twodecimalformate($myTotalCount->total) @endphp
</div>
@if(isset($splitPrice))
<div class="row p-3">
<div class="col-sm-9"><div class="border-bottom pb-2"><b> Total + Split Charges: </b></div></div>
<div class="col-sm-3 addTotalPrice"><div class="border-bottom pb-2">($) {{$total}} + {{twodecimalformate($splitPrice)}} = {{twodecimalformate($total = $total+$splitPrice)}}</div></div>
</div>
@endif
<div class="row p-3">
<div class="col-sm-9"><div class="border-bottom pb-2"><b> (Federal + State Tax) + Total: </b></div></div>
<?php
$tfederal = $MS->federal_tax;
$tstate = $MS->state_tax;
$taxFederal = ($tfederal*$total)/100;
$taxState = ($tstate*$total)/100;
$total_taxis = twodecimalformate($taxFederal)+twodecimalformate($taxState);

?>
<input type="hidden" name="Invoices[{{$horses->horse_id}}][federal_tax]" value="{{$taxFederal}}">
<input type="hidden" name="Invoices[{{$horses->horse_id}}][state_tax]" value="{{$taxState}}">
<div class="col-sm-3 addTotalPrice"><div class="border-bottom pb-2">($) ( {{twodecimalformate($taxFederal)}} + {{twodecimalformate($taxState)}} ) + {{twodecimalformate($total)}} = {{twodecimalformate($total = $taxState+$taxFederal+$total)}}</div></div>
</div>
<div class="row p-3">
<div class="col-sm-9"><div class="border-bottom pb-2"><b> Total: </b></div></div>
<div class="col-sm-3 addTotalPrice"><div class="border-bottom pb-2">($) {{twodecimalformate($total)}} <input type="hidden" class="totalPrice" name="Invoices[{{$horses->horse_id}}][total_price]" value="{{twodecimalformate($total)}}"></div></div>
</div>

<input type="hidden" name="Invoices[{{$horses->horse_id}}][total_taxis]" value="{{$total_taxis}}">


</div>
<input type="hidden" name="Invoices[{{$horses->horse_id}}][horse_id]" value="{{$horses->horse_id}}">
<input type="hidden" name="Invoices[{{$horses->horse_id}}][show_id]" value="{{$show_id}}">
<input type="hidden" name="Invoices[{{$horses->horse_id}}][total_taxis]" value="{{$total_taxis}}">
@php
$gPIOS = getPayInOfficeStatus($show_id,$horses->horse_id,$paid_on);
if($gPIOS){
@endphp
<div class="col-sm-12">
<div class="offset-9 col-sm-3">
<span style="color:red;">Paid in Cash <br></span>
</div>
</div>

@php
}
@endphp

<div class="row">
<div class="col-sm-8">
<?php $commentG = getInvoiceAddedComment($show_id,$horses->horse_id); ?>
<span>
<label>Comments: </label>
@if(isset($commentG->comment))
<textarea class="form-control whiteback" name="invoice_comments" disabled="disabled">{{$commentG->comment}}</textarea>
@else
<textarea class="form-control whiteback" name="invoice_comments" disabled="disabled"></textarea>
@endif

</span>
</div>
@if(count($sponsers)>0)
<div class="col-sm-4">
<p style="margin: 25px 0; text-align: center;">
<b>
This show is sponsored by
<?php $i = 0; ?>
@foreach($sponsers as $sponsor)
@if(isset($sponsor->sponsor->fields))
@if($i != 0)
 <span>,</span>
@endif
<strong class="text-info">{{getFirstFieldAnswer($sponsor->sponsor)}} </strong>
<?php $i++; ?>
@endif
@endforeach
</b>
</p>
</div>
@endif
</div>


</div>


</div>
@endforeach
@else
<div class="row" style="margin-top: 20px;text-align: center;"><p>No Paid invoice exist</p></div>
@endif

</div>
</div>
</div>
</div>

</div>

<div id="billing_prize_claim" class="modal fade" role="dialog">
<div class="modal-dialog">
<!-- Modal content-->
<div class="modal-content">
<div class="modal-header">
<h4 class="modal-title">Prize Claim Form 1099</h4>
<button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
{!! Form::open(['url'=>'/Billing/prizeClaimSubmit','method'=>'post','class'=>'form-horizontal']) !!}

<div class="modal-body">
<div class="row" style="width: 80%; margin-left: 30px;">

<div class="col-md-9  p-0  text-left">
<label>Prize Money/TaxPayer Name</label>
<input required type="text" name="prize_amount" class="form-control" id="prize_amount">
</div>

<div class="col-md-9  p-0  text-left">
<label>Social Security Number</label>
<input required type="text" name="social_security_number" class="form-control" id="social_security_number">
</div>

<div class="col-md-9  p-0  text-left">
<label>Federal ID Number</label>
<input type="text" name="federal_id_number" class="form-control" id="federal_id_number">
</div>
</div>
</div>
<div class="modal-footer">

<input type="hidden" name="show_id" class="show_id">
<input type="hidden" name="horse_id" class="horse_id">
<button type="submit" class="btn btn-default">Update</button>

<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>
{!! Form::close() !!}
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
<script src="{{ asset('/js/shows/pay-inoffice.js') }}"></script>
<script src="{{ asset('/js/custom-tabs-cookies-new.js') }}"></script>
@include('layouts.partials.datatable')
{{-- <script src="{{ asset('/js/shows/already-paid-invoice.js') }}"></script> --}}
@endsection