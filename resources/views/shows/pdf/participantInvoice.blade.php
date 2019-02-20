<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

<html>

<head>

<style>
@page {
size: 21cm 29.7cm;
margin-top: 1cm;
margin-bottom: 0cm;
border: 1px solid blue;
page-break-after: always;
}

#header {
position: fixed;
left: 0px;
top: -180px;
right: 0px;
border: none
}

#footer {
position: fixed;
left: 0px;
bottom: -180px;
right: 0px;
height: 150px;
background-color: lightblue;
}

#footer .page:after {
content: counter(page, upper-roman);
}

table {
border: solid 1px #cdcdcd
}

td {
text-align: justify;
padding: 10px;
}

td.test1 {
width: 20%
}

td.test2 {
width: 70%
}

.page-break {
page-break-before: always;
}

.col-sm-12 {
width: 100%;
}

.col-sm-6 {
width: 50%;
}

.col-sm-2 {
width: 16.6667%;
}

.col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12 {
min-height: 1px;
padding-left: 15px;
padding-right: 15px;
position: relative;

}

label {
display: inline-block;
font-weight: bold;
margin-bottom: 5px;
max-width: 100%;
}

.col-sm-8 {
width: 66.6667%;
}
table {
  background-color: transparent;
}
caption {
  padding-top: 8px;
  padding-bottom: 8px;
  color: #777;
  text-align: left;
}
th {
  text-align: left;
}
.table {
  width: 100%;
  max-width: 100%;
  margin-bottom: 20px;
}
.table > thead > tr > th,
.table > tbody > tr > th,
.table > tfoot > tr > th,
.table > thead > tr > td,
.table > tbody > tr > td,
.table > tfoot > tr > td {
  padding: 8px;
  line-height: 1.42857143;
  vertical-align: top;
  border-top: 1px solid #ddd;
}
.table > thead > tr > th {
  vertical-align: bottom;
  border-bottom: 2px solid #ddd;
}
.table > caption + thead > tr:first-child > th,
.table > colgroup + thead > tr:first-child > th,
.table > thead:first-child > tr:first-child > th,
.table > caption + thead > tr:first-child > td,
.table > colgroup + thead > tr:first-child > td,
.table > thead:first-child > tr:first-child > td {
  border-top: 0;
}
.table > tbody + tbody {
  border-top: 2px solid #ddd;
}
.table .table {
  background-color: #fff;
}
.table-condensed > thead > tr > th,
.table-condensed > tbody > tr > th,
.table-condensed > tfoot > tr > th,
.table-condensed > thead > tr > td,
.table-condensed > tbody > tr > td,
.table-condensed > tfoot > tr > td {
  padding: 5px;
}
.table-bordered {
  border: 1px solid #ddd;
}
.table-bordered > thead > tr > th,
.table-bordered > tbody > tr > th,
.table-bordered > tfoot > tr > th,
.table-bordered > thead > tr > td,
.table-bordered > tbody > tr > td,
.table-bordered > tfoot > tr > td {
  border: 1px solid #ddd;
}
.table-bordered > thead > tr > th,
.table-bordered > thead > tr > td {
  border-bottom-width: 2px;
}
.table-striped > tbody > tr:nth-of-type(odd) {
  background-color: #f9f9f9;
}
.table-hover > tbody > tr:hover {
  background-color: #f5f5f5;
}
table col[class*="col-"] {
  position: static;
  display: table-column;
  float: none;
}
table td[class*="col-"],
table th[class*="col-"] {
  position: static;
  display: table-cell;
  float: none;
}
.table > thead > tr > td.active,
.table > tbody > tr > td.active,
.table > tfoot > tr > td.active,
.table > thead > tr > th.active,
.table > tbody > tr > th.active,
.table > tfoot > tr > th.active,
.table > thead > tr.active > td,
.table > tbody > tr.active > td,
.table > tfoot > tr.active > td,
.table > thead > tr.active > th,
.table > tbody > tr.active > th,
.table > tfoot > tr.active > th {
  background-color: #f5f5f5;
}
.table-hover > tbody > tr > td.active:hover,
.table-hover > tbody > tr > th.active:hover,
.table-hover > tbody > tr.active:hover > td,
.table-hover > tbody > tr:hover > .active,
.table-hover > tbody > tr.active:hover > th {
  background-color: #e8e8e8;
}
.table > thead > tr > td.success,
.table > tbody > tr > td.success,
.table > tfoot > tr > td.success,
.table > thead > tr > th.success,
.table > tbody > tr > th.success,
.table > tfoot > tr > th.success,
.table > thead > tr.success > td,
.table > tbody > tr.success > td,
.table > tfoot > tr.success > td,
.table > thead > tr.success > th,
.table > tbody > tr.success > th,
.table > tfoot > tr.success > th {
  background-color: #dff0d8;
}
.table-hover > tbody > tr > td.success:hover,
.table-hover > tbody > tr > th.success:hover,
.table-hover > tbody > tr.success:hover > td,
.table-hover > tbody > tr:hover > .success,
.table-hover > tbody > tr.success:hover > th {
  background-color: #d0e9c6;
}
.table > thead > tr > td.info,
.table > tbody > tr > td.info,
.table > tfoot > tr > td.info,
.table > thead > tr > th.info,
.table > tbody > tr > th.info,
.table > tfoot > tr > th.info,
.table > thead > tr.info > td,
.table > tbody > tr.info > td,
.table > tfoot > tr.info > td,
.table > thead > tr.info > th,
.table > tbody > tr.info > th,
.table > tfoot > tr.info > th {
  background-color: #d9edf7;
}
.table-hover > tbody > tr > td.info:hover,
.table-hover > tbody > tr > th.info:hover,
.table-hover > tbody > tr.info:hover > td,
.table-hover > tbody > tr:hover > .info,
.table-hover > tbody > tr.info:hover > th {
  background-color: #c4e3f3;
}
.table > thead > tr > td.warning,
.table > tbody > tr > td.warning,
.table > tfoot > tr > td.warning,
.table > thead > tr > th.warning,
.table > tbody > tr > th.warning,
.table > tfoot > tr > th.warning,
.table > thead > tr.warning > td,
.table > tbody > tr.warning > td,
.table > tfoot > tr.warning > td,
.table > thead > tr.warning > th,
.table > tbody > tr.warning > th,
.table > tfoot > tr.warning > th {
  background-color: #fcf8e3;
}
.table-hover > tbody > tr > td.warning:hover,
.table-hover > tbody > tr > th.warning:hover,
.table-hover > tbody > tr.warning:hover > td,
.table-hover > tbody > tr:hover > .warning,
.table-hover > tbody > tr.warning:hover > th {
  background-color: #faf2cc;
}
.table > thead > tr > td.danger,
.table > tbody > tr > td.danger,
.table > tfoot > tr > td.danger,
.table > thead > tr > th.danger,
.table > tbody > tr > th.danger,
.table > tfoot > tr > th.danger,
.table > thead > tr.danger > td,
.table > tbody > tr.danger > td,
.table > tfoot > tr.danger > td,
.table > thead > tr.danger > th,
.table > tbody > tr.danger > th,
.table > tfoot > tr.danger > th {
  background-color: #f2dede;
}
.table-hover > tbody > tr > td.danger:hover,
.table-hover > tbody > tr > th.danger:hover,
.table-hover > tbody > tr.danger:hover > td,
.table-hover > tbody > tr:hover > .danger,
.table-hover > tbody > tr.danger:hover > th {
  background-color: #ebcccc;
}
.table-responsive {
  min-height: .01%;
  overflow-x: auto;
}
.col-xs-1, .col-sm-1, .col-md-1, .col-lg-1, .col-xs-2, .col-sm-2, .col-md-2, .col-lg-2, .col-xs-3, .col-sm-3, .col-md-3, .col-lg-3, .col-xs-4, .col-sm-4, .col-md-4, .col-lg-4, .col-xs-5, .col-sm-5, .col-md-5, .col-lg-5, .col-xs-6, .col-sm-6, .col-md-6, .col-lg-6, .col-xs-7, .col-sm-7, .col-md-7, .col-lg-7, .col-xs-8, .col-sm-8, .col-md-8, .col-lg-8, .col-xs-9, .col-sm-9, .col-md-9, .col-lg-9, .col-xs-10, .col-sm-10, .col-md-10, .col-lg-10, .col-xs-11, .col-sm-11, .col-md-11, .col-lg-11, .col-xs-12, .col-sm-12, .col-md-12, .col-lg-12 {
  position: relative;
  min-height: 1px;
  padding-right: 15px;
  padding-left: 15px;
}
.col-xs-1, .col-xs-2, .col-xs-3, .col-xs-4, .col-xs-5, .col-xs-6, .col-xs-7, .col-xs-8, .col-xs-9, .col-xs-10, .col-xs-11, .col-xs-12 {
  float: left;
}
@media screen and (max-width: 767px) {
  .table-responsive {
    width: 100%;
    margin-bottom: 15px;
    overflow-y: hidden;
    -ms-overflow-style: -ms-autohiding-scrollbar;
    border: 1px solid #ddd;
  }
  .table-responsive > .table {
    margin-bottom: 0;
  }
  .table-responsive > .table > thead > tr > th,
  .table-responsive > .table > tbody > tr > th,
  .table-responsive > .table > tfoot > tr > th,
  .table-responsive > .table > thead > tr > td,
  .table-responsive > .table > tbody > tr > td,
  .table-responsive > .table > tfoot > tr > td {
    white-space: nowrap;
  }
  .table-responsive > .table-bordered {
    border: 0;
  }
  .table-responsive > .table-bordered > thead > tr > th:first-child,
  .table-responsive > .table-bordered > tbody > tr > th:first-child,
  .table-responsive > .table-bordered > tfoot > tr > th:first-child,
  .table-responsive > .table-bordered > thead > tr > td:first-child,
  .table-responsive > .table-bordered > tbody > tr > td:first-child,
  .table-responsive > .table-bordered > tfoot > tr > td:first-child {
    border-left: 0;
  }
  .table-responsive > .table-bordered > thead > tr > th:last-child,
  .table-responsive > .table-bordered > tbody > tr > th:last-child,
  .table-responsive > .table-bordered > tfoot > tr > th:last-child,
  .table-responsive > .table-bordered > thead > tr > td:last-child,
  .table-responsive > .table-bordered > tbody > tr > td:last-child,
  .table-responsive > .table-bordered > tfoot > tr > td:last-child {
    border-right: 0;
  }
  .table-responsive > .table-bordered > tbody > tr:last-child > th,
  .table-responsive > .table-bordered > tfoot > tr:last-child > th,
  .table-responsive > .table-bordered > tbody > tr:last-child > td,
  .table-responsive > .table-bordered > tfoot > tr:last-child > td {
    border-bottom: 0;
  }
}
.visible-xs {display: none;}
.border-bottom {
    border-bottom: 2px solid #651e1c;
    margin-bottom: 10px;
}
</style>
</head>

