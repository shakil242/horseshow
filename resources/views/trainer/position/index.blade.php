@extends('layouts.equetica2')

@section('custom-htmlheader')
    <!-- Search populate select multiple-->
    <script src="{{ asset('/js/vender/bootstrap3-typeahead.min.js') }}"></script>
    <!-- END:Search populate select multiple-->
@endsection

@section('main-content')
    <div class="row">
        <div class="col-sm-8">
            <h1> Placing and Pricing </h1>
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
            {!! Breadcrumbs::render('master-template-additional-charges',0) !!}
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
    <div class="row">
        <div class="charges-form col-sm-6">
        {!! Form::open(['url'=>'position/store','method'=>'post',"class"=>'additiona-charge-form']) !!}
        <input name="asset_id" type="hidden" value="{{$asset_id}}" >
        <input name="prizing_id" type="hidden" value="{{$prizing_id}}" >
        
        <div id="input-hidden-for-edit"></div>
        <div class="row">
            <div class="col-sm-5">
                <h2>Placing</h2>
            </div>
            <div class="col-sm-7">
                <h2>Price ($) <small><i>(Add wining position prize money)</i></small></h2>
            </div>
        </div>
        <div class="position-listing">
            @if(isset($positions))
            <?php $index = 0 ?>
            @foreach($positions as $PS)
            <?php $index++; ?>
            <div class="duplicator">
                <div class="row">
                <div class="col-sm-5" style="text-align: center;">
                    <label>{{$PS->position}}</label>
                </div>
                <div class="col-sm-7">
                   <div class="col-sm-2" style="padding-top: 5px;">($)</div> <div class="col-sm-10"><input name="placingprice[{{$PS->position}}][price]" type="number" class="form-control" placeholder="Add Amount in $" min="0" max="1000" value="{{$PS->price}}" ></div>
                </div>
                <input name="placingprice[{{$PS->position}}][position]" type="hidden" value="{{$PS->position}}" >
                    @if($index > 3)
                        <a href="#" class="delete-position">x</a>
                    @endif
                </div>

            </div>
            
            @endforeach
        @else
        <?php $indexr = 4; $iloop = 1; ?>
            @while($iloop < $indexr)
            <div class="duplicator">
                <div class="row">
                    <div class="col-sm-5" style="text-align: center;">
                        <label>{{$iloop}}</label>
                    </div>
                    <div class="col-sm-7">
                       <div class="col-sm-2" style="padding-top: 5px;">($)</div> <div class="col-sm-10"><input name="placingprice[{{$iloop}}][price]" type="number" class="form-control" placeholder="Add Amount in $" min="0" max="1000" value="0" ></div>
                    </div>
                <input name="placingprice[{{$iloop}}][position]" type="hidden" value="{{$iloop}}" >
                </div>
            </div>
            <?php $iloop = $iloop+1; ?>
            @endwhile
            
        @endif
        
        </div>
        <div class="row">
            <div class="col-sm-4">{!! Form::submit("Update" , ['class' =>"btn btn-lg btn-success",'id'=>'storeonly']) !!} </div>
            <div class="col-sm-4"><input type="button" class="btn btn-lg btn-success add-more" value="Add more"> </div>
        </div>
        
        {!! Form::close() !!}
    </div>
    <div class="col-sm-6">
        <h3>Define Show's Class Type:</h3>
        <br>
        @if(count($ownerShows)>0)
        @foreach($ownerShows as $shows)
          <div class="row">
            <div class="col-sm-12"> <h3>{{$shows->title}}</h3></div>
              <div class="col-sm-4"><label><span>Class Type:</span></label></div>
              <div class="col-sm-8">
                  <select name="show_type_class" style="width: 75%" class="selectpicker show-tick form-control"  data-size="1" data-selected-text-format="count>6"   data-live-search="true">
                       @if(count($classType)>0)
                           @foreach($classType as $asset)
                               <option value="{{$asset->id}}" {{($asset->id == 0)? 'selected':''}}> {{$asset->name}}</option>
                           @endforeach
                       @endif
                   </select>
               </div>
          </div>
          @endforeach
          @endif
          
    </div>
</div>
    
    <!-- Tab containing all the data tables -->


@endsection

@section('footer-scripts')
    <script src="{{ asset('/js/shows/add-positions.js') }}"></script>
    @include('layouts.partials.datatable')
@endsection
