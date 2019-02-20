@extends('layouts.equetica2')
@section('main-content')

    <div class="main-contents">
        <div class="container-fluid">

            @php
                $title = $show->title;
                $added_subtitle = '';
            @endphp
            @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle,'remove_search'=>1])

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
            <div class="col-sm-12">


            </div>
            </div>
            <div class="row">
            <div class="col-sm-12 asset-participate-form">
            {!! Form::open(['url'=>'shows/participate','method'=>'post','files'=>true,'class'=>'form-horizontal dropzone targetvalue',"onsubmit"=>"return confirm('Is that all you want to participate in?');"]) !!}
            @if($collection->count() || $parentCollection->count())
            <div class="">
            <input type="hidden" name="step2" value="1">
            <input type="hidden" name="show_id" class="show_id" value="{{$show->id}}">
            <input type="hidden" name="MSR" value="{{$MSR}}">
            <input type="hidden" class="unique-horses" name="unique_horses" value="0">

            <h3>Division / CLasses</h3>
            <div id="indivisual" class="tab-pane">

                  <div class="module-holer rr-datatable indivisual-fixed-y">
                      <table class="table primary-table collaps-on-click">
                        <thead class="hidden-xs">
                           <tr>
                               <th scope="col"    title="Class Number" style="width: 90px;">Class Number</th>
                               <th scope="col"    title="Class Type" style="width: 74px;">Type</th>
                               <th scope="col">Title</th>
                               <th scope="col"    title="Class Code" style="width: 90px;">Code</th>
                               <th scope="col"   title="Jumper Height" style="width: 74px;">Height</th>
                               <th scope="col"    title="Class Price" style="width: 105px;">Price</th>
                               @if($show->show_type=='Western')<th>Pattern</th>@endif
                               <th scope="col"   style="width: 105px;">Horse</th>
                               <th scope="col"  >Rider</th>
                               <th scope="col"   style="width:10%">Est Start Time</th>

                              <!-- <th>Location</th> -->
                              <th class="action" style="width:10%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>

                            @php
                                $divisionId = [];

                                $divisionId =  getShowDivision($show->id);

                                $divisionIds = [];
                            @endphp
                            @if(sizeof($parentCollection)>0)
                                @foreach($parentCollection as $division)
                                    @php $outer = $loop->index + 1;
                                    $divisionIds[]=$division->id;
                                      $description = GetAssetNameFromLabel($division,'Description');
                                    @endphp
                                  @if(in_array($division->id,$divisionId))
                                      <tr class="tr-row division-row" id="{{$division->id}}">
                                        <td colspan="5"><h3><i class="fa fa-plus"></i> {{GetAssetName($division)}} @if($description!=null)<i class="icon-info-sign"
                                                                                                                                                                 data-toggle="tooltip"
                                                                                                                                                                 title="{{GetAssetNameFromLabel($division,'Description')}}"
                                                                                                                                                                 class='tooltip'></i>@endif</h3></td>
                                        <td colspan="1"><span class="table-title">Price</span>@if(isset($division->ShowAssetInvoice))
                                              <div class="parent-price">
                                                <div style="float:left"> ($) {{$division->ShowAssetInvoice->price }}
                                                  <input name="assets[division][{{$outer}}][price]" type="hidden" class="priceSet" value="{{$division->ShowAssetInvoice->price }}">
                                                  <input name="assets[division][{{$outer}}][orignal_price]" type="hidden" class="orignalPriceSet" value="{{$division->ShowAssetInvoice->price }}">
                                                </div>
                                                <div class="horse-assets-select" style="float:left"></div>
                                              </div>
                                              @else No Price Set. @endif
                                        </td>
                                          @if($show->show_type=='Western')
                                              @php $pattern =  GetSpecificFormField($division,"Pattern");  @endphp
                                              <td><span class="table-title">Pattern</span>{{ $pattern == "" ? "N/A" : str_replace('|||','',$pattern) }}</td> @endif

                                          <td colspan="3">
                                          @if($division->primary_required == 1)
                                            <p>You have to select whole division.</p>
                                          @else
                                            <p>You can select individual classes or division as a whole.</p>
                                          @endif
                                        </td>

                                          @if($division->primary_required == 1)
                                          <td colspan="1" class="primary-required">
                                        <div class="form-check">
                                        <label class="form-check-label" style="width: 1em">
                                        <input class="form-check-input" name="assets[division][{{$outer}}][id]" type="checkbox" data-attr="assets-charges" value="{{$division->id}}">
                                            <span>&nbsp</span>
                                            <input name="assets[division][{{$outer}}][orignal_id]" type="hidden" value="{{$division->id}}">

                                        </label></div>

                                          </td>
                                          @else
                                          <td colspan="1" class="primary-not-required">
                                              <div class="form-check">
                                                  <label class="form-check-label">
                                            <input class="form-check-input" name="assets[division][{{$outer}}][id]" type="checkbox" data-attr="assets-charges" value="{{$division->id}}">
                                                      <span>&nbsp</span>
                                            <input name="assets[division][{{$outer}}][orignal_id]" type="hidden" value="{{$division->id}}">

                                                  </label>
                                              </div>
                                          </td>
                                          @endif
                                  </tr>
                                      @endif
                                  @php $serial = 0; @endphp

                                  @foreach($division->subAssets as $pResponse)
                                    @if(isset($pResponse->fields) && $pResponse->SchedulerRestriction->count() != 0)
                                        @php $serial = $serial+1;

                                      $description = GetAssetNameFromLabel($pResponse,'Description');
                                       $isScoringClasses = isScoringClasses($pResponse->id);
                                        @endphp
                                    <tr class="tr-row hiddenRow division-{{$division->id}}" data-div-id="{{$division->id}}">
                                        <td colspan="1" style="width: 90px;"><span class="table-title">Class Number</span> {{GetAssetNameFromLabel($pResponse,'Class Number')}}</td>
                                        <td colspan="1" style="width: 74px;"><span class="table-title">Type</span> {{$pResponse->classType?$pResponse->classType->name:""}}</td>

                                        <td colspan="1"><span class="table-title">Title</span> {{GetAssetName($pResponse)}}  @if($description!=null)<i class="icon-info-sign"
                                                                                                                       data-toggle="tooltip"
                                                                                                                       title="{{GetAssetNameFromLabel($pResponse,'Description')}}"
                                                                                                                       class='tooltip'></i>@endif</td>
                                        <td colspan="1" style="width: 90px;"><span class="table-title">Code</span>{{ GetSpecificFormField($pResponse,"USEF Section Code")  }}</td>
                                        <td colspan="1" style="width: 74px;"><span class="table-title">Jumper Height</span> {{GetAssetNameFromLabel($pResponse,'Jumper Height')}}</td>

                                        <td colspan="1" style="width: 105px;"><span class="table-title">Price</span>
                                          @if($division->primary_required != 1)
                                            @if(isset($pResponse->ShowClassPrice))
                                                  <div class="child-price">
                                                    <div style="float:left" >
                                                    @include('shows.participate.partials.judgesFee')
                                                    <div class="actual-price-set">($) {{getParticipatingPrice($pResponse,$show)}}</div>
                                                      <input name="assets[division][{{$outer}}][innerclasses][{{$serial}}][price]" type="hidden" class="priceSet" value="{{getParticipatingPrice($pResponse,$show)}}">
                                                      <input name="assets[division][{{$outer}}][innerclasses][{{$serial}}][orignal_price]" type="hidden" class="orignalPriceSet" value="{{getParticipatingPrice($pResponse,$show)}}">
                                                    </div>
                                                    <div class="horse-assets-select" style="float:left"></div>
                                                  </div>
                                                  @else No Price Set.
                                            @endif
                                          @endif
                                        </td>
                                        <td colspan="1" style="width: 105px;"><span class="table-title">Horses</span>
                                          @if(sizeof($OwnerHorses)>0)
                                          <select name="assets[division][{{$outer}}][innerclasses][{{$serial}}][horses][]" data-id="{{$pResponse->id}}" data-serial="{{$serial}}" data-division="{{$outer}}" class="selectpicker selectPickerDiv form-control Horses-{{$pResponse->id}}" data-live-search-placeholder="Search" title="Select Horse" data-live-search="true" multiple>

                                            @foreach($OwnerHorses as $horse)
                                              @php
                                                $alredyReg = ExistingHorses($participatedHorses,$horse->id,$pResponse->id);
                                                if(!$alredyReg){ @endphp
                                                  <option value="{{$horse->id}}">{{GetAssetName($horse)}}</option>
                                               @php } @endphp
                                              @endforeach
                                          </select>
                                          @else
                                            <p>You have not entered any horse asset in your horse application</p>
                                          @endif
                                        </td>
                                        <td colspan="1"><span class="table-title">Rider</span>

                                            <div class="division-selectRiders-{{$pResponse->id}}">
                                                <label>Select Rider</label>
                                            </div>
                                           <div class="ridersContainer" style="display: none">
                                            @if(sizeof($riderHorses)>0)
                                                <select name="assets[division][{{$outer}}][innerclasses][{{$serial}}]['riders'][]" disabled class="rider-drp-select" onchange="getRiderFeed($(this),'{{$pResponse->id}}')">
                                                 <option value=""> Select Rider</option>
                                                    @foreach($riderHorses as $rider)
                                                        <option value="{{$rider->id}}">{{GetAssetName($rider)}}</option>
                                                    @endforeach
                                                </select>
                                            @else
                                                <p>You have not entered any rider asset in your horse application</p>
                                            @endif
                                           </div>
                                        </td>
                                         <td colspan="1" style="width:10%"><span class="table-title">Estimated start time</span>{{ getEstimateStartTime($pResponse->SchedulerRestriction)  }}</td>

                                          <td colspan="1" class="pull-left" style="width:10%">
                                              <span class="table-title">Actions</span>
                                            
                                             @if($division->primary_required == 1)
                                                      <div class="form-check">
                                                      <label class="form-check-label">
                                                        <input name="assets[division][{{$outer}}][innerclasses][{{$serial}}][id]" type="checkbox" data-belong="wholedivisions" 
                                                          onchange="getScoringClasses($(this),'{{$pResponse->id}}','{{json_encode($divisionIds)}}','{{$pResponse->is_required_point_selection}}','{{count($isScoringClasses)}}')"
                                                          class="asset_{{$pResponse->id}}" data-attr="assets-charges" value="{{$pResponse->id}}" disabled="disabled">
                                                        <span>&nbsp</span>
                                                      </label>
                                                      <input type="hidden" name="assets[division][{{$outer}}][innerclasses][{{$serial}}][id]" class="hidden-disabled-val" value="{{$pResponse->id}}" disabled="disabled">
                                                    @else
                                                       <div class="form-check">
                                                      <label class="form-check-label">
                                                        <input name="assets[division][{{$outer}}][innerclasses][{{$serial}}][id]" 
                                                          onchange="getScoringClasses($(this),'{{$pResponse->id}}','{{json_encode($divisionIds)}}','{{$pResponse->is_required_point_selection}}','{{count($isScoringClasses)}}')"
                                                        class="primary-not-required-child asset_{{$pResponse->id}}" data-belong="divisions" type="checkbox" data-attr="assets-charges" value="{{$pResponse->id}}">
                                                         <span>&nbsp</span>
                                                      </label>
                                                      </div>
                                                    @endif
                                           @php
                                              $selector = "assets[division][$outer][innerclasses]";
                                            @endphp
                                            @include('shows.participate.partials.qualifying',['myPrizeobj'=>1,'keys'=>$serial,'selector'=>$selector,'divisionsQulif'=>1])

                                            <input name="assets[division][{{$outer}}][innerclasses][{{$serial}}][primary_required]" type="hidden" value="{{$division->primary_required}}">

                                            <input type="hidden" name="template_id" value="{{$pResponse->template_id}}">
                                        </td>
                                    </tr>
                                    @endif
                                  @endforeach
                                  <input name="assets[division][{{$outer}}][total_classes]" type="hidden" value="{{$serial}}">

                                @endforeach
                            @endif
                        </tbody>
                    </table>
                  </div>
                  <div class="Totals row">
                      <div class="col-md-7">&nbsp;</div>
                       <div class="col-md-5 pull-right">
                           <div class="row">

                          <div class="col-md-3 border-bottom"><b> Amount: </b></div>
                          <div class="col-md-9 addAssetPrice border-bottom">($) 0<input type="hidden" class="AssetsPrice" name="assets_price" value="0"></div>
                       </div>
                       </div>
                  </div>
            </div>

            <h3>Classes</h3>
            <div id="indivisual" class="tab-pane">


                <div class="table-responsive  rr-datatable indivisual-fixed-y">
                    <table class="table  primary-table mt-10 custom-responsive-md Datatable_nopagination">
                        <thead class="hidden-xs">
                           <tr>
                            <th scope="col"  title="Class Number" style="width: 90px;">Class Number</th>
                            <th scope="col"  title="Class Type" style="width: 74px;">Type</th>
                            <th scope="col" style="width: 180px;">Title</th>
                            <th scope="col" title="Class Code" style="width: 90px;">Code</th>
                            <th scope="col" title="Jumper Height" style="width: 74px;">Height</th>
                            <th scope="col"  title="Class Price" style="width: 105px;">Price</th>
                            @if($show->show_type=='Western')<th>Pattern</th>@endif
                            <th scope="col" style="width: 120px;">Horse</th>
                            <th scope="col" style="width: 120px;">Rider</th>
                            <th  scope="col" style="width:10%" title="Estimated Start time">Start time</th>
                              <!-- <th>Location</th> -->
                              <th style="width:10%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(sizeof($collection)>0)

                                @php
                                $scoringSubPrice = 0;
                                @endphp

                                @foreach($collection as $pResponse)
                                    <?php
                                      $serial = $loop->index + 1;
                                      $roleName = '';
                                     $isScoringClasses = isScoringClasses($pResponse->id);

                                     if(in_array($pResponse->id,$isScoringClasses)){
                                         if(isset($pResponse->ShowAssetInvoice)){
                                         $scoringSubPrice = $scoringSubPrice + getParticipatingPrice($pResponse,$show);
                                        }
                                     }

                                    $description = GetAssetNameFromLabel($pResponse,'Description');


                                    ?>
                                    @if($pResponse->fields)
                                        <tr class="tr-row">
                                        <td> <span class="table-title">Class Number</span> {{GetAssetNameFromLabel($pResponse,'Class Number')}}</td>
                                        <td> <span class="table-title">Type</span> {{$pResponse->classType?$pResponse->classType->name:""}}</td>
                                        <td> <span class="table-title">Title</span> {{GetAssetName($pResponse,0)}} @if($description!=null)<i class="icon-info-sign"
                                                                                                                                            data-toggle="tooltip"
                                                                                                                                            title="{{GetAssetNameFromLabel($pResponse,'Description')}}"
                                                                                                                                            class='tooltip'></i>@endif</td>
                                        <td> <span class="table-title">Code</span>{{ GetSpecificFormField($pResponse,"USEF Section Code")  }}</td>
                                        <td> <span class="table-title">Jumper Height</span> {{GetAssetNameFromLabel($pResponse,'Jumper Height')}}</td>

                                            <td> <span class="table-title">Price</span>@if(isset($pResponse->ShowClassPrice))
                                              <div style="float:left" class="single-class">
                                                  @include('shows.participate.partials.judgesFee')
                                                  <div class="actual-price-set">($){{getParticipatingPrice($pResponse,$show) }}</div>
                                                  <input name="assets[{{$serial}}][price]" type="hidden" class="priceSet" value="{{getParticipatingPrice($pResponse,$show) }}" disabled="disabled">
                                                  <input name="assets[{{$serial}}][orignal_price]" type="hidden" class="orignalPriceSet" value="{{getParticipatingPrice($pResponse,$show) }}" disabled="disabled">
                                              </div>
                                              <div class="horse-assets-select" style="float:left"></div>
                                              @else No Price Set. @endif
                                        </td>
                                        @if($show->show_type=='Western')
                                                @php $pattern =  GetSpecificFormField($pResponse,"Pattern");  @endphp
                                                <td><strong class="visible-xs">Pattern</strong>
                                            {{ $pattern == "" ? "N/A" : str_replace('|||','',$pattern) }}
                                        </td>
                                        @endif

                                        {{--onchange="getTrainers($(this),'{{$pResponse->id}}','{{$serial}}')"--}}

                                        <td> <span class="table-title">Horses</span>
                                          @if(sizeof($OwnerHorses)>0)
                                          <select name="assets[{{$serial}}][horses][]" data-id="{{$pResponse->id}}" data-serial="{{$serial}}" class="selectpicker selectPickerMain form-control Horses-{{$pResponse->id}}" data-live-search-placeholder="Search" title="Select Horse" data-live-search="true" multiple>
                                            @foreach($OwnerHorses as $horse)
                                              <?php $alredyReg = ExistingHorses($participatedHorses,$horse->id,$pResponse->id);
                                                if(!$alredyReg){ ?>
                                                  <option value="{{$horse->id}}">{{GetAssetName($horse)}}</option>
                                                <?php } ?>
                                            @endforeach
                                          </select>
                                          @else
                                            <p>You have not entered any horse asset in your horse application</p>
                                          @endif
                                        </td>
                                        <td> <span class="table-title">Rider</span>

                                            <div class="selectRiders-{{$pResponse->id}}">
                                                <label>Select Rider</label>
                                            </div>
                                           <div class="ridersContainer" style="display: none">
                                            @if(sizeof($riderHorses)>0)
                                                <select name="assets[{{$serial}}]['riders'][]" disabled class="rider-drp-select" onchange="getRiderFeed($(this),'{{$pResponse->id}}')">
                                                 <option value=""> Select Rider</option>
                                                    @foreach($riderHorses as $rider)
                                                        <option value="{{$rider->id}}">{{GetAssetName($rider)}}</option>
                                                    @endforeach
                                                </select>
                                            @else
                                                <p>You have not entered any rider asset in your horse application</p>
                                            @endif
                                           </div>
                                        </td>

                                    <!--  <td><strong class="visible-xs">Date</strong>{{ GetSpecificFormField($pResponse,"Date")  }}</td>
                                        <td><strong class="visible-xs">Location</strong>{{ GetSpecificFormField($pResponse,"Location")  }}</td>
                                         -->
                                         <td> <span class="table-title">Estimated start time</span>{{ getEstimateStartTime($pResponse->SchedulerRestriction)  }}</td>
                                         <td>
                                             <span class="table-title">Actions</span>

                                            @php
                                            if(count($divisionIds)>0)
                                            $divisionid = json_encode($divisionIds);
                                            else
                                            $divisionid = 0;

                                            @endphp
                                            <div class="col-sm-3" style="padding-left: 0px;">

                                                <div class="form-check">
                                                    <label class="form-check-label" style="width: 1em">
                                                        <input name="assets[{{$serial}}][id]"
                                                               onchange="getScoringClasses($(this),'{{$pResponse->id}}','{{$divisionid}}','{{$pResponse->is_required_point_selection}}','{{count($isScoringClasses)}}')"
                                                               class="form-check-input asset_{{$pResponse->id}}" type="checkbox"  data-attr="assets-charges" value="{{$pResponse->id}}">
                                                        <span>&nbsp</span>

                                                    </label>
                                                </div>

                                           <input type="hidden" name="template_id" value="{{$pResponse->template_id}}">
                                            </div>
                                                <div style="padding-left: 0px;" class="col-sm-9 division-{{$pResponse->id}}"></div>
                                                @include('shows.participate.partials.qualifying',['myPrizeobj'=>1,'keys'=>$serial])
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                  </div>
                  <div class="Totals row">
                      <div class="col-md-7">&nbsp;</div>
                       <div class="col-md-5 pull-right">
                            <div class="row">
                          <div class="col-md-3 border-bottom"><b> Amount: </b></div>
                          <div class="col-md-8 addAssetPrice border-bottom">($) 0<input type="hidden" class="AssetsPrice" name="assets_price" value="0"></div>
                       </div>
                       </div>
                  </div>
            </div>

            <h3>Additional Charges</h3>
            <div id="indivisuals" class="tab-pane additional-c-wraper">

                <div class="table-responsive  rr-datatable indivisual-fixed-y">
                    <table  class="table table-line-braker primary-table mt-10 custom-responsive-md Datatable_nopagination">
                        <thead class="hidden-xs">
                           <tr>
                              <th scope="col">Title</th>
                              <th scope="col">Description</th>
                              <th scope="col">Price</th>
                              <th scope="col" style="width:1%"></th>
                              <th scope="col">Price x Qty</th>
                              <th class="" style="width:22%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(sizeof($additional_price)>0)
                                @foreach($additional_price as $pResponse)

                                    @if($pResponse->required == 1)
                                    <?php
                                      $serial = $loop->index + 1;
                                      $roleName = '';
                                    ?>
                                    <tr class="tr-row additiona-charges-row">
                                        <!-- <td>{{ $serial }}</td> -->
                                        <td> <span class="table-title">Title</span> {{$pResponse->title}}</td>
                                        <td> <span class="table-title">Description</span>{{ $pResponse->description }}</td>
                                        <td> <span class="table-title">Price</span>@if(isset($pResponse->amount)) ($){{$pResponse->amount }}<input type="hidden" name="additional[{{$serial}}][price]" class="priceSet" value="{{$pResponse->amount}}"> @else No Price Set. @endif</td>
                                        <td> <span class="table-title">Quantity</span><input type="number" name="additional[{{$serial}}][qty]" class="col-sm-8 numberQty hidden" min="1" max="999" value="1" placeholder="qty" data-unit-price="{{$pResponse->amount}}"></td>
                                        <td> <span class="table-title">Price</span>@if(isset($pResponse->amount)) <div class="priceinqty">($) {{$pResponse->amount }}</div> @else No Price Set. @endif</td>
                                        <td class="pull-left">
                                            <span class="table-title">Actions</span>

                                                <div class="form-check">
                                                    <label class="form-check-label" style="width: 1em">
                                                        <input name="additional[{{$serial}}][id]" class="form-check-input checkbox-additional" data-attr="additional-charges" type="checkbox" value="{{$pResponse->id}}" @if($pResponse->required == 1) checked="checked" disabled="disabled" @endif >
                                                        <span>&nbsp</span>

                                                    </label>
                                                </div>

                                             @if($pResponse->required == 1)
                                             <input name="additional[{{$serial}}][id]"  type="hidden" value="{{$pResponse->id}}">
                                             @endif
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                  </div>
               <div class="Totals row">
                   <div class="col-sm-12">
                       <div class="col-sm-5 pull-right">
                           <div class="row">

                      <div class="col-sm-5 border-bottom"><b> Additional Pricing: </b></div>
                      <div class="col-sm-7 addAdditionalPrice border-bottom">($) 0<input type="hidden" class="additionalPrice" name="additional_price" value="0"></div>
                    </div>
                   </div>
                   </div>

                   <div class="col-sm-12">
                     <div class="col-sm-5 pull-right">
                         <div class="row">

                         <div class="col-sm-5 border-bottom"><b> Total: </b></div>
                      <div class="col-sm-7 addTotalPrice border-bottom">($) 0<input type="hidden" class="totalPrice" name="total_price" value="0"></div>
                        <div class="col-sm-8">

                            <div class="form-group">
                            <div class="form-check">
                                <label class="form-check-label" style="width: 1em">
                                 <input name="allow_trainer_register" class="trainer_checkbox form-check-input" type="checkbox" data-attr="assets-charges" value="1">
                                    <span>&nbsp</span>
                                </label>
                                <b>Allow Trainer To Register for you </b>
                            </div>
                        </div>
                        </div>
                         </div>
                        <div class="col-sm-6 mb-20 pl-0">
                            <input type="submit" class="btn btn-primary checkout" value="Participate"></div>
                      </div>
                   </div>

               </div>

            </div>
            </div>
            @else
            <div class="">
            <div class="col-lg-5 col-md-5 col-sm-6">{{NO_CLASSES_RESPONSE}}</div>
            </div>
            @endif
            {!! Form::close() !!}
            </div>
            </div>
            <!-- Tab containing all the data tables -->
            </div>

@endsection

@section('footer-scripts')
<script type="text/javascript">
localStorage.setItem('lastTab', "#activity");
</script>
    @include('layouts.partials.datatable')

<script src="{{ asset('/js/shows/classes-pricing.js') }}"></script>

<script>

    $('.selectpicker').on('changed.bs.select', function (e, clickedIndex, newValue, oldValue) {

        var id = $(e.target).data('id');
        var serial = $(e.target).data('serial');
        var outer = $(e.target).data('division');
        var horse_id =$(e.target).find('option').eq(clickedIndex).val();
        var show_id = $(".show_id").val();

       // console.log(horse_id);
      var respond =  checkShowRestriction($(e.target),id,serial,horse_id,clickedIndex,outer,show_id);

     });

</script>

<style>
    .dataTables_filter{ float: right; margin-right: 56px; display: block!important}
    .select-selectpicker{ width: 1px!important;}

    label { width: 10em; float: left; }
    label.error { float: none; color: red; padding-left: .5em; vertical-align: top; }
    p { clear: both; }
    .submit { margin-left: 12em; }
    em { font-weight: bold; padding-right: 1em; vertical-align: top; }
</style>

@endsection
