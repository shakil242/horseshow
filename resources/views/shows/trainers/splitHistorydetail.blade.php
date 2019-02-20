@extends('layouts.equetica2')

@section('custom-htmlheader')
    @include('layouts.partials.form-header')
@endsection

@section('main-content')
    <!-- ================= CONTENT AREA ================== -->
    <div class="container-fluid">

    @php
        $title = "Split History Detail";
        $added_subtitle = Breadcrumbs::render('shows-trainer-split-history-detail',nxb_encode($split->show_id));
    @endphp
    @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle])

    <!-- Content Panel -->
        <div class="white-board">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-sm-12 action-holder">
                        <a href="{{URL::to('shows') }}/{{nxb_encode($split->id)}}/pdf/trainer/view-invoice" class="btn ml-15 btn-primary pull-right"> Export PDF</a>
                        <a href="#" onclick="window.print();" class="btn btn-primary pull-right"> Print </a>
                    </div>
                    <div class="info">
                        @if(Session::has('message'))
                            <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                        @endif
                    </div>
                </div>
            </div>

    <div class="row">
        <div class="col-sm-12">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if(!$split)
                <div class="">
                    <div class="col-lg-5 col-md-5 col-sm-6">{{NO_CLASSES_RESPONSE}}</div>
                </div>
            @else

                    <div class="col-md-12">
                        <div class="table-responsive module-holer rr-datatable">
                            <h3>Additional Charges</h3>

                            <table id="crudTabl" class="table table-line-braker mt-10 custom-responsive-md">
                                <thead class="hidden-xs">
                                   <tr>
                                      <th scope="col">#</th>
                                      <th scope="col">Title</th>
                                      <th scope="col">Description</th>
                                      <th scope="col">Price</th>
                                      <th scope="col">QTY</th>
                                      <th scope="col">Total </th>
                                    </tr>
                                </thead>
                                <tbody>
                                            <?php 
                                                if (isset($split->additional_fields)>0) {
                                                    $splite_additional = json_decode($split->additional_fields);
                                                }else{
                                                    $splite_additional = null;
                                                }

                                            ?>
                                          @if($splite_additional)
                                            <?php $splitPrice= 0; ?>
                                            @foreach($splite_additional as $pResponse)
                                             @if(isset($pResponse->id))
                                             <?php $serial = $loop->index + 1;  ?>
                                            <tr class="tr-row additiona-charges-row">
                                                <td>{{ $serial }}</td>
                                                <td><span class="table-title">Title</span> {{AdditionalCharge($pResponse->id)}}</td>
                                                <td><span class="table-title">Description</span>{{ AdditionalCharge($pResponse->id,1) }}</td>
                                                <td><span class="table-title">Price</span><div class="priceinqty">{{getpriceFormate($pResponse->price)}}</div></td>
                                                <td><span class="table-title">Qty</span>{{$pResponse->qty}}</td>
                                                <td><span class="table-title">Total Price</span>@if(isset($pResponse->price)) <div class="priceinqty">
                                                <?php 
                                                  $AllCurrentVals = $pResponse->price*$pResponse->qty;
                                                  $splitPrice = (float)$splitPrice+(float)$AllCurrentVals; ?> ($){{ number_format(($pResponse->price*$pResponse->qty ),2) }}</div> @else No Price Set. @endif
                                                </td>
                                            </tr>
                                            @endif
                                            @endforeach
                                          @endif
                                </tbody>
                            </table>
                       </div>
                        <div class="table-responsive module-holer rr-datatable">
                                <h3>Divided Users</h3>
                            <table id="crudTabl" class="table table-line-braker mt-10 custom-responsive-md">
                                <thead class="hidden-xs">
                                   <tr>
                                      <th scope="col">#</th>
                                      <th scope="col">Name</th>
                                      <th scope="col">Horse Name</th>
                                      <th scope="col">Registered On</th>
                                   </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                       $users = getShowInvoiceUsers($split->unique_batch);
                                    ?>
                                  @if($users)
                                    @foreach($users as $pResponse)
                                     @if(isset($pResponse->classHorse))
                                     <?php $serial = $loop->index + 1;  ?>
                                    <tr class="tr-row additiona-charges-row">
                                        <td>{{ $serial }}</td>
                                        <td><span class="table-title">Name</span>{{$pResponse->classHorse->user->name}}</td>
                                        <td><span class="table-title">Horse Name</span> {!! getHorseNameAsLink($pResponse->classHorse->horse)!!} [Entry# {{$pResponse->classHorse->horse_reg}} ]</td>
                                        <td><span class="table-title">Registered On</span>{{ getDates($pResponse->classHorse->created_at) }}</td>
                                    </tr>
                                    @endif
                                    @endforeach
                                  @endif
                                </tbody>
                            </table>
                       </div>
                       <div class="Totals row">
                           <div class="col-md-7">
                             <label><b>Comments:</b>
                               {{$split->comment}}
                               </label>
                           </div>
                           <div class="col-sm-5">
                               <div class="row">
                               <div class="col-sm-4 border-bottom"><b> Split Charges: </b></div>
                              <div class="col-sm-8 addAssetPrice border-bottom">{{getpriceFormate($splitPrice)}}
                                  <input type="hidden" class="splitcharges" name="split_charges" value="{{$splitPrice}}"></div>
                           </div>
                           </div>
             
                       </div>
                  </div>
            @endif
        </div>
    </div>
        </div>
    </div>
    <!-- Tab containing all the data tables -->


@endsection

@section('footer-scripts')
    <script src="{{ asset('/js/shows/classes-pricing.js') }}"></script>
    @include('layouts.partials.datatable')
@endsection