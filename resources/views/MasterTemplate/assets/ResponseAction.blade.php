<a href="{{route('participant-repsones-history',nxb_encode($id))}}" data-toggle="tooltip" data-placement="top" title="View Response">
    <i class="fa fa-eye" aria-hidden="true"></i></a>
<a  class="btn-sm btn-default viewInvoiceBtn"
    style="background: green none repeat scroll 0% 0%;"
    href="{{route('export-response-pdf',nxb_encode($id))}}" class="ic_bd_export">Export PDF</a>