@extends('layouts.equetica2')

@section('custom-htmlheader')
    @include('layouts.partials.form-header')
@endsection

@section('main-content')
    <!-- ================= CONTENT AREA ================== -->
    <div class="container-fluid">

    @php
        $title = "History for Split Invoices";
        $added_subtitle =  Breadcrumbs::render('shows-trainer-list-history', $show_id);
    @endphp
    @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle])

    <!-- Content Panel -->
        <div class="white-board">
            <div class="col-sm-12">
                <div class="row">
                    <div class="info">
                        @if(Session::has('message'))
                            <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                        @endif
                    </div>
                </div>
            </div>

        <div class="row">
          <div class="col-sm-12">
            <!-- if(!$collection->count()) -->
            @if($collection == null)
                <div class="col-lg-12 col-md-12 col-sm-12">No trainer registered yet!</div>
            @else

                  <div class="col-md-12">
                      <div class="table-responsive module-holer rr-datatable">
                          <table id="crudTable2" class="table table-line-braker mt-10 custom-responsive-md">
                                <thead class="hidden-xs">
                                   <tr>
                                      <th scope="col">#</th>
                                      <th scope="col">Date</th>
                                      <th scope="col">Divided Among</th>
                                      <th scope="col">Total Amount</th>
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
                                            <tr>
                                                <td>{{ $serial }}</td>
                                                <td><span class="table-title">Date</span>{{getDates($pResponse->created_at)}}</td>
                                                <td><span class="table-title">Divided Among</span> {{$pResponse->divided_amoung}}</td>
                                                <td><span class="table-title">Total</span> {{getpriceFormate($pResponse->total_amount)}}</td>
                                                 <td class="action">
                                                    <span class="table-title">Actions</span>
                                                      <a href="{{URL::to('shows') }}/view-trainers/{{nxb_encode($pResponse->id)}}/history"><i data-toggle="tooltip" title="View split" class="fa fa-eye"></i> </a>
                                                   </td>
                                               
                                            </tr>

                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                       </div>

                </div>
            @endif
          </div>
        </div>
        <!-- Tab containing all the data tables -->
   
    
@endsection

@section('footer-scripts')
    <script src="{{ asset('/js/ajax-dynamic-assets-table.js') }}"></script>
    @include('layouts.partials.datatable')
@endsection
