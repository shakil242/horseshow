@extends('layouts.equetica2')

@section('custom-htmlheader')
    @include('layouts.partials.form-header')
@endsection

@section('main-content')
    <!-- ================= CONTENT AREA ================== -->
    <div class="main-contents">
        <div class="container-fluid">

        @php
        $spons = 'Sponsor Date: '.formatDate($item->created_at);
        $loc = 'Show Address: '.$arr['location'];
        $contact = 'Contact Information: '.$arr['contact_information'];

            $title = getShowName($item->show_id);
            $added_subtitle = '<p><span>'.$spons.'</span>';
            $added_subtitle .= '<span>'.$loc.'</span>';
            $added_subtitle .= '<span>'.$contact.'</span></p>';



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
                                <input type="hidden" name="show_id" value="{{$item->show_id}}">
                                <div id="indivisual" class="tab-pane">
                                    <div class="table-responsive  rr-datatable indivisual-fixed-y  catCollection">
                                        <input type="hidden" id="fileExport" value="{{$title}}-{{formatDate($item->created_at)}}-sponsor-invoice">
                                        <input type="hidden" id="Title" value="{!! $title  !!} ">
                                        <input type="hidden" id="sponsorTitle" value="{!! $spons  !!} ">
                                        <input type="hidden" id="locExportTitle" value="{!! $loc  !!} ">
                                        <input type="hidden" id="ContactExportTitle" value="{!! $contact  !!} ">

                                        <table class="table table-line-braker mt-10 custom-responsive-md" id="dataTableSponsorInvoice">

                                            <thead class="hidden-xs">


                                            <tr>
                                                <th width="10%" scope="col">#</th>
                                                <th width="10%" scope="col">Category Title</th>
                                                <th width="10%" scope="col">Price</th>
                                                <th width="70%" scope="col">Description</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @php $totalAmount = 0 ; @endphp
                                            @foreach($collection as $pResponse)
                                                @php $catCollection = $pResponse->hasCategory; @endphp
                                                @if(count($catCollection)>0)
                                                    @foreach($catCollection as $cat)
                                                        <?php
                                                        $serial = $loop->index + 1;
                                                        $totalAmount   = $totalAmount + $cat->category_price;
                                                        ?>
                                                    <tr class="tr-row">
                                                        <td>{{ $serial }}</td>
                                                        <td>{{$cat->category_title}}</td>
                                                        <td>${{ $cat->category_price }}</td>
                                                        <td>{{$cat->category_description}}</td>

                                                    </tr>
                                                @endforeach
                                            @endif
                                            @endforeach


                                       @php
                                        $royaltyFinal = twodecimalformate($totalAmount / 100 *  $arr['royalty']);
                                        $taxFederal = ($arr['taxFederal'] * $totalAmount) / 100;
                                        $taxState = ($arr['taxState'] * $totalAmount) / 100;
                                        $taxTotal = twodecimalformate($total = $taxState + $taxFederal);
                                        $grandTotal = $taxTotal + $royaltyFinal + $totalAmount;
                                       @endphp
                                            <tr style="border: none">
                                                <td style="border: none">&nbsp</td>
                                                <td style="border: none">&nbsp</td>
                                                <td style="border: none">&nbsp</td>
                                                <td style="text-align: right;border-bottom: none;">&nbsp</td>
                                            </tr>
                                        <tr style="border: none">
                                            <td style="border: none">&nbsp</td>
                                            <td style="border: none">&nbsp</td>
                                            <td style="border: none">&nbsp</td>
                                        <td style="text-align: right"><b>Miscellaneous Charges: </b>($) {{$royaltyFinal}}</td>
                                        </tr>
                                        <tr>
                                            <td style="border: none">&nbsp</td>
                                            <td style="border: none">&nbsp</td>
                                            <td style="border: none">&nbsp</td>
                                            <td style="text-align: right"><b>(Federal + State Tax): </b>($) {{$taxTotal}}</td>
                                        </tr>
                                        <tr>
                                            <td style="border: none">&nbsp</td>
                                            <td style="border: none">&nbsp</td>
                                            <td style="border: none">&nbsp</td>
                                            <td valign="right" align="right" style="border-bottom: none; text-align: right;float:right"><strong>Total Amount: </strong> ($) {{$grandTotal}} </td>
                                        </tr>
                                            </tbody>
                                        </table>
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
                    @include('layouts.partials.datatable')

                    <style>
                        .dt-button.buttons-pdf.buttons-html5.btn.btn-success {
                            float: right;
                        }
                        p span
                        {
                            display: block;
                        }
                    </style>

                @endsection
