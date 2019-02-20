@extends('admin.layouts.app')

@section('custom-htmlheader')
    <!-- Search populate select multiple-->
    <script src="{{ asset('/js/vender/bootstrap3-typeahead.min.js') }}"></script>
    <!-- END:Search populate select multiple-->
@endsection

@section('main-content')
    <div class="row">
        <div class="col-sm-8">
            <h1> Placing and Points </h1>
        </div>
        <div class="col-sm-4 action-holder">
            <form action="#">
                <div class="search-form">
                    <input class="form-control input-sm" placeholder="Search By Name" id="myInputTextField" type="search">
                    <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            {!! Breadcrumbs::render('points-dashboard-points-positions') !!}
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="back"><a href="#" class="back-to-all-modules" onclick="history.go(-1);"><span class="glyphicon glyphicon-circle-arrow-left"></span> Back</a></div>
        </div>
    </div>
     <div class="row">
        <div class="info">

            @if(Session::has('message'))
                <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
            @endif
        </div>
    </div>
    <div class="charges-form">
        {!! Form::open(['url'=>'admin/save/class/position-points','method'=>'post',"class"=>'additiona-charge-form']) !!}
        <input name="HCT_id" type="hidden" value="{{$HCT_id}}" >
        <input name="prizing_id" type="hidden" value="{{$prizing_id}}" >
        
        <div id="input-hidden-for-edit"></div>
        <div class="row">
            <div class="col-sm-2">
                <h2>Placing</h2>
            </div>
            <div class="col-sm-4">
                <h2>Points <small><i>(Add wining position points)</i></small></h2>
            </div>
        </div>
        <div class="position-listing">
            @if(isset($positions))
            <?php $index = 0; ?>
            @foreach($positions as $PS)
            <?php $index++; ?>
                @if($PS->position == "last")
                <div class="duplicators">
                    <div class="row">
                    <div class="col-sm-2" style="text-align: center;">
                        <label> None Placement</label>
                    </div>
                    <div class="col-sm-3">
                       <div class="col-sm-2" style="padding-top: 5px;"></div> <div class="col-sm-10"><input name="placingprice[others][price]" type="number" class="form-control" placeholder="Add Points" min="0" step="0.5" max="1000" value="{{$PS->price}}" ></div>
                    </div>
                    <input name="placingprice[others][position]" type="hidden" value="{{$PS->position}}" >
                    </div>

                </div>
                @else
                <div class="duplicator">
                    <div class="row">
                    <div class="col-sm-2" style="text-align: center;">
                        <label>{{$PS->position}}</label>
                    </div>
                    <div class="col-sm-3">
                       <div class="col-sm-2" style="padding-top: 5px;"></div> <div class="col-sm-10"><input name="placingprice[{{$PS->position}}][price]" type="number" class="form-control" placeholder="Add Points" min="0" step="0.5" max="1000" value="{{$PS->price}}" ></div>
                    </div>
                    <input name="placingprice[{{$PS->position}}][position]" type="hidden" value="{{$PS->position}}" >
                        @if($index > 3)
                            <a href="#" class="delete-position">x</a>
                        @endif
                    </div>

                </div>
                @endif
            
            
            @endforeach
        @else
        <?php $indexr = 4; $iloop = 1; ?>
            @while($iloop < $indexr)
            <div class="duplicator">
                <div class="row">
                    <div class="col-sm-2" style="text-align: center;">
                        <label>{{$iloop}}</label>
                    </div>
                    <div class="col-sm-3">
                       <div class="col-sm-2" style="padding-top: 5px;"></div> <div class="col-sm-10"><input name="placingprice[{{$iloop}}][price]" type="number" class="form-control" placeholder="Add Points" min="0" max="1000" value="0" ></div>
                    </div>
                <input name="placingprice[{{$iloop}}][position]" type="hidden" value="{{$iloop}}" >
                </div>
            </div>
            <?php $iloop = $iloop+1; ?>
            @endwhile
            
            
        @endif
        
        </div>
        @if(!isset($positions))
        <div class="duplicators">
                <div class="row">
                    <div class="col-sm-2" style="text-align: center;">
                        <label>No Placing</label>
                    </div>
                    <div class="col-sm-3">
                       <div class="col-sm-2" style="padding-top: 5px;"></div> <div class="col-sm-10"><input name="placingprice[others][price]" type="number" class="form-control" placeholder="Add Points" min="0" max="1000" value="0" ></div>
                    </div>
                <input name="placingprice[others][position]" type="hidden" value="last" >
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col-sm-2">{!! Form::submit("Update" , ['class' =>"btn btn-lg btn-success",'id'=>'storeonly']) !!} </div>
            <div class="col-sm-2"><input type="button" class="btn btn-lg btn-success add-more" value="Add more"> </div>
        </div>
        
        {!! Form::close() !!}
    </div>
    <!-- Tab containing all the data tables -->


@endsection

@section('footer-scripts')
    <script src="{{ asset('/adminstyle/js/shows/add-positions.js') }}"></script>
    @include('layouts.partials.datatable')
@endsection
