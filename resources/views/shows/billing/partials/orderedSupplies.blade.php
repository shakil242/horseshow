@php
$OrderedSupplies = getOrderedSupplies($horses->horse_id,$show_id);
@endphp
@if(count($OrderedSupplies)>0)
@foreach($OrderedSupplies as $order)
  @php
    $additional = json_decode($order->additional_fields);
  @endphp
    @if(count($additional)>0)
      @foreach($additional as $pResponse)
        @if(isset($pResponse->status) && $pResponse->status=="completed")
            <tr class="tr-row additiona-charges-row">
              <td><strong class="visible-xs">Title</strong> {{AdditionalCharge($pResponse->id)}}</td>
              <td><strong class="visible-xs">Date</strong> {{getDates($order->updated_at)}}</td>
              <td><strong class="visible-xs">Description</strong> - </td>
              <td><strong class="visible-xs">Price</strong>
                  <span class="additional-divi">
                    @if(isset($pResponse->price)) {{ $pResponse->approveQty}} x {{$pResponse->price }} = ($){{$pResponse->price*$pResponse->approveQty}} @else No Price Set. @endif
                  </span>
                    <input type="hidden" class="additonal-price" value="{{$pResponse->price }}"> 
                    <input type="hidden" class="additonal-qty" value="{{ $pResponse->approveQty}}"> 
                    <input type="hidden" class="ch_id" value=""> 
                    <input type="hidden" class="additonal-row-id" value="{{$pResponse->id}}"> 
              </td>
              
              <td><strong class="visible-xs">Divided</strong>{{$divided = count($order->orderSupplie)}}</td>
              <td><strong class="visible-xs">Price</strong>
                @if(isset($pResponse->price)) 
                  <div class="priceinqty">
                    <?php 
                    $priQty = ((float)$pResponse->price*$pResponse->approveQty)/$divided;
                    $additionalPrice = $additionalPrice +(float)$priQty; ?>
                    ($){{ number_format(($priQty),2) }}
                    @php 
                      $myOrderSuppliesobj->suppliesPrice = $additionalPrice;
                    @endphp
                  </div> 
                  @else No Price Set. 
                @endif
              </td>
          </tr>
          @endif
      @endforeach
    @endif
@endforeach
@endif