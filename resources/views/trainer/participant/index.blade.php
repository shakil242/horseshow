    @extends('layouts.equetica2')


    @section('main-content')

    <div class="row">
      <div class="col-sm-7">
      <?php $ya_fields = getButtonLabelFromTemplateId($template_id,'ya_fields'); ?>
        <h1>{{post_value_or($ya_fields,'manage_scheduler','Schedular')}}</h1>
      </div>
    </div>

    <div class="row">
    <div class="info">
    @if(Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
    @endif
    </div>
    </div>
    <div class="row">
      <div class="col-sm-8">
        {!! Breadcrumbs::render('shows-participants-listing', $template_id) !!}
      </div>
    </div>
    <div class="row" style="margin-bottom: 10px;">
    <div class="col-sm-10">
      <h1>{{GetTemplateName($template_id)}}</h1>
    </div>

    </div>
    <div class="row" style="padding: 0px 15px;">

      <!-- Accordion START -->
      <div class="panel-group show-participant-history" id="accordion">
      @if($manageShows->count()>0)
      @foreach($manageShows as $show)
      <div class="panel panel-default">
      <div class="panel-heading accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" data-target="#{{$show->id}}">
        @if($show->title != null) <h2>{{$show->title}}</h2>@else <h2>Show Title</h2> @endif
      </div>
      <div id="{{$show->id}}" class="defaultClasses panel-collapse collapse">
      <div class="panel-body">
                  <div class="row">
         <div class="col-sm-12">
         <?php $collection = $show->participants; ?>
            @if(!$collection->count())
              <div class="">
                <div class="col-lg-5 col-md-5 col-sm-12">No Participants yet.</div>
              </div>
            @else
              <div class="col-sm-12">
                <div class="tab-content">
                  <div class="tab-pane fade in active">
                            <div class="module-holer rr-datatable">
                                <table class="table table-responsive primary-table dataTableView">
                                <thead class="hidden-xs">
                                   <tr>
                                      <th style="width:1%">#</th>
                                      <th style="width:15%">Name</th>
                                      <th style="width:15%">Class</th>
                                      <th style="width:15%">Register On</th>
                                      <th style="width:15%">Horses</th>
                                      <th style="width:15%">Invoice Status</th>
                                      <th  style="width:25%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  @foreach($collection as $pResponse)
                                      <?php $serial = $loop->index + 1;
                                         $userId =  getIdFromEmail($pResponse->email);
                                         $show_id = $pResponse->show_id;
                                      if($pResponse->payinoffice($show_id,$userId)){
                                        $invoicStatus = 'Pay In Office';
                                      }else{
                                         if($pResponse->invoicePaid($show_id,$userId) > 0)
                                          $invoicStatus = 'Paid';
                                        else
                                          $invoicStatus = 'Pending';
                                      }
                                     
                                      ?>
                                      <tr>
                                          <td style="width:2px">{{ $serial }}</td>
                                          <td><strong class="visible-xs">Name</strong>{{getUserNameEmailfromEmail($pResponse->email)}}</td>
                                          <td><strong class="visible-xs">Class</strong> {{GetAssetNamefromId($pResponse->asset_id)}}</td>
                                          <td><strong class="visible-xs">Register On</strong> {{getDates($pResponse->created_at)}}</td>
                                          <td><strong class="visible-xs">Horses </strong> {!! getHorseNames($pResponse->id,$pResponse->asset_id,2) !!}</td>
                                          <td><strong class="visible-xs">Invoice Status</strong> {{$invoicStatus}}</td>
                                          <td>
                                            <a href="{{URL::to('shows') }}/{{nxb_encode($pResponse->manage_show_reg_id)}}/registration/view">View Registration</a>
                                            <a href="{{URL::to('shows') }}/{{nxb_encode($pResponse->manage_show_reg_id)}}/{{nxb_encode($pResponse->id)}}/view/invoice" class="module-holder">View Invoice</a>
                                              <a  class="btn-sm btn-default viewInvoiceBtn"
                                                  style="background: green none repeat scroll 0% 0%; float: right!important;"
                                                  href="{{URL::to('master-template') }}/ExportRegistrationView/{{nxb_encode($pResponse->manage_show_reg_id)}}" class="ic_bd_export">Export PDF</a>

                                          </td>
                                      </tr>
                                  @endforeach
                                </tbody>
                            </table>
                       </div>
                  </div>
                </div>
              </div>
              
            @endif
          </div>
        </div>


      </div>
      </div>
      </div>
      @endforeach
      @else
      <div class="panel panel-default">
        <label>No show added yet!</label>
      </div>
      @endif
      </div>
    </div>
@endsection

@section('footer-scripts')
  @include('layouts.partials.datatable')
@endsection