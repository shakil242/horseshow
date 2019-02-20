@if($MS->template->category == TRAINER)
           <div class="col-sm-9 "><div class="border-bottom pb-2"><b> (Services+Additonal+Miscellaneous Charges): </b></div></div>
        <div class="col-sm-3 addTotalPrice"><div class="border-bottom pb-2">($) ({{$assetTotal}} + {{twodecimalformate($additionalPrice)}} + {{$royaltyFinal}}) = {{$myTotalCount->total = twodecimalformate($additionalPrice+$assetTotal+$royaltyFinal) }}</div></div>
@else
    @if($prizeWon < 600 )
            <div class="col-sm-9"><div class="border-bottom pb-2"><b> (Classes + Additonal + Miscellaneous Charges + Division + Stall)-Prize: </b></div></div>
            <div class="col-sm-3 addTotalPrice"><div class="border-bottom pb-2">($) ({{$assetTotal}} + {{twodecimalformate($additionalPrice)}}+{{$royaltyFinal}}+{{$divisionTotal}} + {{$stallPrice}}) - {{$prizeWon}} = {{$myTotalCount->total = twodecimalformate(($additionalPrice+$assetTotal+$royaltyFinal+$divisionTotal+$stallPrice)-$prizeWon) }}</div></div>
    @else
      @if(prizeClaimCount($horses->horse_id,$show_id) > 0)
        <div class="col-sm-9 "><div class="border-bottom pb-2"><b> (Classes+Additonal+Miscellaneous Charges+Division + Stall)-Prize: </b></div></div>
        <div class="col-sm-3 addTotalPrice"><div class="border-bottom pb-2">($) ({{$assetTotal}} + {{twodecimalformate($additionalPrice)}} + {{$royaltyFinal}}+{{$divisionTotal}} + {{$stallPrice}}) - {{$prizeWon}} = {{$myTotalCount->total = twodecimalformate(($additionalPrice+$assetTotal+$royaltyFinal+$divisionTotal+$stallPrice)-$prizeWon) }}</div></div>
      @else
        <div class="col-sm-9"><div class="border-bottom pb-2"><b> (Classes+Additonal+Miscellaneous Charges+Division + Stall): </b></div></div>
        <div class="col-sm-3 addTotalPrice"><div class="border-bottom pb-2">($) ({{$assetTotal}} + {{twodecimalformate($additionalPrice)}} + {{$royaltyFinal}} + {{$divisionTotal}} + {{$stallPrice}})  = {{ $myTotalCount->total = twodecimalformate(($additionalPrice+$assetTotal+$royaltyFinal+$divisionTotal+$stallPrice)) }}</div></div>
      @endif
  @endif

@endif