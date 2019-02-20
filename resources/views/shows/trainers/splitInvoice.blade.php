@extends('layouts.equetica2')

@section('custom-htmlheader')
    @include('layouts.partials.form-header')
@endsection

@section('main-content')
    <!-- ================= CONTENT AREA ================== -->
    <div class="container-fluid">

    @php
        $title = "Split Invoice";
        $added_subtitle = Breadcrumbs::render('shows-trainer-split-invoice');
    @endphp
    @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle])

    <!-- Content Panel -->
        <div class="white-board">
            <div class="col-sm-12">
                <div class="row">
                    <div class="info text-center">
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
            @if(!$additional_price->count())

                    <div class="col-lg-12 col-md-12 col-sm-12">{{NO_CLASSES_RESPONSE}}</div>
            @else
                <div class="row">
                    <div class="col-md-12">

                    {!! Form::open(['url'=>'shows/trainer/split/invoice','method'=>'post','files'=>true,'class'=>'form-horizontal dropzone targetvalue','id'=>'splitClassForm'
                 ]) !!}
                    <input type="hidden" name="step2" value="1">
                    <input type="hidden" name="show_id" value="{{$show_id}}">

                    <div class="col-md-4">
                        <h3>Additional Charges</h3>
                        <select title="Please Select Orders" multiple  onchange="getSupplyOrder($(this))"
                     class="selectpicker show-tick form-control" multiple data-size="8" data-selected-text-format="count>6" id="allOrders"
                                data-live-search="true">
                           
                            @foreach($suppliesOrders as $row)
                            <option value="{{$row->id}}">{{$row->order_title}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12">
                    <div class="table-responsive module-holer rr-datatable">
                        <table id="crudTable3" class="table table-line-braker mt-10 custom-responsive-md">
                                <thead class="hidden-xs">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Title</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Price x Qty</th>
                                    <th style="width:21%">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(sizeof($additional_price)>0)
                                    @foreach($additional_price as $pResponse)
                                        <?php
                                        $serial = $loop->index + 1;
                                        $roleName = '';
                                        ?>
                                        <tr class="tr-row additiona-charges-row">
                                            <td>{{ $serial }}</td>
                                            <td><span class="table-title">Title</span> {{$pResponse->title}}</td>
                                            <td><span class="table-title">Description</span>{{ $pResponse->description }}</td>
                                            <td><span class="table-title">Price</span>@if(isset($pResponse->amount)) ${{$pResponse->amount }} <input type="hidden" name="additional[{{$serial}}][price]" class="priceSet" value="{{$pResponse->amount}}"> @else No Price Set. @endif</td>
                                            <td><span class="table-title">Quantity</span><input type="number" name="additional[{{$serial}}][qty]" class="col-sm-8 numberQty orderQty-{{$pResponse->id}}" min="0" max="999" value="0" placeholder="qty" data-unit-price="{{$pResponse->amount}}" disabled="disabled"></td>
                                            <td><span class="table-title">Price</span>@if(isset($pResponse->amount)) <div class="priceinqty">${{$pResponse->amount }} </div> @else No Price Set. @endif</td>
                                        <!--  <td><strong class="visible-xs">Date</strong>{{ GetSpecificFormField($pResponse,"Date")  }}</td>
                                                <td><strong class="visible-xs">Location</strong>{{ GetSpecificFormField($pResponse,"Location")  }}</td>
                                                 --> <td class="action">
                                                <span class="table-title">Actions</span>
                                                <div class="form-check">
                                                     <label class="form-check-label">
                                                <input name="additional[{{$serial}}][id]" class="form-check-input checkbox-additional orderSupply-{{$pResponse->id}}" data-attr="additional-charges" type="checkbox" value="{{$pResponse->id}}">
                                                         <span>Check to Enter Quantity Amount</span>

                                                     </label>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="Totals row">
                            <div class="col-sm-12">
                                <div class="row">

                                <div class="col-sm-6">
                                    <span>
                                        <label> Add Comments: </label>
                                        <textarea class="form-control" name="comment"></textarea>
                                    </span>
                                </div>
                                <div class="col-sm-6">
                                    <div class="row mt-40">
                                    <div class="col-sm-5 border-bottom"><b> Additional Charges: </b></div>
                                    <div class="col-sm-7 addAdditionalPrice border-bottom">($) 0<input type="hidden" class="additionalPrice" name="additional_charges" value="0"></div>
                                    </div>
                                </div>
                        </div>

                    </div>

                    <div class="col-md-12 mt-40">

                            <div class="table-responsive module-holer rr-datatable">
                                <table id="crudTable2" class="table table-line-braker mt-10 custom-responsive-md">
                                    <thead class="hidden-xs">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Horse</th>
                                        <th scope="col">Registered On</th>
                                        <th class="action">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(sizeof($users)>0)
                                        @foreach($users as $pResponse)
                                            
                                           @php
                                            $serial = $loop->index + 1;
                                            $roleName = '';
                                            @endphp

                                            <tr class="tr-row">
                                                <td>{{ $serial }}</td>
                                                <td><span class="table-title">Name</span> {{$pResponse->user->name}}</td>
                                                @if(isset($pResponse->horse))
                                                <td><span class="table-title">Horse</span>{!! getHorseNameAsLink($pResponse->horse) !!}</td>
                                                @endif
                                                
                                                <td><span class="table-title">Registered On</span>{{getDates($pResponse->created_at)}} </td>
                                                <td class="action">
                                                    <span class="table-title">Actions</span>
                                                    <div class="form-check">
                                                        <label class="form-check-label">
                                                    <input name="MSR_ids[]" class="form-check-input" type="checkbox" data-attr="assets-charges" value="{{$pResponse->id}}">
                                                        <span>&nbsp</span>
                                                        </label>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            @if(sizeof($users)>0)
                                <input type="submit" value="Split Invoice" class="btn btn-large btn-success">
                            @endif
                        </div>
                    </div>
                    <br>
                    {!! Form::close() !!}
                    </div>
                </div>
            @endif
        </div>
    </div>
    <!-- Tab containing all the data tables -->


@endsection

@section('footer-scripts')
       <script src="{{ asset('/js/shows/classes-pricing.js') }}"></script>
    @include('layouts.partials.datatable')
    <script src="{{ asset('/js/supply-order.js') }}"></script>


@endsection