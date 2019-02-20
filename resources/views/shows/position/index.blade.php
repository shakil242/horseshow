@extends('layouts.equetica2')

@section('custom-htmlheader')
    <!-- Search populate select multiple-->
    <script src="{{ asset('/js/vender/bootstrap3-typeahead.min.js') }}"></script>
    <!-- END:Search populate select multiple-->
@endsection

@section('main-content')
<?php 
$total_amount = null;
$prize_percentage = null;
$total_prize = null;
$prize_money  = null;
$add_back_amt = null;
$total_add_back_entry = null;
$total_add_back_amt = null;
?>
@if(Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
@endif
     <!-- ================= CONTENT AREA ================== -->
<div class="main-contents">
    <div class="container-fluid">
        @php 
          $title = "Prize Money";
          $added_subtitle =Breadcrumbs::render('master-template-assets-positions',$template_id);
        @endphp
        @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle,'remove_search'=>1])

        <!-- Content Panel -->
        <div class="white-board">  
          <div class="row">
                <!-- <div class="col-md-12">
                    <div class="back"><a onclick="history.go(-1);" class="back-to-all-modules" href="#"><span class="glyphicon glyphicon-circle-arrow-left"></span> Back</a></div>
                </div> -->
            </div>
            
            <div class="row">
                    <div class="col-md-12">
                            
                        <!-- TAB CONTENT -->
                        <div class="tab-content" id="myTabContent">
                            <!-- Tab Data Divisions -->
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="division-tab">
                              @include('admin.layouts.errors')
                                
                                 <div class="row box-shadow bg-white p-4 mb-30">
                                    <div class="charges-form col-sm-7">
                                    {!! Form::open(['url'=>'position/store','method'=>'post',"class"=>'additiona-charge-form']) !!}
                                    <input name="asset_id" type="hidden" value="{{$asset_id}}" >
                                    <input name="prizing_id" type="hidden" value="{{$prizing_id}}" >
                                    
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="row mb-5">
                                                <div class="col-sm-6">
                                                    <label>Total Amount From Entries in ($):</label>
                                                </div>
                                                <?php
                                                    if(isset($positions->total_amount)){
                                                        $total_amount = $positions->total_amount;
                                                        $prize_percentage = $positions->prize_percentage;
                                                        $total_prize = $positions->total_prize;
                                                    }
                                                     if(isset($positions->prize_money)){
                                                        $prize_money  = $positions->prize_money;
                                                    }
                                                     if(isset($positions->add_back_amt)){
                                                        $add_back_amt = $positions->add_back_amt;
                                                    }
                                                     if(isset($positions->total_add_back_entry)){
                                                        $total_add_back_entry = $positions->total_add_back_entry;
                                                    }
                                                    if(isset($positions->total_add_back_amt)){
                                                        $total_add_back_amt = $positions->total_add_back_amt;
                                                    }
                                                 ?>
                                                <div class="col-sm-6">
                                                     <input name="placingprice[total_amount]" class="form-control NumaricRistriction total-amount" type="number" placeholder="Enter Total Amount" value="{{$total_amount}}">
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <div class="col-sm-6">
                                                    <label>Prize Percentage (%):</label>
                                                </div>
                                                <div class="col-sm-6">
                                                     <input name="placingprice[prize_percentage]" class="form-control NumaricRistriction percentage-check percentage-prize" max="100" type="number" placeholder="Enter Prize Percentage" value="{{$prize_percentage}}" >
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <div class="col-sm-6">
                                                    <label>Prize Money($):</label>
                                                </div>
                                                <div class="col-sm-6">
                                                     <input name="placingprice[prize_money]" class="form-control NumaricRistriction prize-money" step="any" type="number" value="{{$prize_money}}">
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <div class="col-sm-6">
                                                    <label>Add Back Amount($):</label>
                                                </div>
                                                <div class="col-sm-6">
                                                     <input name="placingprice[add_back_amt]" class="form-control NumaricRistriction add-back-amount" step="any" type="number" value="{{$add_back_amt}}">
                                                </div>
                                            </div>
                                             <div class="row mb-5">
                                                <div class="col-sm-6">
                                                    <label>Total Add Back Entries:</label>
                                                </div>
                                                <div class="col-sm-2">
                                                     <input name="placingprice[total_add_back_entry]" class="form-control NumaricRistriction payback-entries" type="number" placeholder="# of Entries" value="{{$total_add_back_entry}}">
                                                </div>
                                                <div class="col-sm-4">
                                                     <input name="placingprice[total_add_back_amt]" class="form-control NumaricRistriction payback-total-money" step="any" type="number" placehoder="Total of entries" value="{{$total_add_back_amt}}">
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <div class="col-sm-6">
                                                    <label>Total Prize ($):</label>
                                                </div>
                                                <div class="col-sm-6">
                                                     <input name="placingprice[total_prize]" class="form-control NumaricRistriction total-prize" step="any" type="number" value="{{$total_prize}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="hr-dark hr-thik">
                                    <div id="input-hidden-for-edit"></div>
                                    <br>
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <h2>Placing</h2>
                                        </div>
                                        <div class="col-sm-7">
                                            <h2>Prize ($) <small><i>(Add wining position prize money)</i></small></h2>
                                        </div>
                                    </div>
                                    <div class="position-listing">
                                        @if(isset($positions->place))
                                        <?php $index = 0; ?>
                                        @foreach($positions->place as $PS)
                                        <?php $index++; ?>
                                        <div class="duplicator">
                                            <div class="row">
                                            <div class="col-sm-1" style="text-align: center;padding-top:5px;">
                                                <label>{{$PS->position}}</label>
                                            </div>
                                            <div class="col-sm-4 row">
                                               <div class="col-sm-2" style="padding: 5px;">(%)</div> <div class="col-sm-9"><input name="placingprice[place][{{$PS->position}}][percent]" type="number" class="form-control NumaricRistriction percentage-check position-percentage" placeholder="Add %" max="100" value="{{$PS->percent}}" ></div>
                                            </div>
                                            <div class="col-sm-4 row">
                                               <div class="col-sm-2" style="padding-top: 5px;">($)</div> <div class="col-sm-10"><input name="placingprice[place][{{$PS->position}}][price]" type="number" class="form-control NumaricRistriction position-price" placeholder="Add Amount in $" step="any" min="0" max="10000" value="{{$PS->price}}" ></div>
                                            </div>
                                            <input name="placingprice[place][{{$PS->position}}][position]" type="hidden" value="{{$PS->position}}" >
                                                @if($index == 3)
                                                    <div class="col-xs-1">
                                                        <button type="button" class="btn btn-default addButton add-more"><i class="fa fa-plus"></i></button>
                                                    </div>
                                                @endif
                                                @if($index > 3)
                                                <div class="col-xs-1">
                                                    <button type="button" class="btn btn-default removeButton delete-position"><i class="fa fa-minus"></i></button>
                                                </div>
                                                @endif
                                            </div>

                                        </div>
                                        
                                        @endforeach
                                    @else
                                    <?php $indexr = 4; $iloop = 1; ?>
                                        @while($iloop < $indexr)
                                        <div class="duplicator">
                                            <div class="row">
                                                <div class="col-sm-1" style="text-align: center; padding-top:5px;">
                                                    <label>{{$iloop}}</label>
                                                </div>
                                                 <div class="col-sm-4 row">
                                               <div class="col-sm-2" style="padding-top: 5px;">(%)</div> <div class="col-sm-9"><input name="placingprice[place][{{$iloop}}][percent]" type="number" class="form-control NumaricRistriction percentage-check position-percentage" placeholder="Add %" max="100"></div>
                                            </div>
                                            <div class="col-sm-4 row">
                                               <div class="col-sm-2" style="padding-top: 5px;">($)</div> <div class="col-sm-10"><input name="placingprice[place][{{$iloop}}][price]" type="number" class="form-control NumaricRistriction position-price" placeholder="Add Amount in $" min="0" step="any" max="10000"></div>
                                            </div>
                                                <input name="placingprice[place][{{$iloop}}][position]" type="hidden" value="{{$iloop}}" >
                                             @if($iloop == 3)
                                                    <div class="col-xs-1">
                                                        <button type="button" class="btn btn-default addButton add-more"><i class="fa fa-plus"></i></button>
                                                    </div>
                                                @endif
                                        </div>
                                    </div>
                                        <?php $iloop = $iloop+1; ?>
                                        @endwhile
                                        
                                    @endif
                                    
                                    </div>
                                    <div class="row text-center">
                                        <div class="col-sm-12 padding-25">
                                        {!! Form::submit("Update" , ['class' =>"btn btn-success",'id'=>'storeonly']) !!} </div>
                                         <!-- <div class="col-sm-4"><input type="button" class="btn btn-lg btn-success add-more" value="Add more"> </div> -->
                                    </div>

                                    
                                    {!! Form::close() !!}
                                </div>
                                
                            </div>
                                       
                                    
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
    <script src="{{ asset('/js/shows/add-positions.js') }}"></script>
    @include('layouts.partials.datatable')
@endsection
