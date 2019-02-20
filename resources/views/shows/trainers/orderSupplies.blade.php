@extends('layouts.equetica2')

@section('custom-htmlheader')
    @include('layouts.partials.form-header')
@endsection

@section('main-content')
    <!-- ================= CONTENT AREA ================== -->
        <div class="container-fluid">

        @php
            $title = "Order Form";
            $added_subtitle = Breadcrumbs::render('shows-trainer-order-supplies');
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
                <div class="">
                    <div class="col-lg-5 col-md-5 col-sm-6">{{NO_CLASSES_RESPONSE}}</div>
                </div>
            @else
                    {!! Form::open(['url'=>'shows/trainer/order/supplies','method'=>'post','files'=>true,'class'=>'form-horizontal dropzone targetvalue','id'=>'orderSupplyRequest']) !!}
                    <input type="hidden" name="step2" value="1">
                    <input type="hidden" name="show_id" value="{{$show_id}}">
                    <div id="indivisuals" class="tab-pane">


                        <div class="">

                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-2 pr-0">
                                        <div class="mr-20" style="float: left;">
                                            <label style="">Order As:</label>
                                        </div>
                                    </div>

                                    <div class="col-md-1">

                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input class="form-check-input" value="2" name="ordered_as" id="legendRadio2" checked="checked" type="radio">
                                                <span>Rider</span>
                                            </label>
                                        </div>
                                    </div>
                                    @if(getCurrentUserAsTrainer($show_id))

                                    <div class="col-md-1">

                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input class="form-check-input" value="1" name="ordered_as" id="legendRadio2" type="radio">
                                                <span>Trainer</span>
                                            </label>
                                        </div>
                                    </div>
                                    @endif

                                    
                                </div>

                            </div>
                        </div>

                        <div class="col-md-12 rider-horses">
                            @if(isset($riderHorses))
                                <div class="row">
                                <div class="col-sm-1"><label style="">Horses:</label></div>
                                    <div class="col-sm-6 pl-0">
                                        <select required="required" multiple name="selected_horses[]" class="selectpicker form-control" placeholder="Select Classes to Combine" multiple data-size="8" data-selected-text-format="count>6" id="allAssets" data-live-search="true">
                                          @foreach($riderHorses as $option)
                                              <option value="{{$option->horse_id}}" @if(!empty($combined_class)) {{ (in_array($option->horse_id, $combined_class) ? "selected":"") }} @endif> {{ GetAssetName($option->horse) }}</option>
                                          @endforeach
                                      </select>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6 mt-20">

                            <label style="">Order Title</label>
                                <input required  name="order_title" class="form-control-inline" value="{{\Carbon\Carbon::today()->format('m-d-Y')}}">
                        </div>
                        <div class="table-responsive">
                            <table id="crudTable3" class="table table-line-braker mt-10 custom-responsive-md">

                                <thead class="hidden-xs">
                                <tr>
                                    <th scope="col" width="5%">#</th>
                                    <th scope="col" width="15%">Title</th>
                                    <th scope="col" width="15%">Description</th>
                                    <th scope="col" width="10%">Price</th>
                                    <th scope="col" width="10%">Quantity</th>
                                    <th scope="col" width="15%">Price x Qty</th>
                                    <th class="" width="30%">Actions</th>
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
                                            <td><span class="table-title">Quantity</span><input type="number" name="additional[{{$serial}}][qty]" class="col-sm-8 numberQty" min="1" max="999" value="0" placeholder="qty" data-unit-price="{{$pResponse->amount}}" disabled="disabled"></td>
                                            <td><span class="table-title">Price</span>@if(isset($pResponse->amount)) <div class="priceinqty">${{ $pResponse->amount }}</div> @else No Price Set. @endif</td>
                                        <!--  <td><strong class="visible-xs">Date</strong>{{ GetSpecificFormField($pResponse,"Date")  }}</td>
                                                <td><strong class="visible-xs">Location</strong>{{ GetSpecificFormField($pResponse,"Location")  }}</td>
                                                 --> <td class="action">
                                                <span class="table-title">Actions</span>
                                              <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-check">
                                                    <label class="form-check-label">
                                                    <input  name="additional[{{$serial}}][id]" class="checkbox-additional form-check-input" data-attr="additional-charges" type="checkbox" value="{{$pResponse->id}}">
                                                    <span>Check to Enter Quantity Amount</span>
                                                    </label>
                                                    </div>
                                                </div>
                                                <div class="offset-1 col-md-11 destination-horse-select" style="padding-left: 0px; display:none">
                                                    @if(isset($stalls))
                                                        <div class="row">
                                                            <div class="col-sm-12"> 
                                                                <label>Stall Location:</label>
                                                            </div>
                                                            <div class="col-sm-12">
                                                                <select multiple name="additional[{{$serial}}][destination_stall][]" class="selectpicker form-control" placeholder="Select Classes to Combine" multiple data-size="8" data-selected-text-format="count>6" data-live-search="true" disabled="disabled">
                                                                  @foreach($stalls as $option)
                                                                      <option value="{{$option}}"> {{ $option }}</option>
                                                                  @endforeach
                                                              </select>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                              </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="Totals row">
                            <div class="col-md-12">
                            <div class="row">
                                <div class="col-sm-6">

                                <fieldset class="form-group">
                                    <textarea name="trainer_comments" class="form-control form-control-lg" placeholder="Please enter comments here"></textarea>
                                </fieldset>

                                </div>

                                <div class="col-sm-6">
                                    <div class="row">

                                    <div class="col-sm-6 border-bottom"><b> Total Amount: </b></div>
                                    <div class="col-sm-6 addAdditionalPrice border-bottom">($) 0<input type="hidden" class="additionalPrice" name="additional_charges" value="0"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    </div>
                    <div class="row">
                        <div class="col-sm-12 mb-20">
                                <input type="submit" value="Submit Order" class="btn btn-primary pull-right">
                        </div>
                    </div>
                    {!! Form::close() !!}

                </div>
            @endif
        </div>
    </div>
    <!-- Tab containing all the data tables -->


@endsection

@section('footer-scripts')
    <script src="{{ asset('/js/shows/order-supplies.js') }}"></script>
    @include('layouts.partials.datatable')

    <style>

        label {
            text-transform: uppercase;
            float: left;
            padding-left: 0px;
            margin-left: 0px;
            margin-right: 10px;
            line-height: 35px;
        }
        .primary-table tr td:last-child a {
            color: #000000 !important;
            float: none !important;
            margin: 0px !important;
        }
        .form-control {
            display: block;
            width: 90%;
            height: 34px;
            padding: 6px 12px;
            font-size: 14px;
            line-height: 1.42857143;
            color: #555;
            background-color: #fff;
            background-image: none;
            /*border: 1px solid #ccc;*/
            border-radius: 4px;
            -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,0.075);
            box-shadow: inset 0 1px 1px rgba(0,0,0,0.075);
            -webkit-transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
            -o-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
            transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
            float: left;
        }

    </style>

@endsection