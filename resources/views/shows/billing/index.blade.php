    @extends('layouts.equetica2')


    @section('main-content')

@if(Session::has('message'))
<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
@endif
      <!-- ================= CONTENT AREA ================== -->
<div class="main-contents">
    <div class="container-fluid">

        @php 
          $title = "Invoices";
          $added_subtitle = "";//Breadcrumbs::render('shows-participants-listing', $template_id);
        @endphp
        @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle,'invoice'=>1])

        <!-- Content Panel -->
        <div class="white-board">


            <div class="row">
                <div class="col-sm-10 mb-20">&nbsp</div>
                <div class="col-sm-2">
                    <a class="nav-link btn btn-primary" href="{{url('/billing/0')}}">Billing</a>
                </div>
                <div class="col">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <!--<li class="nav-item">
                                <a class="nav-link active" id="division-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Orders</a>
                            </li>
                             <li class="nav-item">
                                <a class="nav-link" id="showclasses-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Show Classes</a>
                            </li> -->
                        </ul>
                    </div>

            </div>
            
            <div class="row">
                    <div class="col-md-12">
                            
                        <!-- TAB CONTENT -->
                        <div class="tab-content" id="myTabContent">
                            <!-- Tab Data Divisions -->
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="division-tab">
                              @include('admin.layouts.errors')

                                 @if(!$collection->count())
                                    <div class="">
                                      <div class="col-lg-5 col-md-5 col-sm-12">No Participants yet.</div>
                                    </div>
                                  @else
                                  <div class="module-holer rr-datatable">
                                        <table class="table primary-table dataViews">
                                          <thead class="hidden-xs">
                                             <tr>
                                                <th style="width:5%;">#</th>
                                                <th style="width:20%">Name</th>
                                                <th style="width:20%">Status</th>
                                                <th style="width:15%">Action</th>
                                              </tr>
                                          </thead>
                                              <!-- <tfoot>
                                              <tr>
                                                  <td colspan="2">Name</td>
                                              </tr>
                                              </tfoot> -->

                                              <tbody>
                                            @foreach($collection as $pResponse)
                                            
                                                <?php $serial = $loop->index + 1;?>
                                                <tr>
                                                    <td style="width:10px;padding: 0px 2px">{{ $serial }}</td>
                                                    <td>{{$pResponse->show->title}}</td>
                                                    <td> {{appInvoiceStatus($pResponse->manage_show_id,$user_id)}}</td>
                                                   
                                                    <td>
                                                      <a href="{{URL::to('shows') }}/{{nxb_encode($pResponse->manage_show_id)}}/horse/invoices">View</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                          </tbody>
                                      </table>
                                 </div>
                                  @endif

                            </div>
                            
                        </div>
                        <!-- ./ TAB CONTENT -->
                    </div>
                </div>
        <!-- ./ Content Panel -->  
        </div>
    </div>
</div>

<!-- ================= ./ CONTENT AREA ================ -->

     
@endsection

@section('footer-scripts')


    {{--<link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">--}}
  @include('layouts.partials.datatable')


@endsection