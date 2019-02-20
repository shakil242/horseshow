@extends('layouts.equetica2')
@section('main-content')

        <div class="container-fluid">

            @php
                $title ='Training ('.$show->title.')';
                $added_subtitle = '';
            @endphp
            @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle,'remove_search'=>1])

            <div class="white-board">
                <div class="col-md-12">
                <div class="row">
                    <div class="info text-center">
                        @if(Session::has('message'))
                            <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                        @endif
                    </div>
                </div>
                </div>

                  {!! Form::open(['url'=>'trainer/participate','method'=>'post','files'=>true,'class'=>'form-horizontal dropzone targetvalue']) !!}
        <div class="row">
          <div class="col-sm-12">
            @if(!$collection->count())
              <div class="row">
                <div class="col-md-12">{{NO_CLASSES_RESPONSE}}</div>
              </div>
            @else
                  <h3>Training Services</h3>
                  <input type="hidden" name="step2" value="1">
                  <input type="hidden" name="show_id" class="show_id" value="{{$show->id}}">
                  <input type="hidden" name="MSR" value="{{$MSR}}">

                  <div id="indivisual" class="tab-pane">

                      <div class="table-responsive  rr-datatable indivisual-fixed-y">
                          <table class="table  primary-table mt-10 custom-responsive-md Datatable_nopagination">
                              <thead class="hidden-xs">
                              <tr>
                                   <tr>
                                      <th scope="col">#</th>
                                      <th scope="col">Title</th>
                                      {{--<th scope="col">Code</th>--}}
                                      <th scope="col">Price</th>
                                      <th scope="col">Horse</th>
                                      <th scope="col">Rider</th>
                                      <th scope="col">QTY</th>

                                      <th class="action">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(sizeof($collection)>0)
                                        @foreach($collection as $pResponse)
                                            <?php 
                                              $serial = $loop->index + 1; 
                                              $roleName = '';
                                            ?>
                                            @if($pResponse->fields)
                                            <tr class="tr-row">
                                                <td>{{ $serial }}</td>
                                                <td><span class="table-title">Title</span> {{GetAssetName($pResponse)}}</td>
                                                {{--<td><span class="table-title">Code</span>{{ GetSpecificFormField($pResponse,"Code")  }}</td>--}}
                                                <td><span class="table-title">Price</span>@if(isset($pResponse->ShowClassPrice))
                                                      <div style="float:left" class="single-class"> 
                                                        <div class="actual-price-set">($){{getParticipatingPrice($pResponse,$show) }}</div>
                                                        <input name="assets[{{$serial}}][price]" type="hidden" class="priceSet" value="{{getParticipatingPrice($pResponse,$show) }}">
                                                        <input name="assets[{{$serial}}][orignal_price]" type="hidden" class="orignalPriceSet" value="{{getParticipatingPrice($pResponse,$show)}}">
                                                      </div> 
                                                      <div class="horse-assets-select" style="float:left"></div> 
                                                      @else No Price Set. @endif
                                                </td>
                                                <td><span class="table-title">Horses</span>
                                                  @if(sizeof($OwnerHorses)>0)
                                                  <select name="assets[{{$serial}}][horses][]" data-id="{{$pResponse->id}}" data-serial="{{$serial}}" class="selectpicker selectPickerMain form-control Horses-{{$pResponse->id}}" data-live-search-placeholder="Search" title="Select Horse" data-live-search="true" multiple>
                                                    @foreach($OwnerHorses as $horse)
                                                      
                                                          <option value="{{$horse->id}}">{{GetAssetName($horse)}}</option>
                                                        
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
                                                <td>
                                                    <span class="table-title">Qty</span>
                                                    <div class="selectQty-{{$pResponse->id}}">
                                                        <label>Select Qty</label>
                                                    </div>

                                                    <div class="qtyContainer" style="display: none">
                                                        @if(sizeof($riderHorses)>0)
                                                        <input value="1" type="number" oninput="this.value = Math.abs(this.value)" style="width: 70px;margin-left: 5px;" name="assets[{{$serial}}]['qty'][]" min="1">
                                                        @else
                                                            <p>You have not entered any rider asset in your horse application</p>
                                                        @endif
                                                    </div>


                                                </td>
                                               
                                               <!--  <td><strong class="table-title">Date</strong>{{ GetSpecificFormField($pResponse,"Date")  }}</td>
                                                <td><strong class="table-title">Location</strong>{{ GetSpecificFormField($pResponse,"Location")  }}</td>
                                                 --> <td class="action">
                                                    <span class="table-title">Actions</span>
                                                    <div class="form-check">
                                                        <label class="form-check-label" style="width: 1em">
                                                            <input class="form-check-input" name="assets[{{$serial}}][id]" type="checkbox" data-attr="assets-charges" value="{{$pResponse->id}}">
                                                           <span>&nbsp</span>

                                                        </label>
                                                    </div>


                                                    <input type="hidden" name="template_id" value="{{$pResponse->template_id}}">
                                                </td>
                                            </tr>
                                            @endif
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                       </div>
                  </div>

                  <div class="Totals row" style="display: none">
                      <div class="col-md-7">&nbsp;</div>
                      <div class="col-md-5 pull-right">
                          <div class="row">
                              <div class="col-md-3 border-bottom"><b> Amount: </b></div>
                              <div class="col-md-8 addAssetPrice border-bottom">($) 0<input type="hidden" class="AssetsPrice" name="assets_price" value="0"></div>
                          </div>
                      </div>
                  </div>


                  </div>

              <div class="col-sm-12">
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
               
              </div>
            </div>
            
          <div class="col-md-12 mt-20">
            <div class="Totals row"  style="display: none">
                <div class="col-md-7">&nbsp;</div>
                <div class="col-md-5 pull-right">
                    <div class="row mb-20">
                        <div class="col-md-9 border-bottom"><b>Additional Pricing: </b></div>
                        <div class="col-md-2 addAdditionalPrice border-bottom">($) 0<input type="hidden" class="additionalPrice" name="additional_price" value="0"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-9 border-bottom"><b>Total: </b></div>
                        <div class="col-md-2 addTotalPrice border-bottom">($) 0<input type="hidden" class="totalPrice" name="total_price" value="0"></div>
                    </div>
                </div>
            </div>
          </div>
            <div class="col-sm-12 text-right"><input type="submit" class="btn btn-primary checkout" value="Participate"></div>


            {{--<div class="Totals row">--}}
                           {{--<div class="col-sm-12">--}}
                            {{--<div class="col-sm-5 pull-right">--}}
                              {{--<div class="col-sm-5 border-bottom"><b> Additional Pricing: </b></div>--}}
                              {{--<div class="col-sm-7 addAdditionalPrice border-bottom">($) 0<input type="hidden" class="additionalPrice" name="additional_price" value="0"></div>--}}
                            {{--</div>--}}
                           {{--</div>--}}
                           {{--<div class="col-sm-12">--}}
                             {{--<div class="col-sm-5 pull-right">--}}
                              {{--<div class="col-sm-5 border-bottom"><b> Total: </b></div>--}}
                              {{--<div class="col-sm-7 addTotalPrice border-bottom">($) 0<input type="hidden" class="totalPrice" name="total_price" value="0"></div>--}}
                                {{--<div class="col-sm-10"><input type="submit" class="btn btn-lg btn-primary checkout" value="Participate"></div>--}}
                              {{--</div>--}}
                           {{--</div>--}}
                           {{----}}
                       {{--</div>--}}

                  </div>



                  {!! Form::close() !!}

                </div>
            @endif
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
    <script src="{{ asset('/js/shows/classes-pricing.js') }}"></script>
    @include('layouts.partials.datatable')

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

@endsection
