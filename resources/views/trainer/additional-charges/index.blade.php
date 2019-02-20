@extends('layouts.equetica2')

@section('custom-htmlheader')
    <!-- Search populate select multiple-->
    <script src="{{ asset('/js/vender/bootstrap3-typeahead.min.js') }}"></script>
    <!-- END:Search populate select multiple-->
@endsection

@section('main-content')
    <div class="row">
        <div class="col-sm-8">
            <h1>  Additional Charges </h1>
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
        <div class="col-sm-12">
            <div class="col-sm-6 pull-right"><input type="button" name="" class="btn btn-success btn-lg addNewCharges pull-right" value="Add Charges"> </div>
        </div>
    </div>
    <div class="charges-hidden-form">
        {!! Form::open(['url'=>'shows/additional-charges/store','method'=>'post',"class"=>'additiona-charge-form']) !!}
        <div id="input-hidden-for-edit"></div>
        <div class="row">
            <div class="col-sm-2">
                {!! Form::text('title', null , ['class' =>"form-control",'placeholder'=>"Add Title",'required'=>"required"]) !!}
            </div>
            <div class="col-sm-3">
                {!! Form::textarea('description', null , ['class' =>"form-control",'placeholder'=>"Add the description",'rows'=>"3"]) !!}
            </div>
            <div class="col-sm-3">
                <div class="col-sm-10">{!! Form::number('amount', null , ['class' =>"form-control",'placeholder'=>"Add Amount in $",'required'=>"required",'step'=>'any']) !!}</div>
                <div class="col-sm-2">$</div> 
            </div>
            <div class="col-sm-2">
                {!! Form::checkbox('required', '1', true); !!} Required to pay
            </div>
            <div class="col-sm-2">{!! Form::submit("Save" , ['class' =>"btn btn-lg btn-success",'id'=>'storeonly']) !!} </div>
            <input type="hidden" name="app_id" value="{{$app_id}}">
            <input type="hidden" name="template_id" value="{{$template_id}}">
        </div>
        {!! Form::close() !!}
    </div>



    <div class="row"  style="margin-left:0px;margin-right:0px">
        {{ getAlert() }}
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

                    <div class="tab-content">
                    <div id="Transffered" class="tab-pane fade in active">

                        <div class="module-holer rr-datatable">

                            <table id="crudTable2" class="table primary-table">

                                <thead class="hidden-xs">
                                <tr>
                                    <th style="width:8%">#</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Amount</th>
                                    <th>Required</th>
                                    <th style="width:19%" >Actions</th>
                                </tr>
                                </thead>
                                <tbody>

                                @if(sizeof($additional_charges)>0)
                                    @foreach($additional_charges as $pResponse)
                                        <?php $serial = $loop->index + 1; ?>
                                        <tr>
                                            <td>
                                                {{ $serial }}
                                            </td>
                                            <td><strong class="visible-xs">Title</strong>{{$pResponse->title}} <input class="list-title" type="hidden" value="{{$pResponse->title}}"></td>

                                            <td><strong class="visible-xs">Description</strong>{{$pResponse->description}}<input class="list-description" type="hidden" value="{{$pResponse->description}}"></td>

                                            <td><strong class="visible-xs">Amount</strong>{{$pResponse->amount}}<input class="list-amount" type="hidden" value="{{$pResponse->amount}}"></td>
                                            
                                            <td><strong class="visible-xs">Required</strong>@if($pResponse->required == 1) Required @else Not Required @endif<input class="list-required" type="hidden" value="{{$pResponse->required}}"></td>

                                            <td class="pull-left" style="width: 100%">
                                                <strong class="visible-xs">Actions</strong>
                                                <a href="{{URL::to('shows/additional-charges/delete') }}/{{ nxb_encode($pResponse->id) }}" onclick="return confirm('Are you sure you want to delete?')">Delete</a>
                                                <a href="#" class="edit-additional-charges" data-attr="{{$pResponse->id}}">Edit</a>
                                                    
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    </div>
            {{--@endif--}}
        </div>
    </div>
    <!-- Tab containing all the data tables -->


@endsection

@section('footer-scripts')


    <script src="{{ asset('/js/ajax-dynamic-assets-table.js') }}"></script>
    <script src="{{ asset('/js/shows/restriction.js') }}"></script>

    @include('layouts.partials.datatable')
@endsection
