@if(getEstimateRestrictionif($pResponse->SchedulerRestriction))                                                  

  @if(isset($divisionsQulif) && $divisionsQulif == 1)
  <div class="qualifing-div">
      <input type="hidden" class="qualifing-price" name="assets[division][{{$outer}}][innerclasses][{{$serial}}][qualifing_price]" value="{{getEstimateRestrictionPrice($pResponse->SchedulerRestriction,$show->id)}}">
  </div>
  <div class="qualifying-confirm mt-10" @if($myPrizeobj==1) style="display:none" @endif>
    
    @if(isset($selector[$keys]['qualifing']) && $selector[$keys]['qualifing']==0)
    <!-- <label> Not Qualifying</label> -->
    @else
    <label style="width: 5em; text-align: left">Qualifying</label>
     @if(isset($selector[$keys]['qualifing']) && $selector[$keys]['qualifing']==1)
     <label> YES </label>
     @else
      <select style="width: 118px;" class="qualifing-drpdwn" name="assets[division][{{$outer}}][innerclasses][{{$serial}}][qualifing]">
        <option value="0">No</option>
        <option value="1">Yes</option>
      </select>
      @endif
    @endif
  </div>
  
  @else
  <!-- For Classes -->
  
  <div class="qualifing-div">
      <input type="hidden" class="qualifing-price" name="assets[{{$serial}}][qualifing_price]" value="{{getEstimateRestrictionPrice($pResponse->SchedulerRestriction,$show->id)}}">
  </div>
  <div class="qualifying-confirm  mt-10" @if($myPrizeobj==1) style="display:none" @endif>
    
    @if(isset($regAsset[$keys]['qualifing']) && $regAsset[$keys]['qualifing']==0)
    <!-- <label> Not Qualifying</label> -->
    @else
    <label style="width: 5em; text-align: left">Qualifying</label>
     @if(isset($regAsset[$keys]['qualifing']) && $regAsset[$keys]['qualifing']==1)
     <label> YES </label>
     @else
      <select style="width: 118px;" class="qualifing-drpdwn" name="assets[{{$serial}}][qualifing]">
        <option value="0">No</option>
        <option value="1">Yes</option>
      </select>
      @endif
    @endif
  </div>
  @endif

@endif