@extends('layouts.equetica2')
@section('main-content')

    <div class="container-fluid">

        @php
            $title ='Class Price';
            $added_subtitle = '';
        @endphp
        @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle,'remove_search'=>1])

        <div class="white-board">
            <div class="col-md-12">
                <div class="row">
                    <div class="info text-center">
                        @if(Session::has('message'))
                            <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                        @endif
                    </div>
                </div>
            </div>



                {!! Form::open(['url'=>'master-template/add/class-price','method'=>'post','files'=>true,'class'=>'']) !!}
                <input type="hidden" name="division" value="0">

         <div class="table-responsive  rr-datatable indivisual-fixed-y">
             <table id="crudTable2" class="table  primary-table mt-10 custom-responsive-md">
                  <thead class="hidden-xs">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Class</th>
                        <th scope="col">Price ($)</th>
                        @if($Show->show_type == 'Western')
                        <th scope="col">Judges Fee ($)</th>
                        @endif
                        <th class="action"></th>

                    </tr>
                    </thead>
                    <tbody>
                    @if(sizeof($collection)>0)
                        @foreach($collection as $pResponse)
                            @php
                            $serial = $loop->index + 1;
                            $roleName = '';
                           $isCombined = checkCombined($pResponse->asset_id);
                           @endphp
                            @if($isCombined==0)
                            <tr>
                                <td>{{ $serial }}</td>
                                <td><span class="table-title">Class</span>{{ getAssetNameFromId($pResponse->asset_id) }}
                                <input type="hidden" class="form-control" name="asset[{{ $serial }}][id]" value="{{$pResponse->asset_id}}"></td>
                                <td><span class="table-title">Price</span>
                                @if(isset($answer))
                                    @php                  
                                        $key = array_search($pResponse->asset_id, array_column($answer, 'class_id'));
                                        if($key || $key===0){
                                            $vals = $answer[$key]['price'];
                                        }else{
                                            $vals = "";
                                        }
                                    @endphp
                                    <input type="number" class="form-control" name="asset[{{ $serial }}][price]" value="{{$vals}}">
                                @else
                                    <input type="number" class="form-control" name="asset[{{ $serial }}][price]" value="">
                                @endif
                                </td>
                                @if($Show->show_type == 'Western')
                                <td>
                                    @if(isset($answer[$key]['price_judges']) && $answer[$key]['price_judges'] != 0)
                                        <input type="number" class="form-control" name="asset[{{ $serial }}][price_judges]" value="{{$answer[$key]['price_judges']}}">
                                    @else
                                        <input type="number" class="form-control" name="asset[{{ $serial }}][price_judges]" value="">
                                    @endif
                                </td>
                                @endif
                                <td></td>
                                

                            </tr>
                            @endif
                        @endforeach

                    @endif

                    </tbody>
                </table>
                
            </div>
            <div class="col-md-12 mt-20 text-right">

                <input type="hidden" name="show_id" value="{{$show_id}}">
            {!! Form::submit("Submit Prices" , ['class' =>"btn btn-primary btn-share"]) !!}
            </div>

            {!! Form::close() !!}
   @if(GetTemplateType($Show->template_id) != CONST_TRAINERS)
    <div class="row mt-20">
        <div class="col-sm-12 text-center mb-20">
        <h1 class="text-center"> Divisions Price </h1>
            <span class="col-md-12 text-center noteText">Note: Division price is the cost for all classes within that specific division. Class price is the cost of the individual class.</span>
        </div>
    </div>

            {!! Form::open(['url'=>'master-template/add/class-price','method'=>'post','files'=>true,'class'=>'']) !!}
                <input type="hidden" name="division" value="1">
                <div class="table-responsive  rr-datatable indivisual-fixed-y">
                     <table id="crudTable2" class="table  primary-table mt-10 custom-responsive-md">
                          <thead class="hidden-xs">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Class</th>
                                <th scope="col">Price ($)</th>
                                <th class="action"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(sizeof($divisions)>0)
                                @foreach($divisions as $pResponse)
                                    <?php
                                  $serial = $loop->index + 1;
                                    $roleName = '';
                                    ?>
                                    <tr>
                                        <td>{{ $serial }}</td>
                                        <td><span class="table-title">Class</span>{{ getAssetNameFromId($pResponse->division_id) }}
                                        <input type="hidden" class="form-control" name="asset[{{ $serial }}][id]" value="{{$pResponse->division_id}}"></td>
                                        <td><span class="table-title">Price</span>
                                        @if(isset($answer))
                                            @php                  
                                                $key = array_search($pResponse->division_id, array_column($answer, 'class_id'));
                                                if($key || $key===0){
                                                    $vals = $answer[$key]['price'];
                                                }else{
                                                    $vals = "";
                                                }
                                            @endphp
                                            <input type="number" class="form-control" name="asset[{{ $serial }}][price]" value="{{$vals}}">
                                        @else
                                            <input type="number" class="form-control" name="asset[{{ $serial }}][price]" value="">
                                        @endif
                                        </td>
                                        <td></td>
                                        

                                    </tr>
                                @endforeach

                            @endif

                            </tbody>
                        </table>
                </div>
            {!! Form::submit("Submit Prices" , ['class' =>"btn btn-primary btn-share mb-20"]) !!}
            <div class="col-md-12 mt-20 text-right">
            <input type="hidden" name="show_id" value="{{$show_id}}"> 
            </div>
            {!! Form::close() !!}

    @endif
    <!-- Tab containing all the data tables -->


@endsection

@section('footer-scripts')

<style>

    .jcf-unselectable
    {
        display: none!important;

    }

</style>

    <script src="<?php echo e(asset('/js/vender/jquery-ui.min.js')); ?>"></script>
    <link rel="stylesheet" href="<?php echo e(asset('/css/vender/jquery-ui.css')); ?>" />
@endsection
