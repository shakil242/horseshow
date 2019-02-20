@extends('layouts.equetica2')

@section('custom-htmlheader')
    <!-- Search populate select multiple-->
    <script src="{{ asset('/js/vender/bootstrap3-typeahead.min.js') }}"></script>
    <!-- END:Search populate select multiple-->
@endsection

@section('main-content')
    <div class="row">
        <div class="col-sm-8">
            <h1>All Penalty Options</h1>
        </div>
        <div class="col-sm-4 action-holder">
            <form action="#">
                <div class="search-form">
                    <input type="text" placeholder="Search By Name" id="myInputTextField"/>
                    <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-8">
           {!! Breadcrumbs::render('shows-scratch-option') !!}
        </div>
    </div>

    <div class="row">
        <div class="info">
            @if(Session::has('message'))
                <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
            @endif
        </div>
    </div>

    <!--- template list -->
<div class="row">
    <div class="col-sm-6">
        <div class="existing-scratch-penalty">
        <div class="row">
            <div class="col-sm-12"><h3>Scratch Penalty</h3></div>
            <form method="post" action="{{URL::to('shows') }}/save/scratch"
                  name="PaypalDetails">
                <div class="form-scratch" style="margin-top: 40px;">
                    {{csrf_field()}}
                    <div class="col-sm-12">
                            <div class="form-scratch-penality">
                                <div class="row form-group">
                                    <div class="col-sm-4"><label for="penality">Penalty:</label></div>
                                    <div class="col-sm-8"><input type="number" class="form-control" id="penalty" required name="penality"></div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-sm-12">
                                    @if($ClasseScratch != null)
                                    <select class="selectpickers form-control" name="scratch_classes[]" data-live-search="true" multiple data-min-options="1" required> 
                                        @foreach($ClasseScratch as $class)
                                            @if(isset($pos_answers))
                                                <option value="{{$class->id}}" {{getSelectedValuesMultiple($pos_answers,$post->position,$user->GetUserObj->id)}} >{{$user->GetUserObj->name.' '.getUserHorseNamefromid($user->GetUserObj->id,$assetId,$showId,'master')}}</option>
                                            @else
                                                <option value="{{$class->id}}">{{GetAssetName($class)}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                     @endif
                                     </div>
                                </div>
                                <div class="row form-group" >
                                    <div class="col-sm-4"><span>Date From :</span></div>
                                    <div class="col-sm-8"><input type="text"  name="date_from" required="" class="form-control datetimepickerDate"></div>
                                    
                                </div>
                                <div class="row form-group" >
                                    <div class="col-sm-4"><span>Date To :</span></div>
                                    <div class="col-sm-8"><input type="text"  name="date_to" required="" class="form-control datetimepickerDate"></div>
                                </div>
                        </div>
                        <input type="hidden" name="template_id" value="{{$template_id}}">
                        <input type="hidden" id="type" name="type" value="{{SCROPT_SCRATCH_PENALITY}}">
                        <div class="col-sm-12 center">
                            <div class="col-md-4">
                                <input type="submit" name="submit" class="btn btn-lg  btn-success btn-close" value="Submit">
                            </div>
                        </div>
                    </div>

                </div>
            </form>

        </div>
        </div>

    <div class="row" style="margin-top: 40px;"></div>
    <div class="module-holer rr-datatable">
        <div class="col-sm-12"><h3>Scratch Penalty</h3></div>
        <table id="crudTable2" class="table primary-table">
            <thead class="hidden-xs">
            <tr>
                <th style="width:5%">#</th>
                <th>Penalty</th>
                <th>Classes</th>
                <th>From Date</th>
                <th>To Date</th>
                <th>Action</th>

                <!-- <th style="width:22%">Type</th> -->
            </tr>
            </thead>
            <tbody>

            @if(sizeof($collection)>0)
                <?php $serial = 0; ?>
                @foreach($collection as $entry)
                    <?php $serial = $serial+1;?>
                    @if( $entry->type == SCROPT_SCRATCH_PENALITY)

                    <tr>
                        <td>{{ $serial }}</td>
                        <td><strong class="visible-xs">Penalty</strong>{{ $entry->penality }}</td>
                        <td><strong class="visible-xs">Classes</strong>{{GetAssetNamefromId($entry->asset_id)}}</td>
                        <td><strong class="visible-xs">From Date</strong>{{  $entry->date_from }}</td>
                        <td><strong class="visible-xs">To Date</strong>{{  $entry->date_to }}</td>
                            <td><strong class="visible-xs">Action</strong>
                            <div class="TD-left">
                                <a onclick="return confirm('Are you sure?')" href="{{URL::to('shows') }}/{{nxb_encode($entry->id)}}/delete/scratch/" data-original-title="Delete This rule" data-placement="top" data-toggle="tooltip">
                                    <i style="font-size: 20px;" aria-hidden="true" class="fa fa-trash-o"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endif
                @endforeach
            @else

                <tr><td colspan="5" style="text-align: center">No Penality Exist</td></tr>

            @endif
            </tbody>
        </table>
    </div>

    </div>

    <div class="col-sm-6">
        <div class="existing-scratch-penalty">
            <div class="row">
             <div class="col-sm-12"><h3>Joining Date Penalty</h3></div>
                <form method="post" action="{{URL::to('shows') }}/save/scratch"
                      name="PaypalDetails">
                    <div class="form-joining" style="margin-top: 40px;">
                    {{csrf_field()}}
                    <div class="col-sm-12">
                            <div class="form-scratch-penality">
                                <div class="row form-group">
                                    <div class="col-sm-4"><label for="penality">Penalty:</label></div>
                                    <div class="col-sm-8"><input type="number" class="form-control" id="penalty" required name="penality"></div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-sm-12">
                                    @if($ClasseJoining != null)
                                    <select class="selectpickers form-control" name="scratch_classes[]" data-live-search="true" multiple data-min-options="1" required> 
                                        @foreach($ClasseJoining as $class)
                                            @if(isset($pos_answers))
                                                <option value="{{$class->id}}" {{getSelectedValuesMultiple($pos_answers,$post->position,$user->GetUserObj->id)}} >{{$user->GetUserObj->name.' '.getUserHorseNamefromid($user->GetUserObj->id,$assetId,$showId,'master')}}</option>
                                            @else
                                                <option value="{{$class->id}}">{{GetAssetName($class)}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                     @endif
                                     </div>
                                </div>
                                <div class="row form-group" >
                                    <div class="col-sm-4"><span>Date From :</span></div>
                                    <div class="col-sm-8"><input type="text"  name="date_from" required="" class="form-control datetimepickerDate"></div>
                                    
                                </div>
                                <div class="row form-group" >
                                    <div class="col-sm-4"><span>Date To :</span></div>
                                    <div class="col-sm-8"><input type="text"  name="date_to" required="" class="form-control datetimepickerDate"></div>
                                </div>
                        </div>
                        <input type="hidden" name="template_id" value="{{$template_id}}">
                        <!-- class joining panelty = 2 -->
                        <input type="hidden" id="type" name="type" value="{{SCROPT_CLASS_JOINING_PENALITY}}">
                        <div class="col-sm-12 center">
                            <div class="col-md-4">
                                <input type="submit" name="submit" class="btn btn-lg  btn-success btn-close" value="Submit">
                            </div>
                        </div>
                    </div>

                </div>
                </form>

            </div>
        </div>
        <div class="row" style="margin-top: 40px;"></div>
    <div class="module-holer rr-datatable">
        <div class="col-sm-12"><h3>Joining Date Penalty</h3></div>
        <table id="crudTable2" class="table primary-table">
            <thead class="hidden-xs">
            <tr>
                <th style="width:5%">#</th>
                <th>Penalty</th>
                <th>Classes</th>
                <th>From Date</th>
                <th>To Date</th>
                <th>Action</th>

                <!-- <th style="width:22%">Type</th> -->
            </tr>
            </thead>
            <tbody>

            @if(sizeof($collection)>0)
                 <?php $serial = 0; ?>
                @foreach($collection as $entry)
                    @if( $entry->type == SCROPT_CLASS_JOINING_PENALITY)
                     <?php $serial = $serial+1;?>
                    <tr>
                        <td>{{ $serial }}</td>
                        <td><strong class="visible-xs">Penalty</strong>{{ $entry->penality }}</td>
                        <td><strong class="visible-xs">Classes</strong>{{GetAssetNamefromId($entry->asset_id)}}</td>
                        <td><strong class="visible-xs">From Date</strong>{{  $entry->date_from }}</td>
                        <td><strong class="visible-xs">To Date</strong>{{  $entry->date_to }}</td>
                            <td><strong class="visible-xs">Action</strong>
                            <div class="TD-left">
                                <a onclick="return confirm('Are you sure?')" href="{{URL::to('shows') }}/{{nxb_encode($entry->id)}}/delete/scratch/" data-original-title="Delete This rule" data-placement="top" data-toggle="tooltip">
                                    <i style="font-size: 20px;" aria-hidden="true" class="fa fa-trash-o"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endif
                @endforeach
            @else

                <tr><td colspan="5" style="text-align: center">No Penality Exist</td></tr>

            @endif
            </tbody>
        </table>
    </div>
    </div>
</div>


@endsection
@section('footer-scripts')

    <script type="text/javascript" src="{{ asset('/js/vender/moment.js') }}"></script>
  <script type="text/javascript" src="{{ asset('/js/vender/transition.js') }}"></script>
  <script type="text/javascript" src="{{ asset('/js/vender/collapse.js') }}"></script>
  <script type="text/javascript" src="{{ asset('/js/vender/bootstrap-datetimepicker.min.js') }}"></script>
  
  <script type="text/javascript" src="{{asset('/js/shows/add-scratch.js')}}"></script>
    @include('layouts.partials.datatable')
@endsection