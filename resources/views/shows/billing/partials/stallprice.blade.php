@php
$stallCharges = getStallCharges($horses->horse_id,$show_id,$invoice_status,$paid_on);
$utilityStall = getUtilityStalls($horses->horse_id,$show_id,$invoice_status,$paid_on);
@endphp
@if(count($stallCharges)>0)
  <div class="stall-charges">
    <h3>Stall Purchased</h3>
    <div class="rr-datatable">
        <table id="crudTabl" class="table primary-table">
          <thead class="hidden-xs">
             <tr>
                <th>Title</th>
                <th>Date Register</th>
                <th> Stall#</th>
                <th>Price</th>
                <!-- <th>Date</th>
                <th>Location</th> -->
              </tr>
          </thead>
          <tbody>
          @foreach($stallCharges as $pResponse)
            <tr class="tr-row additiona-charges-row">
                <td><strong class="visible-xs">Title</strong>{{$pResponse->stalls->stall_type}}</td>
                <td><strong class="visible-xs">Date</strong>{{getDates($pResponse->updated_at)}}</td>
                <td><strong class="visible-xs"> Stall#</strong>{{$pResponse->stall_no}}</td>
                <td><strong class="visible-xs">Price</strong>$ {{twodecimalformate($pResponse->stalls->price)}} @php $stallPrice=$stallPrice+$pResponse->stalls->price; $myStallobj->stallPrice = twodecimalformate($stallPrice); @endphp</td>
            </tr> 
          @endforeach
          @if(count($utilityStall)>0)
            @foreach($utilityStall as $pResponse)
              <tr class="tr-row additiona-charges-row">
                  <td><strong class="visible-xs">Title</strong>Utility</td>
                  <td><strong class="visible-xs">Date</strong>{{getDates($pResponse->created_at)}}</td>
                  <td><strong class="visible-xs">Stall#</strong>-</td>
                  <td><strong class="visible-xs">Price</strong>$ {{twodecimalformate($pResponse->total_price / $pResponse->divided_amoung) }} @php $stallPrice=$stallPrice+($pResponse->total_price / $pResponse->divided_amoung); $myStallobj->stallPrice = twodecimalformate($stallPrice); @endphp</td>
              </tr> 
            @endforeach
          @endif

                   
          </tbody>
        </table>
    </div>
</div>
@endif