<body>
<div class="container">
<div class="white-box">


	 	<div class="row">
  		 	<div class="col-sm-4">
          <h1>Shows</h1>
        </div>
        <div class="col-sm-5 action-holder">
          <!-- <form action="#">
            <div class="search-form">
              <input class="form-control input-sm" placeholder="Search Class Name" id="myInputTextField" type="search">
              <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
            </div>
          </form> -->
        </div>
        <div class="col-sm-3"></div>
      	</div>
         <div class="row">
        <div class="info">
            @if(Session::has('message'))
                <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
            @endif
        </div>
    </div>
      	<div class="row">
	        <div class="col-sm-12">
            
		         
	    	  </div>
      	</div>
        <div class="row">
          <div class="col-sm-12">
            @if($assets == null)
              <div class="">
                <div class="col-lg-5 col-md-5 col-sm-6">{{NO_CLASSES_RESPONSE}}</div>
              </div>
            @else
                <div class="tab-content">
                  {{--{!! Form::open(['url'=>'shows/invoice/payment','method'=>'post','files'=>true,'class'=>'form-horizontal dropzone targetvalue']) !!}--}}
                  <input type="hidden" name="show_id" value="{{$MSR->manage_show_id}}">
                  <input type="hidden" name="MSR" value="{{$MSR->id}}">
                  
                  <div id="indivisual" class="tab-pane fade in active">
                            <div class="module-holer rr-datatable">
                                <table id="crudTabl" class="table primary-table">
                                <thead class="hidden-xs">
                                   <tr>
                                      <th style="width:5%">#</th>
                                      <th>Title</th>
                                      <th>Code</th>
                                      <th>Price</th>
                                      <!-- <th>Date</th>
                                      <th>Location</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $assetTotal = 0 ?>
                                    @if(sizeof($assets)>0)
                                        @foreach($assets as $pResponse)
                                            <?php 
                                              $serial = $loop->index + 1; 
                                              $roleName = '';
                                            ?>
                                            @if(isset($pResponse->id))
                                            <tr class="tr-row">
                                                <td>{{ $serial }}</td>
                                                <td><strong class="visible-xs">Title</strong> {{GetAssetNamefromId($pResponse->id)}}<input type="hidden" name="assets[]" value="{{$pResponse->id}}"></td>
                                                <td><strong class="visible-xs">Code</strong>{{ GetSpecificFormField($pResponse->id,"USEF Section Code")  }}</td>
                                                <td><strong class="visible-xs">Price</strong>@if(isset($pResponse->price)) ($){{$pResponse->price }} <?php $assetTotal = $assetTotal + $pResponse->price; ?> <input type="hidden" class="priceSet" value="{{$pResponse->price}}"> @else No Price Set. @endif</td>
                                            </tr>
                                            @endif
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                       </div>
                       <div class="Totals row">
                           <div class="col-sm-5 pull-right">
                             
                              <div class="col-sm-3 border-bottom" style="float:left"><b> Class Price: </b></div>
                              <div class="col-sm-9 addAssetPrice border-bottom">($) {{$assetTotal}}<input type="hidden" class="AssetsPrice" name="assets_price" value="0"></div> 
                           </div>

             
                       </div>

                  </div>
                  <?php $prizeWon = 0; ?>
                  @if(sizeof($prize)>0)
                  <div>
                  <h3>Prize Money</h3>
                  <div id="indivisual" class="tab-pane fade in active">
                            <div class="module-holer rr-datatable">
                                <table id="crudTabl" class="table primary-table">
                                <thead class="hidden-xs">
                                   <tr>
                                      <th style="width:5%">#</th>
                                      <th>Class</th>
                                      <th>Position</th>
                                      <th>Prize Won</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $serial =0; ?>
                                        @foreach($prize as $one_asset)
                                          <?php $decode_asset = json_decode($one_asset->position_fields); ?>
                                          @foreach($decode_asset as $pResponse)
                                            @if(isset($pResponse->user_id) && $pResponse->user_id == $user_id)
                                            <?php  $serial = $serial + 1;?>
                                            <tr class="tr-row">
                                                <td>{{ $serial }}</td>
                                                <td><strong class="visible-xs">Class</strong>{{GetAssetNamefromId($one_asset->asset_id)}}</td>
                                                <td><strong class="visible-xs">Position</strong>{{getPostionText($pResponse->position)}}</td>
                                                <td><strong class="visible-xs">Price</strong>@if(isset($pResponse->price)) <div class="priceinqty"><?php $prizeWon = $prizeWon+$pResponse->price ?>($){{ $pResponse->price}}</div> @else No Price Set. @endif</td>
                                            </tr>
                                            @endif
                                          @endforeach
                                        @endforeach
                                        @if($serial ==0)
                                        <tr class="tr-row">
                                          <td colspan="4"> You have not won any position or prize!</td>
                                        </tr>
                                        @endif
                                </tbody>
                            </table>
                       </div>
                       <div class="Totals row">
                           <div class="col-sm-5 pull-right">
                             
                              <div class="col-sm-3 border-bottom" style="float:left"><b> Prize Won: </b></div>
                              <div class="col-sm-9 addAssetPrice border-bottom">($) {{$prizeWon}}<input type="hidden" class="PrizeWon" name="prize_won" value="{{$prizeWon}}"></div> 
                           </div>

             
                       </div>

                  </div>
                  </div>
                  @endif

                  <h3>Additional Charges</h3>
                  <div id="indivisuals" class="tab-pane fade in active">
                            <div class="module-holer rr-datatable">
                                <table id="crudTabl" class="table primary-table">
                                <thead class="hidden-xs">
                                   <tr>
                                      <th style="width:5%">#</th>
                                      <th>Title</th>
                                      <th>Description</th>
                                      <th>Price</th>
                                      <th>Quantity</th>
                                      <th>Price x Qty</th>
                                      <!-- <th>Date</th>
                                      <th>Location</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $additionalPrice = 0; ?>
                                    @if(sizeof($additional_price)>0)
                                        @foreach($additional_price as $pResponse)
                                            <?php 
                                              $serial = $loop->index + 1; 
                                              $roleName = '';
                                            ?>
                                             @if(isset($pResponse->id))
                                            <tr class="tr-row additiona-charges-row">
                                                <td>{{ $serial }}</td>
                                                <td><strong class="visible-xs">Title</strong> {{AdditionalCharge($pResponse->id)}}</td>
                                                <td><strong class="visible-xs">Description</strong>{{ AdditionalCharge($pResponse->id,1) }}</td>
                                                <td><strong class="visible-xs">Price</strong>@if(isset($pResponse->price)) ($){{$pResponse->price }} @else No Price Set. @endif</td>
                                                <td><strong class="visible-xs">Quantity</strong>{{$pResponse->qty}}</td>
                                                <td><strong class="visible-xs">Price</strong>@if(isset($pResponse->price)) <div class="priceinqty"><?php $additionalPrice = $additionalPrice + ($pResponse->price*$pResponse->qty) ?>($){{ $pResponse->price*$pResponse->qty}}</div> @else No Price Set. @endif</td>
                                            </tr>
                                            @endif
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                       </div>
                       <div class="Totals row">
                           <div class="col-sm-12">
                            <div class="col-sm-5 pull-right">
                              <div class="col-sm-5 border-bottom" style="float:left"><b> Additional Pricing: </b></div>
                              <div class="col-sm-7 border-bottom">($) {{$additionalPrice}}</div>
                            </div>
                           </div>
                           <div class="col-sm-12">
                             <div class="col-sm-5 pull-right">
                              <div class="col-sm-5 border-bottom" style="clear:both;float:left"><b> (Assets+Additonal)-Prize: </b></div>
                              <div class="col-sm-7 border-bottom">($) ({{$assetTotal}} + {{$additionalPrice}}) - {{$prizeWon}} = {{$total = ($additionalPrice+$assetTotal)-$prizeWon }}</div>
                              
                              <div class="col-sm-5 border-bottom" style="clear:both;float:left"><b> Total: </b></div>
                              <div class="col-sm-7 border-bottom">($) {{$total}} <input type="hidden" class="totalPrice" name="total_price" value="{{$total}}"></div>

                              </div>
                           </div>
                           
                       </div>

                  </div>



                  {{--{!! Form::close() !!}--}}

                </div>
            @endif
          </div>
        </div>
        <!-- Tab containing all the data tables -->
</div>
</div>
</body>
</html>