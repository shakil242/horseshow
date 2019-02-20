@extends('layouts.equetica2')
@section('main-content')

        <div class="container-fluid">

            @php
                $title = $show->title;
                $added_subtitle = '';
            @endphp
            @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle,'remove_search'=>1])

            <div class="white-board">

                <div class="row">
                    <div class="info text-center">
                        @if(Session::has('message'))
                            <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                        @endif
                    </div>
                </div>

        <div class="row">
          <div class="col-sm-12">
                <div class="asset-participate-form">
                  {!! Form::open(['url'=>'shows/trainer/participate-for-rider','method'=>'post','files'=>true,'class'=>'form-horizontal dropzone targetvalue']) !!}
                  @if($collection->count() || $parentCollection->count())
                  <?php $regAsset = getRegisteredAssets($registration);
                    //dd($regAsset);
                   ?>
                  <input type="hidden" name="step2" value="1">
                  <input type="hidden" name="show_id" value="{{$show->id}}">
                  <input type="hidden" name="actual_user_id" value="{{$user_id}}">
                  <input type="hidden" name="reg_id" value="{{$reg_id}}">
                  <input type="hidden" class="unique-horses" name="unique_horses" value="{{$registration->unique_horses}}">


                  <h3 {{($templateType==TRAINER)?'style=display:none':''}}>Division / CLasses</h3>

                  <div  {{($templateType==TRAINER)?'style=display:none':''}} id="indivisual" class="tab-pane division-container">

                      <div class="table-responsive  rr-datatable indivisual-fixed-y">
                          <table class="table primary-table collaps-on-click">
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
                                  <th scope="col" style="width: 120px;">Riders</th>
                                  <th  scope="col" style="width:10%" title="Estimated Start time">Start time</th>
                                  <!-- <th>Location</th> -->
                                  <th style="width:10%">Actions</th>
                              </tr>
                              </thead>
                              <tbody>

                                @php
                                    $divisionId = [];

                                    $divisionId =  getShowDivision($show->id);

                                    $divisionIds = [];
                                @endphp
                                    @if(sizeof($parentCollection)>0)
                                      <?php $divTotal=0; ?>
                                        @foreach($parentCollection as $division)
                                            @php $outer = $loop->index + 1;
                                            $divisionIds[]=$division->id;

                                              $description = GetAssetNameFromLabel($division,'Description');


                                            @endphp


                                            <?php $outer = $loop->index + 1;  $classexist = false;?>




                                          <tr class="tr-row parent-div-row" id="{{$division->id}}">
                                                <td colspan="5"><h3><i class="fa fa-plus"></i> {{GetAssetName($division)}} @if($description!=null)<i class="icon-info-sign"
                                                                                                                                                                         data-toggle="tooltip"
                                                                                                                                                                         title="{{GetAssetNameFromLabel($division,'Description')}}"
                                                                                                                                                                         class='tooltip'></i>@endif</h3></td>
                                                <td colspan="1"><span class="visible-xs">Price</span>@if(isset($division->ShowAssetInvoice))
                                                      <div class="parent-price">
                                                        <div style="float:left"> ($) {{$division->ShowAssetInvoice->price}}
                                                          <input name="assets[division][{{$outer}}][price]" type="hidden" class="priceSet" value="{{$division->ShowAssetInvoice->price }}">
                                                          <input name="assets[division][{{$outer}}][orignal_price]" type="hidden" class="orignalPriceSet" value="{{$division->ShowAssetInvoice->price }}">
                                                        </div>
                                                        <div class="horse-assets-select" style="float:left"></div>
                                                      </div>
                                                      @else No Price Set. @endif
                                                </td>
                                              @if($show->show_type=='Western')

                                                  @php $pattern =  GetSpecificFormField($division,"Pattern");  @endphp

                                                  <td><span class="visible-xs">Pattern</span>{{ $pattern == "" ? "N/A" : $pattern  }}</td>
                                              @endif

                                              <td colspan="3">
                                                  @if($division->primary_required == 1)
                                                    <p>You have to select whole division.</p>
                                                  @else
                                                    <p>You can select individual classes or division as a whole.</p>
                                                  @endif
                                                </td>

                                                  @if($division->primary_required == 1)
                                                  <td colspan="1" class="primary-required">
                                                    <label class="form-check-label">
                                                      <input class="form-check-input" name="assets[division][{{$outer}}][id]" type="checkbox" data-attr="assets-charges" value="{{$division->id}}">
                                                      <span>&nbsp;</span>  
                                                    </label>
                                                    <input name="assets[division][{{$outer}}][orignal_id]" type="hidden" value="{{$division->id}}">

                                                  </td>
                                                  @else
                                                  <td colspan="1" class="primary-not-required">
                                                    <label class="form-check-label">
                                                      <input class="form-check-input" name="assets[division][{{$outer}}][id]" type="checkbox" data-attr="assets-charges" value="{{$division->id}}">
                                                       <span>&nbsp;</span>  
                                                    </label>
                                                    <input name="assets[division][{{$outer}}][orignal_id]" type="hidden" value="{{$division->id}}">

                                                  </td>
                                                  @endif
                                          </tr>
                                            <?php $serial = 0; ?>

                                          @foreach($division->subAssets as $pResponse)
                                            @if(isset($pResponse->fields) && $pResponse->SchedulerRestriction->count() != 0)
                                            <?php $serial = $serial+1;$DivlineTotal=0;
                                            $description = GetAssetNameFromLabel($pResponse,'Description');
                                            $isScoringClasses = isScoringClasses($pResponse->id);
                                            ?>
                                            <tr class="tr-row hiddenRow division-{{$division->id}}" data-div-id="{{$division->id}}" data-batch="{{$outer}}">
                                                <td> <span class="table-title">Class Number</span> {{GetAssetNameFromLabel($pResponse,'Class Number')}}</td>
                                                <td> <span class="table-title">Type</span> {{$pResponse->classType?$pResponse->classType->name:""}}</td>

                                                <td> <span class="table-title">Title</span> {{GetAssetName($pResponse)}}  @if($description!=null)<i class="icon-info-sign"
                                                                                                                                                      data-toggle="tooltip"
                                                                                                                                                      title="{{GetAssetNameFromLabel($pResponse,'Description')}}"
                                                                                                                                                      class='tooltip'></i>@endif
                                                </td>
                                                <td> <span class="table-title">Code</span>{{ GetSpecificFormField($pResponse,"USEF Section Code")  }}</td>
                                                <td> <span class="table-title">Jumper Height</span> {{GetAssetNameFromLabel($pResponse,'Jumper Height')}}</td>

                                                <td> <span class="table-title">Horses</span>

                                                  @if(sizeof($OwnerHorses)>0)
                                                    <?php
                                                    if(isset($regAsset['division']) && isset($regAsset['division'][$outer]["innerclasses"])){

                                                      $keys = recursive_array_search("$pResponse->id", $regAsset['division'][$outer]["innerclasses"]);
                                                    }else{
                                                      $keys =null;
                                                    }

                                                     ?>
                                                    <?php  $classexist =false; ?>
                                                    @if(isset($regAsset['division'][$outer]["innerclasses"][$keys]['id']) && $regAsset['division'][$outer]["innerclasses"][$keys]['id'] == $pResponse->id)
                                                      <?php
                                                      $regAsset['division'][$outer]["innerclasses"][$keys]['id'];
                                                        $classexist = true;
                                                        //$assetName= "assets[".$serial."][horses][]";
                                                        $assetName= "assets[division][".$outer."][innerclasses][".$serial."][horses][]";
                                                        echo getHorseNamesfromIDTrainer($reg_id,$pResponse->id,$assetName); ?>
                                                    @endif
                                                    <select name="assets[division][{{$outer}}][innerclasses][{{$serial}}][horses][]" data-id="{{$pResponse->id}}" data-serial="{{$serial}}" data-division="{{$outer}}" class="selectpicker form-control Horses-{{$pResponse->id}}" title="Select Horse" data-live-search="true" multiple>
                                                      @foreach($OwnerHorses as $horse)
                                                      <?php $alredyReg = ExistingHorses($participatedHorses,$horse->id,$pResponse->id);
                                                        if(!$alredyReg){ ?>
                                                          <option value="{{$horse->id}}">{{GetAssetName($horse)}}</option>
                                                        <?php } ?>
                                                      @endforeach
                                                    </select>
                                                    
                                                  @else
                                                      <p>Your has not added any horses in his/her horse app.</p>
                                                  @endif
                                                </td>

                                                 <td> <span class="table-title">Riders</span>

                                                    <div class="division-selectRiders-{{$pResponse->id}}">
                                                        <?php
                                                    $rider =  getRiderNamesfromIDTrainer($reg_id,$pResponse->id);

                                                     if($rider!='')
                                                         echo $rider;
                                                        else {
                                                        ?>
                                                        <label>Select Rider</label>
                                                        <?php } ?>

                                                    </div>
                                                    <div class="ridersContainer" style="display: none">
                                                        @if(sizeof($riderHorses)>0)
                                                            <select class="rider-drp-select" name="assets[{{$serial}}]['riders'][]" disabled onchange="getRiderFeed($(this),'{{$pResponse->id}}')">
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
                                                <td> <span class="table-title">Price</span>
                                                  @if($division->primary_required != 1)
                                                    @if(isset($pResponse->ShowClassPrice))
                                                          <div class="child-price">
                                                            <div style="float:left" >
                                                            <?php $DivlineTotal=getParticipatingPrice($pResponse,$show);
                                                                  if(isset($regAsset['division'][$outer])){
                                                                    $selector = $regAsset['division'][$outer]["innerclasses"];
                                                                  }
                                                            ?>
                                                            @include('shows.participate.partials.judgesFee')
                                                              @if(isset($selector[$keys]['qualifing']) && $selector[$keys]['qualifing']==1)
                                                                  <div class="actual-price-set">($) {{getParticipatingPrice($pResponse,$show)+ $selector[$keys]['qualifing_price'] }}</div>
                                                                  <input name="assets[division][{{$outer}}][innerclasses][{{$serial}}][price]" type="hidden" class="priceSet" value="{{getParticipatingPrice($pResponse,$show) }}">
                                                                  <input name="assets[division][{{$outer}}][innerclasses][{{$serial}}][orignal_price]" type="hidden" class="orignalPriceSet" value="{{getParticipatingPrice($pResponse,$show)+$selector[$keys]['qualifing_price'] }}">

                                                               @else
                                                                  <div class="actual-price-set">($) {{getParticipatingPrice($pResponse,$show)}}</div>
                                                                   <input name="assets[division][{{$outer}}][innerclasses][{{$serial}}][price]" type="hidden" class="priceSet" value="{{getParticipatingPrice($pResponse,$show) }}">
                                                                  <input name="assets[division][{{$outer}}][innerclasses][{{$serial}}][orignal_price]" type="hidden" class="orignalPriceSet" value="{{getParticipatingPrice($pResponse,$show)}}">

                                                               @endif
                                                            </div>
                                                            <div class="horse-assets-select" style="float:left"></div>
                                                          </div>
                                                          @else No Price Set.
                                                    @endif
                                                  @endif
                                                </td>
                                                 <td> <span class="table-title">Estimated start time</span>{{ getEstimateStartTime($pResponse->SchedulerRestriction)  }}</td>

                                                  <td>
                                                     <span class="table-title">Actions</span>
                                                    @if($division->primary_required == 1)

                                                      @if($classexist)

                                                        <label>
                                                          <input name="assets[division][{{$outer}}][innerclasses][{{$serial}}][id]" type="checkbox" data-attr="assets-charges" value="{{$pResponse->id}}" checked="checked" disabled="disabled">
                                                          <span>&nbsp;</span>  
                                                        </label>
                                                        <input name="assets[division][{{$outer}}][innerclasses][{{$serial}}][id]" type="hidden" value="{{$pResponse->id}}">
                                                        <input name="assets[division][{{$outer}}][innerclasses][{{$serial}}][already_registered]" type="hidden" value="1">
                                                        @include('shows.participate.partials.qualifying',['myPrizeobj'=>0,'divisionsQulif'=>1])
                                                         <?php  $divTotal = $divTotal+$DivlineTotal; ?>
                                                      @else
                                                        <label class="form-check-label">
                                                        <input name="assets[division][{{$outer}}][innerclasses][{{$serial}}][id]" type="checkbox" data-belong="wholedivisions" 
                                                          onchange="getScoringClasses($(this),'{{$pResponse->id}}','{{json_encode($divisionIds)}}','{{$pResponse->is_required_point_selection}}','{{count($isScoringClasses)}}')"
                                                          class="asset_{{$pResponse->id}}" data-attr="assets-charges" value="{{$pResponse->id}}" disabled="disabled">
                                                        <span>&nbsp</span>
                                                      </label>
                                                        <input type="hidden" name="assets[division][{{$outer}}][innerclasses][{{$serial}}][id]" class="hidden-disabled-val" value="{{$pResponse->id}}" disabled="disabled">
                                                      @endif

                                                   @else

                                                      @if($classexist)
                                                        <label class="form-check-label">
                                                          <input name="assets[division][{{$outer}}][innerclasses][{{$serial}}][id]" type="checkbox" class="primary-not-required-child" data-attr="assets-charges" value="{{$pResponse->id}}" checked="checked" disabled="disabled">
                                                          <span>&nbsp;</span>
                                                        </label>
                                                        <input name="assets[division][{{$outer}}][innerclasses][{{$serial}}][id]" type="hidden" value="{{$pResponse->id}}">
                                                        <input name="assets[division][{{$outer}}][innerclasses][{{$serial}}][already_registered]" type="hidden" value="1">
                                                         <?php  $divTotal = $divTotal+$DivlineTotal; ?>
                                                        @include('shows.participate.partials.qualifying',['myPrizeobj'=>0,'divisionsQulif'=>1])

                                                      @else
                                                        <label class="form-check-label">
                                                          <input name="assets[division][{{$outer}}][innerclasses][{{$serial}}][id]" 
                                                          class="primary-not-required-child asset_{{$pResponse->id}}" data-belong="divisions" type="checkbox" data-attr="assets-charges" 
                                                          value="{{$pResponse->id}}">
                                                          <span>&nbsp;</span>
                                                        </label>
                                                      @endif
                                                  @endif
                                                    @include('shows.participate.partials.qualifying',['myPrizeobj'=>1,'divisionsQulif'=>1])

                                                    <input name="assets[division][{{$outer}}][innerclasses][{{$serial}}][primary_required]" type="hidden" value="{{$division->primary_required}}">
                                                    <input type="hidden" name="template_id" value="{{$pResponse->template_id}}">
                                                </td>
                                            </tr>
                                            @endif
                                          @endforeach
                                          <input class="total-classes-div-{{$division->id}}" name="assets[division][{{$outer}}][total_classes]" type="hidden" value="{{$serial}}">

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

                    @if($templateType==TRAINER)
                    <h3>Training Services</h3>
                    @else
                    <h3>Classes</h3>
                    @endif


                  <div class="tab-pane except-division">
                      <div class="table-responsive  rr-datatable indivisual-fixed-y">
                          <table class="table table-line-braker primary-table mt-10 custom-responsive-md Datatable_nopagination">
                              <thead class="hidden-xs">
                              <tr>
                                  <th scope="col"  title="Class Number" style="width: 90px;">Class Number</th>
                                  <th scope="col"  title="Class Type" style="width: 74px;">Type</th>
                                  <th scope="col" style="width: 180px;">Title</th>
                                  @if($templateType!=TRAINER)
                                  <th scope="col" title="Class Code" style="width: 90px;">Code</th>
                                  <th scope="col" title="Jumper Height" style="width: 74px;{{($templateType==TRAINER)?'display:none':''}}">Height</th>
                                @endif
                                  <th scope="col"  title="Class Price" style="width: 105px;">Price</th>
                                  @if($show->show_type=='Western')<th>Pattern</th>@endif
                                  <th scope="col" style="width: 120px;">Horse</th>
                                  <th scope="col" style="width: 120px;">Riders</th>
                                  @if($templateType==TRAINER)
                                      <th scope="col" style="width: 120px;">Qty</th>
                                  @endif

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
                                            @php

                                              $serial = $loop->index + 1;
                                              $classexist = false;

                                                $isScoringClasses = isScoringClasses($pResponse->id);

                                                if(in_array($pResponse->id,$isScoringClasses)){
                                                    if(isset($pResponse->ShowAssetInvoice)){
                                                        $scoringSubPrice = $scoringSubPrice + getParticipatingPrice($pResponse,$show);
                                                    }
                                                }

                                              $description = GetAssetNameFromLabel($pResponse,'Description');
                                              $isScoringClasses = isScoringClasses($pResponse->id);
                                            @endphp


                                            @if($pResponse->fields)
                                            <tr class="tr-row">
                                                <td> <span class="table-title">Class Number</span> {{GetAssetNameFromLabel($pResponse,'Class Number')}}</td>
                                                <td> <span class="table-title">Type</span> {{$pResponse->classType?$pResponse->classType->name:""}}</td>
                                                <td> <span class="table-title">Title</span> {{GetAssetName($pResponse)}} @if($description!=null)<i class="icon-info-sign"
                                                                                                                                                     data-toggle="tooltip"
                                                                                                                                                     title="{{GetAssetNameFromLabel($pResponse,'Description')}}"
                                                                                                                                                     class='tooltip'></i>@endif</td>
                                                @if($templateType!=TRAINER)
                                                <td> <span class="table-title">Code</span>{{ GetSpecificFormField($pResponse,"USEF Section Code")  }}</td>
                                                <td> <span class="table-title">Jumper Height</span> {{GetAssetNameFromLabel($pResponse,'Jumper Height')}}</td>
                                                @endif

                                                <td> <span class="table-title">Price</span>

                                                    @if(isset($pResponse->ShowClassPrice))
                                                        <div style="float:left">
                                                            @include('shows.participate.partials.judgesFee')
                                                            @if(isset($regAsset[$keys]['qualifing']) && $regAsset[$keys]['qualifing']==1)
                                                                <div class="actual-price-set">($) {{getParticipatingPrice($pResponse,$show)+$regAsset[$keys]['qualifing_price'] }}</div>
                                                                <input name="assets[{{$serial}}][price]" type="hidden" class="priceSet" value="{{getParticipatingPrice($pResponse,$show) }}">
                                                                <input name="assets[{{$serial}}][orignal_price]" type="hidden" class="orignalPriceSet" value="{{getParticipatingPrice($pResponse,$show)+$regAsset[$keys]['qualifing_price']  }}">
                                                            @else
                                                                <div class="actual-price-set">($) {{getParticipatingPrice($pResponse,$show)}}</div>
                                                                <input name="assets[{{$serial}}][price]" type="hidden" class="priceSet" value="{{getParticipatingPrice($pResponse,$show)}}">
                                                                <input name="assets[{{$serial}}][orignal_price]" type="hidden" class="orignalPriceSet" value="{{getParticipatingPrice($pResponse,$show)}}">
                                                            @endif
                                                        </div>
                                                        <div class="horse-assets-select" style="float:left"></div>
                                                    @else No Price Set. @endif
                                                </td>

                                                @if($show->show_type=='Western')
                                                    @php $pattern =  GetSpecificFormField($pResponse,"Pattern");  @endphp
                                                    <td> <span class="table-title">Pattern</span>{{ $pattern == "" ? "N/A" : str_replace('|||','',$pattern)  }}</td>
                                                @endif



                                                <td> <span class="table-title">Horses</span>

                                                  @if(sizeof($OwnerHorses)>0)
                                                    <?php
                                                    $keys = recursive_array_search("$pResponse->id", $regAsset); ?>
                                                    @if(isset($regAsset[$keys]['id']) && $regAsset[$keys]['id'] == $pResponse->id)
                                                      <?php
                                                        $classexist = true;
                                                        $assetName= "assets[".$serial."][horses][]";
                                                        echo getHorseNamesfromIDTrainer($reg_id,$pResponse->id,$assetName); ?>
                                                    @endif
                                                    <select name="assets[{{$serial}}][horses][]" data-id="{{$pResponse->id}}" data-serial="{{$serial}}" class="selectpicker form-control Horses-{{$pResponse->id}}" data-live-search="true" multiple>
                                                      @foreach($OwnerHorses as $horse)
                                                       <?php $alredyReg = ExistingHorses($participatedHorses,$horse->id,$pResponse->id);
                                                        if(!$alredyReg){ ?>
                                                          <option value="{{$horse->id}}">{{GetAssetName($horse)}}</option>
                                                        <?php } ?>
                                                      @endforeach
                                                    </select>
                                                  @else
                                                      <p>Your has not added any horses in his/her horse app.</p>
                                                  @endif
                                                </td>
                                                <td> <span class="table-title">Riders</span>

                                                    <div class="selectRiders-{{$pResponse->id}}">
                                                        <?php
                                                        $rider =  getRiderNamesfromIDTrainer($reg_id,$pResponse->id);
                                                        if($rider!='')
                                                        echo $rider;
                                                        else {
                                                        ?>
                                                        <label>Select Rider</label>
                                                        <?php } ?>

                                                    </div>
                                                    <div class="ridersContainer" style="display: none">
                                                        @if(sizeof($riderHorses)>0)
                                                            <select class="rider-drp-select" name="assets[{{$serial}}]['riders'][]" disabled onchange="getRiderFeed($(this),'{{$pResponse->id}}')">
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

                                                @if($templateType==TRAINER)
                                                <td>
                                                    <div class="selectQty-{{$pResponse->id}}">
                                                  <?php
                                                    $qty =  getRiderQtyfromIDTrainer($reg_id,$pResponse->id);
                                                    if($qty!='')
                                                    echo $qty;
                                                    else {
                                                    ?>
                                                          <label>Select Qty</label>
                                                  <?php } ?>
                                                    </div>

                                                      <div class="qtyContainer" style="display: none">
                                                          @if(sizeof($riderHorses)>0)
                                                              <input value="1" type="number" oninput="this.value = Math.abs(this.value)" style="width: 70px;margin-left: 5px;" name="assets[{{$serial}}]['qty'][]" min="1">
                                                          @else
                                                              <p>You have not entered any rider asset in your horse application</p>
                                                          @endif
                                                      </div>

                                                </td>
                                                @endif






                                            <!--  <td> <span class="table-title">Date</span>{{ GetSpecificFormField($pResponse,"Date")  }}</td>
                                                <td> <span class="table-title">Location</span>{{ GetSpecificFormField($pResponse,"Location")  }}</td>
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

                                                    @if($classexist)
                                                      <label>
                                                       <input name="assets[{{$serial}}][id]" type="checkbox" data-attr="assets-charges" value="{{$pResponse->id}}" class="trainer-already-selectedone" checked="checked" disabled="disabled">
                                                       <span>&nbsp;</span>  
                                                      </label>
                                                      <input name="assets[{{$serial}}][id]" type="hidden" value="{{$pResponse->id}}">
                                                      <input name="assets[{{$serial}}][already_registered]" type="hidden" value="1">
                                                      @include('shows.participate.partials.qualifying',['myPrizeobj'=>0])

                                                    @else
                                                      <label>
                                                        <input onchange="getScoringClasses($(this),'{{$pResponse->id}}','{{$divisionid}}','{{$pResponse->is_required_point_selection}}','{{count($isScoringClasses)}}')" 
                                                        name="assets[{{$serial}}][id]"  type="checkbox" data-attr="assets-charges" value="{{$pResponse->id}}"
                                                        class="form-check-input asset_{{$pResponse->id}}">
                                                        <span>&nbsp;</span>
                                                      </label>
                                                    @endif
                                                     <div style="padding-left: 0px;" class="col-sm-9 division-{{$pResponse->id}}"></div>


                                                    @include('shows.participate.partials.qualifying',['myPrizeobj'=>1])
                                                    <input type="hidden" name="template_id" value="{{$pResponse->template_id}}">
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
                  <div id="indivisuals" class="tab-pane except-division additional-c-wraper">
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
                                        <?php $regAssets = getRegisteredAssets($registration,2); ?>


                                             @if($pResponse->required == 1)
                                             <?php
                                              $serial = $loop->index + 1;
                                              $roleName = '';
                                            ?>
                                            <tr class="tr-row additiona-charges-row">
                                                <!-- <td>{{ $serial }}</td> -->
                                                <td> <span class="table-title">Title</span> {{$pResponse->title}}</td>
                                                <td> <span class="table-title">Description</span>{{ $pResponse->description }}</td>
                                                <td> <span class="table-title">Price</span>@if(isset($pResponse->amount)) ($){{$pResponse->amount }} @else No Price Set. @endif</td>
                                                <?php $keys = recursive_array_search("$pResponse->id", $regAssets); ?>
                                                <td> <span class="table-title">Quantity</span>
                                                  @if(isset($regAssets[$keys]['id']) && isset($regAssets[$keys]['qty']) && $regAssets[$keys]['id'] == $pResponse->id)
                                                    <input type="number" name="additional[{{$serial}}][qty]" class="col-sm-8 numberQty hidden" min="1" max="999" value="{{$regAssets[$keys]['qty']}}" placeholder="qty" data-unit-price="{{$pResponse->amount}}">
                                                  @else
                                                    <input type="number" name="additional[{{$serial}}][qty]" class="col-sm-8 numberQty hidden" min="1" max="999" value="1" placeholder="qty" data-unit-price="{{$pResponse->amount}}">
                                                  @endif
                                                </td>
                                                <td> <span class="table-title">Price</span>@if(isset($pResponse->amount) && isset($regAssets[$keys])) <div class="priceinqty">@php $pricQty = $pResponse->amount*$regAssets[$keys]['qty']@endphp {{getpriceFormate($pricQty)}} <input type="hidden" name="additional[{{$serial}}][price]" class="priceSet" value="{{$pricQty}}"></div> @else No Price Set. @endif</td>
                                               <!--  <td> <span class="table-title">Date</span>{{ GetSpecificFormField($pResponse,"Date")  }}</td>
                                                <td> <span class="table-title">Location</span>{{ GetSpecificFormField($pResponse,"Location")  }}</td>
                                                 --> <td>
                                                     <span class="table-title">Actions</span>

                                                    @if(isset($regAssets[$keys]['id']) && $regAssets[$keys]['id'] == $pResponse->id)
                                                      <label>
                                                        <input name="additional[{{$serial}}][id]" class="checkbox-additional" data-attr="additional-charges" type="checkbox" value="{{$pResponse->id}}" checked="checked" disabled="disabled">
                                                        <span>&nbsp;</span>
                                                      </label>
                                                      <input name="additional[{{$serial}}][id]"  type="hidden" value="{{$pResponse->id}}">

                                                    @else
                                                      <label>
                                                        <input name="additional[{{$serial}}][id]" class="checkbox-additional" data-attr="additional-charges" type="checkbox" value="{{$pResponse->id}}" @if($pResponse->required == 1) checked="checked" disabled="disabled" @endif >
                                                        <span>&nbsp;</span>
                                                      </label>
                                                    @endif

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
                              
                               </div>
                              <div class="col-sm-6 mb-20 pl-0 mt-20">
                                  <input type="submit" class="btn btn-primary checkout" value="Participate"></div>
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
        </div>
        <!-- Tab containing all the data tables -->
            </div>
        </div>
		
@endsection

@section('footer-scripts')
<script type="text/javascript">
localStorage.setItem('lastTab', "#invited_assets");
</script>

    @include('layouts.partials.datatable')
<script src="{{ asset('/js/shows/classes-pricing.js') }}"></script>
<script src="{{ asset('/js/shows/classes-pricing-trainer.js') }}"></script>

<script>

    $('.selectpicker').on('changed.bs.select', function (e, clickedIndex, newValue, oldValue) {

        var id = $(e.target).data('id');
        var serial = $(e.target).data('serial');
        var outer = $(e.target).data('division');
        var show_id = '{{$show->id}}';
        var horse_id = $('.selectpicker option').eq(clickedIndex).val();
        var respond =  checkShowRestriction($(e.target),id,serial,horse_id,clickedIndex,outer,show_id);

    });

</script>

    @if($templateType ==TRAINER)
        <style>
            .HorseScratch{
                display:none;
            }

        </style>
    @endif


@endsection
