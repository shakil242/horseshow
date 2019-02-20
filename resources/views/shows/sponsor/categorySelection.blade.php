@extends('layouts.equetica2')

@section('custom-htmlheader')
    @include('layouts.partials.form-header')
@endsection

@section('main-content')
    <!-- ================= CONTENT AREA ================== -->
    <div class="main-contents">
        <div class="container-fluid">

        @php
            $title = getShowName($data['show_id']);

            $added_subtitle = '';
        @endphp
        @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle])

        <!-- Content Panel -->
            <div class="white-board">


    <div class="row">
        <div class="info">
            @if(Session::has('message'))
                <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            @if($collection->count())
                <div class="">
                    <input type="hidden" name="step2" value="1">
                    <input type="hidden" name="show_id" value="{{$data['show_id']}}">

                    <div id="indivisual" class="tab-pane">
                        <div class="table-responsive  rr-datatable indivisual-fixed-y  catCollection">
                            <table class="table table-line-braker mt-10 custom-responsive-md Datatable_nopagination">

                                <thead class="hidden-xs">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Category Title</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Description</th>
                                    <th class="action">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <input id="csrf-token" type="hidden" value="{{csrf_token()}}">

                                @if(sizeof($collection)>0)
                                    @foreach($collection as $pResponse)
                                        <?php
                                        $serial = $loop->index + 1;
                                        $roleName = '';

                                        ?>
                                            <tr class="tr-row">
                                                <td>{{ $serial }}</td>
                                                <td><span class="table-title">Category Title</span> {{$pResponse->category_title}}</td>
                                                <td><span class="table-title">Price</span
                                                        <div style="float:left"> ($) {{$pResponse->category_price}}
                                                        </div>
                                                </td>
                                                <td><span class="visible-xs">Description</span>
                                                    {{$pResponse->category_description}}
                                                </td>
                                                <td class="action">
                                                    <span class="table-title">Actions</span>
                                                    <div class="form-check">
                                                        <label class="form-check-label">
                                                            <input class="form-check-input" name="category_selected[]" onchange="selectCategory('{{$data['show_id']}}','{{$sponsorFormId}}')" type="checkbox" data-attr="assets-charges" value="{{$pResponse->id}}">
                                                            <span>&nbsp;</span>
                                                        </label>
                                                    </div>
                                                </td>
                                            </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>

                        <div class="amountContainer">

                        <div class="row">
                       <div class="col-sm-7">&nbsp</div>
                        <div class="col-sm-5 pull-right border-bottom">
                            <div class="row">
                            @php $royaltyFinal = 0 @endphp
                            <div class="col-sm-7"><b>Miscellaneous Charges: </b></div>
                            <div class="col-sm-5 addAdditionalPrice">
                            ($) 0
                            </div>
                            </div>
                        </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-7">&nbsp</div>

                        <div class="col-sm-5 pull-right border-bottom">
                            <div class="row">

                            <div class="col-sm-7"><b>(Federal + State Tax): </b></div>
                            @php
                            $taxFederal=0;
                            $taxState=0;
//
//                            $tfederal = $MS->federal_tax;
//                            $tstate = $MS->state_tax;
//                            $taxFederal = ($tfederal*$total)/100;
//                            $taxState = ($tstate*$total)/100;
                            @endphp
                            <input type="hidden" name="federal_tax" value="{{$taxFederal}}">
                            <input type="hidden" name="state_tax" value="{{$taxState}}">
                            <div class="col-sm-5 taxPrice">($) {{$taxFederal}}</div>
                            </div>
                        </div>
                        </div>

                        <div class="Totals row">
                            <div class="col-sm-7">&nbsp</div>

                            <div class="col-sm-5 pull-right border-bottom" >
                                <div class="row">
                                <div class="col-sm-7"><b>Amount: </b></div>
                                <div class="col-sm-5 addAssetPrice">($) 0</div>
                            </div>
                            </div>
                        </div>




                    <div class="row mb-20">
                        <div class="col-sm-3">&nbsp</div>
                        @if($paypalAccountDetail>0)
                        <div class="col-sm-2">
                                <button  href="javascript:"  class="btn-primary btn stripeBtn" name="type" value="paypal">Pay By Paypal</button>
                            </div>
                        @endif
                        @if($stripeDetails>0)
                        <div class="col-sm-2"  >
                            <button type="submit" class="stripe-button-el stripeBtn" style="visibility: visible; border: none; padding: 0px;"><span style="display: block; min-height: 30px;">Pay with Card</span></button>
                        </div>
                        @endif
                        <div class="col-sm-2" >
                            <button href="javascript:" class="btn-primary btn strp stripeBtn">Cancel</button>
                        </div>


                    </div>
                </div>
                </div>
                </div>
            @else
                <div class="">
                    <div class="col-lg-5 col-md-5 col-sm-6">{{NO_CLASSES_RESPONSE}}</div>
                </div>
            @endif
        </div>
    </div>
    <!-- Tab containing all the data tables -->


@endsection

@section('footer-scripts')

    <script type="text/javascript">

    localStorage.setItem('lastTab', "#invited_assets");
    var show_id = '{{$data['show_id']}}';
    var sponsorFormId = '{{$sponsorFormId}}';
    </script>

    <script src="{{ asset('/js/custom-function.js') }}"></script>
    <link href="{{ asset('/css/custom.css') }}" rel="stylesheet" />
    @include('layouts.partials.datatable')
<style>
    label { width: 10em; float: left; }
    label.error { float: none; color: red; padding-left: .5em; vertical-align: top; }
    p { clear: both; }
    .submit { margin-left: 12em; }
    em { font-weight: bold; padding-right: 1em; vertical-align: top; }

    .dataTables_filter {
        float: right;
        margin-right: 69px;
    }
    .stripe-button-el span {
        display: block;
        position: relative;
        padding: 0 12px;
        height: 30px;
        line-height: 30px;
        background: #1275ff;
        font-size: 14px;
        color: #fff;
        font-weight: bold;
        font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
        text-shadow: 0 -1px 0 rgba(0,0,0,0.25);
        box-shadow: inset 0 1px 0 rgba(255,255,255,0.25);
        border-radius: 4px;
        background-image: -webkit-linear-gradient(#28a0e5,#015e94);
        background-image: linear-gradient(#28a0e5,#015e94);
        -webkit-font-smoothing: antialiased;
    }

</style>
@endsection
