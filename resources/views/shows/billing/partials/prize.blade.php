@if(sizeof($prize)>0)
<div>
<h3>Prize Money</h3>
<div id="indivisual">
          <div class="module-holer rr-datatable">
              <table id="crudTabl" class="table primary-table">
              <thead class="hidden-xs">
                 <tr>
                    <th style="width:5%">#</th>
                    <th>Class</th>
                    <th>Placement</th>
                    <th>Prize Won</th>
                  </tr>
              </thead>
              <tbody>
                  <?php $serial =0; ?>
                      @foreach($prize as $one_asset)
                        <?php $decode_asset = json_decode($one_asset->position_fields);
                        ?>
                        @foreach($decode_asset as $pResponse)
                          @if(isset($pResponse->horse_id) && $pResponse->horse_id == $horses->horse->id)
                           
                            @if($invoice_status == PAID)
                              @if(isset($pResponse->paid_on) && $pResponse->paid_on == $paid_on)
                                <tr class="tr-row">
                                  <td>@php $serial=1; @endphp</td>
                                  <td><strong class="visible-xs">Class</strong>{{GetAssetNamefromId($one_asset->asset_id)}}
                                     @if(isset($class->splitClass->splitedclass->SchedulerRestriction)
                                        && count($class->splitClass->splitedclass->SchedulerRestriction)>0 )
                                     <strong>California Split</strong>
                                    @endif
                                  </td>

                                  <td><strong class="visible-xs">Position</strong>@if(isset($pResponse->position)){!! getPostionText($pResponse->position) !!} @endif</td>
                                  <td><strong class="visible-xs">Price</strong>@if(isset($pResponse->price)) <div class="priceinqty"><?php $prizeWon =  (int)$prizeWon+ (int)$pResponse->price ?>($){{ $pResponse->price}}</div> @else No Price Set. @endif</td>
                                </tr>
                              @endif
                            @else
                              @if(!isset($pResponse->paid_on))
                            <?php  $serial = $serial + 1;?>
                            
                              <tr class="tr-row">
                                  <td>{{ $serial }}</td>
                                  <td><strong class="visible-xs">Class</strong>{{GetAssetNamefromId($one_asset->asset_id)}}
                                    @if(isset($class->splitClass->splitedclass->SchedulerRestriction)
                                        && count($class->splitClass->splitedclass->SchedulerRestriction)>0 )
                                     <strong>California Split</strong>
                                    @endif
                                  </td>
                                  <td><strong class="visible-xs">Position</strong>@if(isset($pResponse->position)){!! getPostionText($pResponse->position) !!} @endif</td>
                                  <td><strong class="visible-xs">Price</strong>@if(isset($pResponse->price) && !empty($pResponse->price)) <div class="priceinqty"><?php $prizeWon = (int)$prizeWon+ (int)$pResponse->price ?>($){{ $pResponse->price}}</div> @else No Price Set. @endif</td>
                              </tr>
                              @endif
                            @endif
                          
                          @endif
                        @endforeach
                      @endforeach
                      @if($serial ==0)
                      <tr class="tr-row">
                        <td colspan="4"> You have not won any placement or prize money.</td>
                      </tr>
                      @endif
              </tbody>
          </table>
     </div>
     <div class="Totals">
         <div class="col-sm-12 row">

            <div class="col-sm-9 border-bottom mr-10"><b> Prize Won: </b></div>
            <div class="col-sm-2 addAssetPrice border-bottom">($) {{$prizeWon}}<input type="hidden" class="PrizeWon" name="Invoices[{{$horses->horse_id}}][prize_won]" value="{{$prizeWon}}">
            @php $myPrizeobj->prizeWon =$prizeWon  @endphp
            </div>

         
         </div>
          <div class="offset-10 col-sm-2">
          @if($prizeWon < 600)
            <a href="javascript:" style="color:red" onclick="GetPrizeClaimForm('{{$horses->horse_id}}','{{$show_id}}')"> 1099 form</a>
          @else
             @if($prizeWon>0)
                  @if(prizeClaimCount($horses->horse_id,$show_id) > 0)
                  <a href="javascript:" style="color:red" onclick="GetPrizeClaimForm('{{$horses->horse_id}}','{{$show_id}}')"> 1099 form</a>
                  @else
                  <a href="javascript:" style="color:red" onclick="GetPrizeClaimForm('{{$horses->horse_id}}','{{$show_id}}')">Claim prize money by filling 1099 form</a>
                  @endif
             @endif
          @endif
          </div>
     </div>
</div>
</div>
@endif
