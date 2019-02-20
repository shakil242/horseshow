@php
$showOwner  = getShowOwnerInfo($show_id);
@endphp

<div class="row">
    <div class="col-sm-6" id="all-invoices">
       <table class="table primary-table">
        <tbody>
          <tr>
            <td class="hidden-xs">Invoice No: </td>
            <td><strong class="brown">{{getInvoiceNumber($show_id,$horses->horse_id,$horses)}}</strong></td>
          </tr>
          <tr>
            @if($MS->template->category == TRAINER)
              <td class="hidden-xs">Horse Name: </td>
              <td><strong class="brown">{!! getHorseNameAsLink($horses->horse) !!}</strong></td>
            @else
              <td class="hidden-xs">Horse Name and Entry#: </td>
            <td><strong class="brown">{!! getHorseNameAsLink($horses->horse) !!}</strong> ({{$horses->horse_reg}})</td>
            @endif
          </tr>
          <tr>
            <td class="hidden-xs">Client’s Name: </td>
            <td><strong class="brown">{{getUserNamefromid($user_id)}}</strong></td>
          </tr>
          
          @if($MS->template->category != TRAINER)
          <tr>
              <td class="hidden-xs">Horse USEF#: </td>
              <td><strong class="brown">{{GetSpecificFormField($horses->horse,"USEF Number","N/A")}}</strong></td>
            </tr>
          <tr>
            <td class="hidden-xs">Rider's Name and USEF#: </td>
            <td><strong class="brown">{!! getRidersForHorse($horses->horse_id,$show_id) !!}</strong></td>
          </tr>
          <tr>
            <td class="hidden-xs">Trainer Name and USEF#: </td>
            <td><strong class="brown">{!! getTrainerForHorse($horses->horse_id,$show_id) !!}</strong></td>
          </tr>
          <tr>
            <td class="hidden-xs">Owner Name and USEF#: </td>
            <td><strong class="brown">{!! getOwnerForHorse($horses->horse_id) !!}</strong></td>
          </tr>
          @endif
          
        </tbody>
        </table>
    </div>
    <div class="col-sm-6" id="all-invoices">
       <table class="table primary-table">
        <tbody>

        <tr>
            <td class="hidden-xs">Business Name: </td>
            <td><strong class="brown">{{(isset($showOwner))?$showOwner->business_name:''}}</strong></td>
        </tr>

        <tr>
            @if($MS->template->category == TRAINER)
              <td class="hidden-xs">Business Address: </td>    
            @else
              <td class="hidden-xs">Show Address: </td>  
            @endif
            
            <td><strong class="brown">{{$MS->location}}</strong></td>
          </tr>

          <tr>
            <td class="hidden-xs">Contact Information: </td>
            <td><strong class="brown">{{$MS->contact_information}}</strong></td>
          </tr>
           @if($MS->template->category != TRAINER)  
            <tr>
              <td class="hidden-xs">Show Date: </td>
              <td><strong class="brown">{{getDates($MS->date_from)}} Up To {{getDates($MS->date_to)}}</strong></td>
            </tr>
            @endif
          <tr>
            <td class="hidden-xs">{{post_value_or($m_s_fields,'ShowInfoOnInvoice','Info On Invoice')}}: </td>
            <td><strong class="brown">{{$MS->info_on_invoice}} </strong></td>
          </tr>
           
        </tbody>
        </table>
    </div>
</div>