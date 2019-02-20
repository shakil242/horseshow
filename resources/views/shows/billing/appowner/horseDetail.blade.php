    @extends('layouts.equetica2')


    @section('main-content')

      <!-- ================= CONTENT AREA ================== -->

<div class="row">
    <div class="col-sm-12">
        @if(Session::has('message'))
            <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
        @endif
    </div>
</div>
<div class="main-contents">
    <div class="container-fluid">

        @php 
          $title = getShowName($show_id)." invoice";
          $added_subtitle = Breadcrumbs::render('shows-appowner-invoices-detail', nxb_encode($template_id));
        @endphp
        @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle,'remove_search'=>1])

        <!-- Content Panel -->
         <div class="row">
              <div class="col-sm-12">
                <h3 class="golden-heading" style="text-align: center">{{getUserNamefromid($user_id)}}</h3>
              </div>
        </div>

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
                              <div class="row">
                                <div class="col-sm-12">
                                    <a href="#" class="btn btn-primary btn-small pull-right" data-toggle="modal" data-target="#printUnPaidInvoices">Print Invoice</a>
                                  </div>
                                </div>
                                @if(count($collection)>0)
                                 
                                 @include('shows.modal', [
                                  'modalData' => [
                                      'title' => 'Print Un-Paid Invoices',
                                      'id' => 'printUnPaidInvoices',
                                      "theLooper" => $collection,
                                      'status' => UNPAID,
                                      'url' => "shows/pdf/print/app-owner/horse-invoice",
                                    ],
                                  ])

                                  @foreach($collection as $horses)
                                  <?php $additionalArray=array(); $CHArray=array(); $assetTotal = 0;$additionalExistingids=array(); $invoice_status=UNPAID;$paid_on=$horses->paid_on;$divisionClassesInc=array();?>
                                  <div class="row horse-invoice">
                                   <div class="col-sm-12 invoice-details invoice-details box-shadow bg-white p-4 mt-30 mb-30">
                                          @include('shows.billing.partials.header-info')
                                          
                                          <hr class="hr-dark hr-thik">
                                          <div class="pb-50"> </div>
                                        <div class="invoice-table">
                                          
                                        <!-- Divisions -->
                                        <?php $DivisionHorses = getDivisionForHorse($horses->horse_id,$show_id,UNPAID);
                                        $divisionTotal= 0;
                                          if (count($DivisionHorses)>0) {
                                        ?>
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
                                                       <td><strong class="visible-xs">Code</strong> {{ GetSpecificFormField($class->pclass,"USEF Section Code") }} </td>
                                                       <td><strong class="visible-xs">Registered</strong> {{ getDates($class->created_at) }} 
                                                          @php 
                                                            $divPenalty = getDivJoiningDatePanlety($class->pclass->id,$horses->horse_id);
                                                          @endphp
                                                          {!! $divPenalty['html'] !!}
                                                       </td>
                                                       <td><strong class="visible-xs">Price</strong>
                                                         <span class="prise-contents">
                                                          @if(isset($class->price)) 
                                                            <span class="price-display">
                                                            ($){{twodecimalformate($class->price+$divPenalty['totalPenalty'])}} 
                                                             </span>
                                                             <?php $divisionTotal= $divisionTotal+$class->price+$divPenalty['totalPenalty']; ?> 
                                                            <input type="hidden" class="priceSet" value="{{$class->price}}"> 
                                                          @else 
                                                            <span class="price-display"> No Price Set. </span>
                                                            <input type="hidden" class="priceSet" value="0"> 

                                                          @endif
                                                          </span>
                                                          <input type="hidden" class="ch_id" value="{{$class->id}}"> 
                                                          <a href="javascript:" class="edit-price-division"> (Edit)</a>
                                                       </td>
                                                        
                                                    </tr>
                                                <?php } ?>
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
                                                       <td><strong class="visible-xs">Code</strong> {{ GetSpecificFormField($class->pclass,"USEF Section Code") }} </td>
                                                       <td><strong class="visible-xs">Registered</strong> {{ getDates($class->created_at) }}
                                                          @php
                                                            $divPenalty = getDivJoiningDatePanlety($class->pclass->id,$horses->horse_id);
                                                          @endphp
                                                          {!! $divPenalty['html'] !!}
                                                       </td>
                                                       <td><strong class="visible-xs">Price</strong>
                                                         <span class="prise-contents">
                                                          @if(isset($class->price))
                                                            <span class="price-display">
                                                            ($){{twodecimalformate($class->price+$divPenalty['totalPenalty'])}}
                                                             </span>
                                                             <?php $divisionTotal= $divisionTotal+$class->price+$divPenalty['totalPenalty']; ?>
                                                            <input type="hidden" class="priceSet" value="{{$class->price}}">
                                                          @else
                                                            <span class="price-display"> No Price Set. </span>
                                                            <input type="hidden" class="priceSet" value="0">
                                                          @endif
                                                          </span>
                                                          <input type="hidden" class="ch_id" value="{{$class->id}}">
                                                          <a href="javascript:" class="edit-price-division"> (Edit)</a>
                                                       </td>

                                                    </tr>
                                                <?php } ?>
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
                                        <?php } ?>
                                        <!-- Classes -->
                                          <div class="classes-title">
                                              @if(isset($MS->template->category) && $MS->template->category == TRAINER)
                                              <h3>Training Services</h3>
                                              @else
                                              <h3>Classes</h3>
                                              @endif
                                              <div class="rr-datatable">
                                                  <table id="crudTabl" class="table primary-table">
                                                    <thead class="hidden-xs">
                                                       <tr>
                                                           @if(isset($MS->template->category) && $MS->template->category == TRAINER)
                                                           <th>Training Services
                                                           @else
                                                           <th>Classes</th>
                                                          <th>Code</th>
                                                          <th># of Entries</th>
                                                           <th>Penalty</th>
                                                           @endif
                                                          <th>Registered</th>
                                                           @if(isset($MS->template->category) && $MS->template->category == TRAINER)
                                                           <th>Qty x Price</th>
                                                           @endif
                                                           <th>Price</th>
                                                          <!-- <th>Date</th>
                                                          <th>Location</th> -->
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                      <?php $ListHorses = getUnPaidHorses($horses->horse_id,$show_id,$divisionClassesInc);
                                                        if (count($ListHorses['collection'])>0) {
                                                          foreach ($ListHorses['collection'] as $index => $class) {
                                                            if ($class->pclass != null) {
                                                      ?>
                                                      <?php $JoiningPenalty=["html"=>"",'panelty'=>0]; $addedPenalty=["html"=>"",'panelty'=>0]; $sumPenalty =0; $horseCount=1;$classPenaltyLine = 0; $classPenalty =0;$scratchPenaltyLine =0;$combinedClassId=0; ?>

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
                                                          @if(isset($MS->template->category) && $MS->template->category != TRAINER)
                                                          <td><strong class="visible-xs">Code</strong> {{ GetSpecificFormField($class->pclass,"Code") }} </td>
                                                          <td><strong class="visible-xs"># of Entries </strong> {{getClassParticipants($class->class_id,$show_id,$combinedClassId)}} </td>
                                                         <td><strong class="visible-xs">Penalty</strong> 
                                                          <?php 
                                                              if (isset($class->Joining_penalty)) {
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
                                                          @if(isset($MS->template->category) && $MS->template->category == TRAINER)
                                                            <td><strong class="visible-xs">Qty x Price</strong>
                                                            <span class="horse_quantity"> {{$class->horse_quantity}} </span>  <input type="number" style="width:60px; display:none" class="horse_quantity_hidden" value="{{$class->horse_quantity}}">x {{$class->price}}</td>
                                                            <td><strong class="visible-xs">Price</strong>
                                                            @include('shows.billing.partials.judgesFee')
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
                                                            <input type="hidden" class="ch_id" value="{{$class->id}}">
                                                            <a href="javascript:" class="edit-price-trainer"> (Edit)</a>
                                                            </td>
                                                            @else
                                                              <td><strong class="visible-xs">Price</strong>
                                                                  @include('shows.billing.partials.judgesFee')
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

                                                                  <input type="hidden" class="ch_id" value="{{$class->id}}">
                                                                  <a href="javascript:" class="edit-price"> (Edit)</a>
                                                              </td>
                                                          @endif
                                                         <?php
                                                          //Split Charges , CH = ClassHorses
                                                          $CHArray[]=$class->id;
                                                         //Additional Charges for multiple participation at same time
                                                         if (in_array($class->invite_asociated_key, array_column($additionalArray, 'invited_key') ) ) {
                                                             //Due to change in requirements, Removed charges
                                                           //echo "already have";

                                                         }else{
                                                                $additionalArray[$index]["ch_id"] = $class->id;
                                                                $additionalArray[$index]["charges"] = $class->additional_charges;
                                                                if(isset($MS->template->category) && $MS->template->category == TRAINER)
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
                                                           } ?>
                                                      <?php
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
                                                               //echo "already have";
                                                            }else{
                                                                $additionalArray[$index]["ch_id"] = $class->id;
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
                                                              <th>Qty x Price </th>
                                                              <th>Split</th>
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
                                                                            
                                                                          <a href="javascript:" class="edit-additional-charges"> (Edit)</a>
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
                                                                      <td><b>Comments for Above split:</b></td>
                                                                      <td colspan="5">{{$pSresponse->comment}}</td>
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
                                                <a href="javascript:" onclick="addCharges('{{$class->id}}')" class="btn btn-success pull-right mb-10">Add Charges</a>

                                              <div class="rr-datatable">
                                                  <table id="crudTabl" class="table primary-table">
                                                    <thead class="hidden-xs">
                                                       <tr>
                                                          <th>Title</th>
                                                          <th>Date Register</th>
                                                          <th>Description</th>
                                                          <th>Qty x Price</th>
                                                          <th>Divided</th>
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
                                                                      <td><strong class="visible-xs">Date</strong> {{ AdditionalCharge($pResponse->id,2) }}</td>
                                                                      <td><strong class="visible-xs">Description</strong>{{ AdditionalCharge($pResponse->id,1) }}</td>
                                                                      <td><strong class="visible-xs">Price</strong>
                                                                          <span class="additional-divi">
                                                                            @if(isset($pResponse->price)) 
                                                                              {{ $pResponse->qty}} x {{$pResponse->price }} = ($){{$pResponse->price*$pResponse->qty}} 
                                                                              <input type="hidden" class="additonal-price" value="{{$pResponse->price}}"> 
                                                                            @else
                                                                               No Price Set. 
                                                                            @endif
                                                                          </span>
                                                                            <input type="hidden" class="additonal-qty" value="{{ $pResponse->qty}}"> 
                                                                            <input type="hidden" class="ch_id" value="{{$allCharges['ch_id']}}"> 
                                                                            <input type="hidden" class="additonal-row-id" value="{{$pResponse->id}}"> 
                                                                            
                                                                          <a href="javascript:" class="edit-additional-charges"> (Edit)</a>
                                                                      </td>
                                                                      
                                                                      <td><strong class="visible-xs">Divided</strong>{{$allCharges["divided"]}}</td>
                                                                      <td><strong class="visible-xs">Price</strong>@if(isset($pResponse->price)) <div class="priceinqty"><?php $additionalPrice = $additionalPrice + (($pResponse->price*$pResponse->qty)/$allCharges["divided"]) ?>($){{ (($pResponse->price*$pResponse->qty)/$allCharges["divided"]) }}</div> @else No Price Set. @endif</td>
                                                                  </tr>
                                                                  <?php } ?>
                                                                @endif
                                                            @endforeach
                                                          @php
                                                              $additionalExisting = implode(',',$additionalExistingids);
                                                          @endphp
                                                          <input type="hidden" value="{{$additionalExisting}}" name="additionalExistingid[{{$class->id}}]" class="additionalExistingids-{{$class->id}}">

                                                          @endif
                            
                                                          <?php } ?>
                                                        @endforeach
                                                      @else
                                                        <tr class="tr-row additiona-charges-row">
                                                           <td colspan="5"> <!-- No additional charges. --></td>
                                                        </tr>
                                                      @endif
                                                      @php
                                                        $myOrderSuppliesobj=new stdClass();
                                                        $myOrderSuppliesobj->suppliesPrice=$additionalPrice;
                                                      @endphp
                                                      @include('shows.billing.partials.orderedSupplies',['myOrderSuppliesobj'=>$myOrderSuppliesobj,"is_admin"=>1])
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
                                              
                                            @endphp
                                            @include('shows.billing.partials.stallprice',['myStallobj'=>$myStallobj])
                                            @php $stallPrice=twodecimalformate($myStallobj->stallPrice) @endphp
                                        </div>
                                    
                                     <div class="Totals">
                                              
                                              <div class="row p-3">
                                                <div class="col-sm-9"> <div class="border-bottom pb-2"><b> Additional Pricing: </b></div></div>
                                                <div class="col-sm-3 addAdditionalPrice"><div class="border-bottom pb-2">($) {{twodecimalformate($additionalPrice)}}<input type="hidden" class="additionalPrice" name="Invoices[{{$horses->horse_id}}][additional_price]" value="{{twodecimalformate($additionalPrice)}}"></div></div>
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
                                                @if(isset($MS->template->category) && $MS->template->category != TRAINER)
                                              <div class="row p-3">
                                                  <div class="col-sm-9"><div class="border-bottom pb-2"><b> Total + Split Charges: </b></div></div>
                                                  <div class="col-sm-3 addTotalPrice"><div class="border-bottom pb-2">($) {{$total}} + {{twodecimalformate($splitPrice)}} = {{twodecimalformate($total = $total+$splitPrice)}}</div></div>
                                              </div>
                                                @endif
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
                                              <?php
                                               //updateInvoiceDetail($total,$invoice->id);
                                               // to sync invoice total with the invoice table
                                               ?>
                                            

                                            </div>
                                            @php 
                                              $PIOchecked = ""; 
                                              if(getPayInOfficeStatus($show_id,$horses->horse_id)){
                                                $PIOchecked = "Pay In Cash <br>";
                                              }
                                              @endphp
                                           <div class="col-sm-12">
                                             <div class="offset-9 col-sm-3">
                                               <span style="color:red;">{!! $PIOchecked !!}</span>
                                               <a type="button" class="btn btn-secondary" href="javascript:" 
                                               onclick="PayInOffice('{{$horses->horse_id}}','{{$show_id}}','{{$assetTotal}}','{{$additionalPrice}}','{{$royaltyFinal}}','{{$prizeWon}}','{{$splitPrice}}','{{$total}}','{{$divisionTotal}}','{{$stallPrice}}','{{$total_taxis}}')">Paid in Cash</a>
                                               
                                             </div>
                                           </div>
                                               <input type="hidden" name="Invoices[{{$horses->horse_id}}][horse_id]" value="{{$horses->horse_id}}">
                                               <input type="hidden" name="Invoices[{{$horses->horse_id}}][show_id]" value="{{$show_id}}">
                                           <div class="row col-sm-12">
                                             <div class="col-sm-8">
                                               {!! Form::open(['url'=>'shows/invoice/add/comment','method'=>'post','class'=>'form-horizontal dropzone targetvalue' ]) !!}
                                                 <?php $commentG = getInvoiceAddedComment($show_id,$horses->horse_id); ?>
                                                 <span>
                                                      <label>Comments: </label>
                                                      @if(isset($commentG->comment))
                                                        <textarea class="form-control whiteback" name="invoice_comments">{{$commentG->comment}}</textarea>
                                                        <input type="hidden" name="HIC_id" value="{{$commentG->id}}">
                                                      @else
                                                        <textarea class="form-control whiteback" name="invoice_comments"></textarea>
                                                      @endif
                                                        <input type="hidden" name="horse_id" value="{{$horses->horse_id}}">
                                                  </span>
                                              
                                               <input type="hidden" name="show_id" value="{{$show_id}}">
                                               <br>
                                                <input type="submit" class="btn btn-primary btn-small" value="Save Comment">
                                                {!! Form::close() !!}
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

                                      <div id="add_addional_charges-{{$class->id}}" class="modal fade" role="dialog">
                                          <div class="modal-dialog">
                                              <!-- Modal content-->
                                              <div class="modal-content">
                                                  <div class="modal-header">
                                                      <h4>Add Additional Charges</h4>
                                                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                  </div>
                                                  {!! Form::open(['url'=>'shows/invoice/addAddionalCharges/'.$class->id,'method'=>'post','class'=>'form-horizontal']) !!}
                                                  <div class="row">
                                                      <div class="col-sm-12">
                                                          <table class="table primary-table">
                                                              <thead class="hidden-xs">
                                                              <tr>
                                                                  <th>Select</th>
                                                                  <th>Title</th>
                                                                  <th>Description</th>
                                                                  <th>Amount</th>
                                                                  <th>Quantity</th>
                                                              </tr>
                                                              </thead>
                                                              <tbody>
                                                              @if(sizeof($additional_charges)>0)
                                                                  @foreach($additional_charges as $pResponse)
                                                                      <?php $serial = $loop->index + 1; ?>
                                                                      @if(!in_array($pResponse->id,$additionalExistingids))
                                                                          <tr>
                                                                              <td>
                                                                                  <label>
                                                                                      <input type="checkbox" value="{{$pResponse->id}}" name="charges_selected[{{$class->id}}][{{$pResponse->id}}]" class="charges_selected_check">
                                                                                      <span>&nbsp</span>
                                                                                  </label>
                                                                              </td>
                                                                              <td><strong class="visible-xs">Title</strong>{{$pResponse->title}} </td>

                                                                              <td><strong class="visible-xs">Description</strong>{{$pResponse->description}}</td>

                                                                              <td><strong class="visible-xs">Amount</strong>${{$pResponse->amount}}</td>

                                                                              <td><strong class="visible-xs">Quantity</strong><input class="quantity_selected" type="number" name="quantity_selected[{{$pResponse->id}}]" value="0" min="0"> </td>
                                                                          </tr>
                                                                          @endif
                                                                          @endforeach
                                                                      @endif
                                                              </tbody></table></div></div>
                                                  <div class="modal-footer">

                                                      <input type="hidden" value="{{$class->id}}" name="class_id" class="class_id">


                                                      <button type="submit" class="btn btn-default">Submit</button>
                                                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                  </div>
                                                  {!! Form::close() !!}
                                              </div>

                                          </div>
                                      </div>


                                    @endforeach
                                @endif
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
                                <?php $additionalArray=array(); $CHArray=array(); $additionalExistingids=array(); $paid_on=$horses->paid_on; $invoice_status=PAID; 
                                $divisionClassesInc=array();$assetTotal = 0;?>
                                <div class="row horse-invoice">
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
                                                   <td><strong class="visible-xs">Price</strong>@if(isset($class->price)) ($){{twodecimalformate($class->price+$divPenalty['totalPenalty'] )}} <?php $divisionTotal= $divisionTotal+$class->price+$divPenalty['totalPenalty']; ?> @else No Price Set. @endif</td>
                                                  
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
                                             @if(isset($MS->template->category) && $MS->template->category == TRAINER)
                                              <h3>Training Services</h3>
                                            @else
                                              <h3>Classes</h3>
                                              @endif
                                            <div class="rr-datatable">
                                                <table id="crudTabl" class="table primary-table">
                                                  <thead class="hidden-xs">
                                                     <tr>
                                                       @if(isset($MS->template->category) && $MS->template->category == TRAINER)
                                                        <th>Training Services</th>
                                                         <th>Qty x Price</th>
                                                         @else
                                                        <th>Classes</th>
                                                        <th>Code</th>
                                                        <th># of Entries</th>
                                                         <th>Penalty</th>
                                                         @endif
                                                        <th>Registered</th>
                                                        <th >Price</th>
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
                                                        @if(isset($MS->template->category) && $MS->template->category == TRAINER)
                                                        <td><strong class="visible-xs">Qty x Price</strong>
                                                        {{$class->horse_quantity}} x {{$class->price}}</td>
                                                        @endif
                                                        @if(isset($MS->template->category) && $MS->template->category != TRAINER)
                                                       <td><strong class="visible-xs">Code</strong> {{ GetSpecificFormField($class->pclass,"Code") }} </td>
                                                        <td><strong class="visible-xs"># of Entries </strong> {{getClassParticipants($class->class_id,$show_id)}} </td>
                                                       <td><strong class="visible-xs">Penalty</strong> 
                                                        <?php 
                                                            if (isset($class->Joining_penalty)) {
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
                                                           @if(isset($MS->template->category) && $MS->template->category == TRAINER)
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
                                                              if(isset($MS->template->category) && $MS->template->category == TRAINER)
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
                                                            <th>Qty x Price </th>
                                                            <th>Split</th>
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
                                                        <th>Qty x Price</th>
                                                        <th>Divided</th>
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
                                                                    <?php $additionalPrice = $additionalPrice + (($pResponse->price*$pResponse->qty)/$allCharges["divided"]) ?>($){{ number_format((($pResponse->price*$pResponse->qty)/$allCharges["divided"]),2) }}</div> @else No Price Set. @endif</td>
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
                                             @php 
                                              $gPIOS = getPayInOfficeStatus($show_id,$horses->horse_id,$paid_on);
                                              if($gPIOS){
                                              @endphp
                                              <div class="col-sm-12">
                                               <div class="offset-9 col-sm-3">
                                                 <a type="button" class="btn btn-secondary" href="javascript:" 
                                                  onclick="editPayInOffice('{{$gPIOS->id}}','{{json_encode($gPIOS->charge_id)}}')">Details</a>
                                                 <span style="color:red;">Paid in Cash By User <br></span>
                                               </div>
                                             </div>

                                              @php
                                              }
                                              @endphp 
                                              
                                             
                                              <input type="hidden" name="Invoices[{{$horses->horse_id}}][horse_id]" value="{{$horses->horse_id}}">
                                            <input type="hidden" name="Invoices[{{$horses->horse_id}}][show_id]" value="{{$show_id}}">
                                            <input type="hidden" name="Invoices[{{$horses->horse_id}}][total_taxis]" value="{{$total_taxis}}">
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
                                </div>
                                </div>
                              @else
                                        <div class="row" style="margin-top: 20px;text-align: center;"><p>No Paid invoice exist</p></div>
                               @endif

                                </div>
                            </div>
                        </div>
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
                    <h4>Prize Claim Form 1099</h4>
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
                    <input type="hidden" name="claim_id" id="claim_id">
                    <button type="button"  onclick="exportClaimDetails()" class="btn btn-success">Export Claim Details</button>

                    <button type="submit" class="btn btn-default">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
                {!! Form::close() !!}

            </div>

        </div>
    </div>
     <div id="paid_in_office_popup" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Are you sure you want to mark it as paid?</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                {!! Form::open(['url'=>'shows/app-owner/payinoffice','method'=>'post','class'=>'form-horizontal']) !!}

                <div class="modal-body">
                    <div class="row" style="width: 80%; margin-left: 30px;">
                      
                        <div class="col-md-9  p-0  text-left">
                            <label>Details</label>
                            <textarea name="payinoffice_details" class="form-control payinoffice_details"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">

                    <input type="hidden" name="show_id" class="show_id">
                    <input type="hidden" name="horse_id" class="horse_id">
                    <input type="hidden" name="invoice_owner_id" value="{{$user_id}}">


                    <input type="hidden" name="assets_price" class="assetTotal">
                    <input type="hidden" name="additional_price" class="additionalPrice">
                    <input type="hidden" name="royalty" class="royaltyFinal">
                    <input type="hidden" name="prize_won" class="prizeWon">
                    <input type="hidden" name="split_charges" class="splitPrice">
                    <input type="hidden" name="total_price" class="total">
                    <input type="hidden" name="division_price" class="divisionTotal">
                    <input type="hidden" name="stall_price" class="stallPrice">
                    <input type="hidden" name="total_taxis" class="total_taxis">
                   
                    
                    <button type="submit" class="btn btn-default">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
                {!! Form::close() !!}

            </div>

        </div>
    </div>
     <div id="editpaid_in_office_popup" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Edit Paid Invoice</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                {!! Form::open(['url'=>'shows/app-owner/payinoffice/edit','method'=>'post','class'=>'form-horizontal']) !!}

                <div class="modal-body">
                    <div class="row" style="width: 80%; margin-left: 30px;">
                      
                        <div class="col-md-9  p-0  text-left">
                            <label>Details</label>
                            <textarea name="payinoffice_details" class="form-control payinoffice_details"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">

                    <input type="hidden" name="billing_id" class="billing_id">
                    
                    
                    <button type="submit" class="btn btn-default">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
                {!! Form::close() !!}

            </div>

        </div>
    </div>



    @endsection

@section('footer-scripts')
  <script src="{{ asset('/js/scroll-position.js') }}"></script>
  <script src="{{ asset('/js/shows/already-paid-invoice.js') }}"></script>
  <script src="{{ asset('/js/shows/app-owner-invoice.js') }}"></script>
   <script src="{{ asset('/js/custom-tabs-cookies-new.js') }}"></script>
  <script>

      $(".form-horizontal input[type=text]").attr("readonly", 'readonly');


  </script>
  @include('layouts.partials.datatable')
@endsection