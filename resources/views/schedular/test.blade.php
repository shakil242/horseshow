@extends('layouts.equetica2')
@section('custom-htmlheader')
    <!-- Search populate select multiple-->
    <link href="{{ asset('/css/vender/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('/css/vender/daterangepicker.css') }}" rel="stylesheet" />
    <script type="text/javascript" src="{{ asset('/js/vender/moment.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/vender/transition.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/vender/collapse.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/vender/bootstrap-datetimepicker.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/vender/daterangepicker.js') }}"></script>
    <link href="{{ asset('/css/addMoreCollapse.css') }}" rel="stylesheet" />
    <!-- END:Search populate select multiple-->
@endsection
@section('main-content')
    <div class="row">
        <div class="col-sm-7">
            <?php $ya_fields = getButtonLabelFromTemplateId($template_id,'ya_fields'); ?>
            <h1>{{post_value_or($ya_fields,'manage_scheduler','Schedular')}}</h1>

        </div>
        <div class="col-sm-4 action-holder">
            <div class="search-form">
                <input type="text" placeholder="Search By Name, Date, Location etc ..." id="mySearchTerm">
                <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
            </div>
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
        <div class="col-sm-8">
            {!! Breadcrumbs::render('master-template-breadcrumbs-list-schedular', $template_id) !!}
        </div>
    </div>

    <div class="row" style="margin-bottom: 10px;">
        <div class="col-sm-10">
            <h1>{{GetTemplateName($template_id)}}</h1>
        </div>

        <div class="col-sm-1" style="float: right; margin-right: 30px;">
            <button type="button" class="btn btn-success AddMoreSch">Add More</button>
        </div>

    </div>


    <div class="row" style="padding: 0px 15px;">

        <!-- Accordion START -->
        <div class="panel-group" id="accordion">
            @if($manageShows->count()>0)
                @foreach($manageShows as $show)
                    <div class="panel panel-default">
                        <div class="panel-heading accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" data-target="#{{$show->id}}">
                            @if($show->title != null) <h2>{{$show->title}}</h2>@else <h2>Show Title</h2> @endif
                        </div>
                        <div id="{{$show->id}}" class="defaultClasses panel-collapse collapse">
                            <div class="panel-body">
                                {!! Form::open(['url'=>'master-template/schedular/add/restriction/','method'=>'post','class'=>'form-horizontal']) !!}
                                <div class="col-sm-12">
                                    <div class="row" style="margin-top: 7px;">
                                        <div class="col-md-4">
                                            <label><span>Show Title :</span></label>
                                            <input @if($show->title != null) value="{{$show->title}}" @endif type="text" class="form-control c-control" required="required"  name="showTitle" />
                                        </div>
                                        <div class="col-md-4" >
                                            <label><span>Date From :</span></label>
                                            <input @if($show->date_from != null) value="{{$show->date_from}}" @endif type="text"  name="dateFrom" required="" class="form-control c-control datetimepicker8">
                                        </div>
                                        <div class="col-md-4" >
                                            <label><span>Date To :</span></label>
                                            <input @if($show->date_to != null) value="{{$show->date_to}}" @endif type="text"  name="dateTo" required="" class="form-control datetimepicker8 c-control">
                                        </div>

                                        <div class="col-md-12" style="margin-top: 12px;">
                                            <label><span>Location :</span></label>

                                            {{ Form::text('location', isset($show->location)? $show->location :'', ['id' => '',
                                            'placeholder'=>"Location *",'required'=>'required' ,'class' => 'form-control search-input-scheduler','style'=>'float: right; width: 87.5%;']) }}
                                        </div>

                                    </div>

                                    <div class="invite-participant-history">
                                        <?php $restriction = []; ?>
                                        @if($schedular_forms->count())
                                            @foreach($schedular_forms as $forms)

                                                <?php $res =  restrictedScheduledDates($forms->id);
                                                $answers = $res->first();
                                                //echo $answers->id.'>>>'.$forms->id.'>>>'.$forms->name.'>>>'.$answers->name;

                                                if($answers!='')
                                                    $restriction = $answers->SchedulerRestriction->toArray();

                                                $assets = checkSchedulerTime($template_id,$forms->id);
                                                //print_r($restriction);

                                                ?>

                                                <input type="hidden" name="template_id" value="{{$template_id}}">
                                                <input type="hidden" name="form_id[{{$forms->id}}]" value="{{$forms->id}}">
                                                <input type="hidden" name="show_id" @if($show->id != null) value="{{$show->id}}" @endif>


                                                @if($answers!='')
                                                    <input type="hidden" name="schedual_id[{{$forms->id}}]" value="{{$answers->id}}">

                                                    <div class="create-form">
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <h3>{{$forms->name}}</h3><br>


                                                                <div class="row fields">
                                                                    <div class="col-sm-6">
                                                                        <label><span>Schedular Name :</span><input type="text" class="form-control c-control"  @if($answers != null) value="{{$answers->name}}" @endif name="schedular_name[{{$forms->id}}]" /></label>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <label>Make Ristriction <input type="checkbox" class="ristriction-checkbox" name="schedular_checbox[{{$forms->id}}]" @if($answers != null && $answers->status == 1) checked="checked" @endif value="1" /></label>
                                                                    </div>

                                                                    <div class="col-sm-12">
                                                                        <div class="schedual-restrictions">

                                                                            @if($restriction!= null && isset($restriction))
                                                                                <div class="adding-restrictions-options">
                                                                                    @foreach($restriction as $row)
                                                                                        <?php $assetArr = explode(',',$row['asset_id']);?>

                                                                                        <div class="row" style=" margin-bottom: 15px;">
                                                                                            <div class="col-sm-5 ClassAssets">
                                                                                                <label style="padding-top:5px"><span>Select Class:</span></label>
                                                                                                <select multiple name="datetimeschedual['asset'][{{$forms->id}}][]" style="width: 75%" class="selectpicker show-tick form-control" multiple data-size="8" data-selected-text-format="count>6"  id="allAssets" data-live-search="true">
                                                                                                    @if(count($assets)>0)
                                                                                                        <option value="All">Select All</option>
                                                                                                        @foreach($assets as $asset)
                                                                                                            <option value="{{$asset['id']}}" {{ (collect(old('datetimeschedual["asset"]['.$forms->id.']',$assetArr))->contains($asset['id'])) ? 'selected':'' }} > {{$asset['name']}}</option>
                                                                                                        @endforeach
                                                                                                    @endif
                                                                                                </select>

                                                                                            </div>

                                                                                            <div class="col-sm-6">
                                                                                                <label><span>Select Date and Time</span>

                                                                                                    <input type="text" class="daterange form-control datetime-control"
                                                                                                           placeholder="Add Date" name="datetimeschedual['restriction'][{{$forms->id}}][]"
                                                                                                           value="{{$row['restriction']}}" /></label>
                                                                                            </div>
                                                                                            <div class="col-sm-1"><a href="javascript:void(0)" class="deleterowschedual"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></div>
                                                                                        </div>
                                                                                    @endforeach
                                                                                </div>
                                                                            @else
                                                                                <div class="adding-restrictions-options">
                                                                                    <div class="row">
                                                                                        <div class="col-sm-5 ClassAssets">
                                                                                            <label style="padding-top:5px"><span>Select Class:</span></label>
                                                                                            <select multiple name="asset[{{$forms->id}}][]" style="width: 75%" class="selectpicker show-tick form-control" multiple data-size="8" data-selected-text-format="count>6"  id="allAssets" data-live-search="true">
                                                                                                @if(count($assets)>0)
                                                                                                    <option value="All">Select All</option>
                                                                                                    @foreach($assets as $asset)
                                                                                                        <option value="{{$asset['id']}}" {{ (collect(old('datetimeschedual["asset"]['.$key.']',$assetArr))->contains($asset['id'])) ? 'selected':'' }} > {{$asset['name']}}</option>
                                                                                                    @endforeach
                                                                                                @endif
                                                                                            </select>

                                                                                        </div>
                                                                                        <div class="col-sm-6">
                                                                                            <label><span>Select Date and Time</span> <input type="text" class="daterange form-control datetime-control" placeholder="Add Date" name="datetimeschedual[{{$forms->id}}][]" value="" /></label>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            @endif

                                                                            <div class="row">
                                                                                <div class="col-sm-8">
                                                                                    <input type="hidden" value="{{json_encode($assets,true)}}"  id="asset_{{$forms->id}}">

                                                                                    <input type="button" data-id="{{$forms->id}}" class="btn btn-success add-more-restriction" value="Add More">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>


                                                                </div>

                                                            </div>

                                                            <div class="col-sm-12">
                                                                <div class="col-sm-2" style="margin-top: 30px;">
                                                                    <label>Make Reminder <input type="checkbox" class="reminder-checkbox" name="isReminder[{{$forms->id}}]" @if($answers != null && $forms->isReminder == 1) checked="checked" @endif value="1" /></label>
                                                                </div>
                                                                <div class="col-sm-10 makeRestriction">
                                                                    <div class="col-sm-4">
                                                                        <label>Select Days</label>
                                                                        <select class="form-group" name="reminderDays[{{$forms->id}}]">
                                                                            <option @if($forms->reminderDays == null) selected="selected" @endif   value="" >Select Days</option>
                                                                            <option @if($forms->reminderDays != null && $forms->reminderDays == '1') selected="selected" @endif value="1">1 Day before</option>
                                                                            <option @if($answers->reminderDays != null && $answers->reminderDays == '2') selected="selected" @endif value="2">2 Day before</option>
                                                                            <option @if($answers->reminderDays != null && $answers->reminderDays == '3') selected="selected" @endif value="3">3 Day before</option>
                                                                            <option @if($answers->reminderDays != null && $answers->reminderDays == '4') selected="selected" @endif value="4">4 Day before</option>
                                                                            <option @if($answers->reminderDays != null && $answers->reminderDays == '5') selected="selected" @endif value="5">5 Day before</option>
                                                                            <option @if($answers->reminderDays != null && $answers->reminderDays == '6') selected="selected" @endif value="6">6 Day before</option>
                                                                        </select>
                                                                    </div>

                                                                    <div class="col-sm-4">
                                                                        <label>Select Hours</label>
                                                                        <select class="form-group" name="reminderHours[{{$forms->id}}]">
                                                                            <option @if($forms->reminderHours == null) selected="selected" @endif  value="" >Select Hours</option>
                                                                            <option @if($forms->reminderHours != null && $forms->reminderHours == '1') selected="selected" @endif  value="1">1 Hour</option>
                                                                            <option @if($answers->reminderHours != null && $answers->reminderHours == '2') selected="selected" @endif  value="2">2 Hours</option>
                                                                            <option @if($answers->reminderHours != null && $answers->reminderHours == '3') selected="selected" @endif  value="3">3 Hours</option>
                                                                            <option @if($answers->reminderHours != null && $answers->reminderHours == '4') selected="selected" @endif  value="4">4 Hours</option>
                                                                            <option @if($answers->reminderHours != null && $answers->reminderHours == '5') selected="selected" @endif  value="5">5 Hours</option>
                                                                            <option @if($answers->reminderHours != null && $answers->reminderHours == '6') selected="selected" @endif  value="6">6 Hours</option>
                                                                            <option @if($answers->reminderHours != null && $answers->reminderHours == '7') selected="selected" @endif  value="7">7 Hours</option>
                                                                            <option @if($answers->reminderHours != null && $answers->reminderHours == '8') selected="selected" @endif  value="8">8 Hours</option>
                                                                            <option @if($answers->reminderHours != null && $answers->reminderHours == '9') selected="selected" @endif  value="9">9 Hours</option>
                                                                            <option @if($answers->reminderHours != null && $answers->reminderHours == '10') selected="selected" @endif  value="10">10 Hours</option>
                                                                            <option @if($answers->reminderHours != null && $answers->reminderHours == '11') selected="selected" @endif  value="11">11 Hours</option>
                                                                            <option @if($answers->reminderHours != null && $answers->reminderHours == '12') selected="selected" @endif  value="12">12 Hours</option>
                                                                            <option @if($answers->reminderHours != null && $answers->reminderHours == '13') selected="selected" @endif  value="13"> 13 Hour</option>
                                                                            <option @if($answers->reminderHours != null && $answers->reminderHours == '14') selected="selected" @endif  value="14"> 14 Hour</option>
                                                                            <option @if($answers->reminderHours != null && $answers->reminderHours == '15') selected="selected" @endif  value="15"> 15 Hour</option>
                                                                            <option @if($answers->reminderHours != null && $answers->reminderHours == '16') selected="selected" @endif  value="16"> 16 Hour</option>
                                                                            <option @if($answers->reminderHours != null && $answers->reminderHours == '17') selected="selected" @endif  value="17"> 17 Hour</option>
                                                                            <option @if($answers->reminderHours != null && $answers->reminderHours == '18') selected="selected" @endif  value="18"> 18 Hour</option>
                                                                            <option @if($answers->reminderHours != null && $answers->reminderHours == '19') selected="selected" @endif  value="19"> 19 Hour</option>
                                                                            <option @if($answers->reminderHours != null && $answers->reminderHours == '20') selected="selected" @endif  value="20"> 20 Hour</option>
                                                                            <option @if($answers->reminderHours != null && $answers->reminderHours == '21') selected="selected" @endif  value="21"> 21 Hour</option>
                                                                            <option @if($answers->reminderHours != null && $answers->reminderHours == '22') selected="selected" @endif  value="22"> 22 Hour</option>
                                                                            <option @if($answers->reminderHours != null && $answers->reminderHours == '23') selected="selected" @endif  value="23"> 23 Hour</option>

                                                                        </select>
                                                                    </div>
                                                                    <div class="col-sm-4">
                                                                        <label>Select Minutes</label>
                                                                        <input @if($answers->reminderMinutes != null) value="{{$answers->reminderMinutes}}" @endif type="text" min="5" step="1" id="reminderMinutes" onkeypress="return isNumber(event)" placeholder="Enter Minutes Before Scheduler" class="form-control" name="reminderMinutes[{{$forms->id}}]">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>

                                                    @endforeach
                                                @else
                                                    <label>No Forms Associated yet!</label>
                                                @endif
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    {!! Form::submit("Update" , ['class' =>"btn btn-lg btn-primary btn-close"]) !!}
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="panel panel-default">
                    <div class="panel-heading accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" data-target="#1">
                        <h2>Show Title</h2>
                    </div>
                    <div id="1" class="defaultClasses panel-collapse collapse in">
                        <div class="panel-body">
                            {!! Form::open(['url'=>'master-template/schedular/add/restriction/','method'=>'post','class'=>'form-horizontal']) !!}

                            <div class="col-sm-12">

                                <div class="row" style="margin-top: 7px;">

                                    <div class="col-md-4">
                                        <label><span>Show Title :</span></label>
                                        <input  type="text" class="form-control c-control" required="required"  name="showTitle" />
                                    </div>

                                    <div class="col-md-4" >
                                        <label><span>Date From :</span></label>
                                        <input  type="text"  name="dateFrom" required="" class="form-control datetimepicker8 c-control">
                                    </div>
                                    <div class="col-md-4" >
                                        <label><span>Date To :</span></label>
                                        <input  type="text"  name="dateTo" required="" class="form-control datetimepicker8 c-control">
                                    </div>

                                    <div class="col-md-12" style="margin-top: 12px;">
                                        <label><span>Location :</span></label>

                                        {{ Form::text('location', isset($location)? $location :'', ['id' => '',
                                        'placeholder'=>"Location *",'required'=>'required' ,'class' => 'form-control search-input-scheduler','style'=>'float: right; width: 87.5%;']) }}
                                    </div>

                                </div>

                                <div class="invite-participant-history">
                                    @if($schedular_forms->count())
                                        @foreach($schedular_forms as $forms)
                                            <input type="hidden" name="template_id" value="{{$template_id}}">


                                            <?php
                                            $restriction = [];
                                            $answ =  restrictedScheduledDates($forms->id);
                                            $answers = $answ->first();
                                            if($answers!='')
                                                $restriction = $answers->SchedulerRestriction->toArray();

                                            //print_r($restriction);
                                            $key = $forms->id;
                                            ?>

                                            @if($answers!= "")
                                                <?php $key = $answers['id']; ?>

                                                <input type="hidden" name="schedual_id[{{$key}}]" value="{{$answers['id']}}">
                                            @else
                                                <?php $key = $forms->id;?>
                                            @endif

                                            <?php $assets = checkSchedulerTime($template_id,$forms->id);?>

                                            <input type="hidden" name="form_id[{{$key}}]" value="{{$forms->id}}">

                                            <div class="create-form">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <h3>{{$forms->name}}</h3><br>
                                                        <div class="row fields">
                                                            <div class="col-sm-6">
                                                                <label><span>Schedular Name :</span><input type="text" class="form-control c-control"  @if($answers != null) value="{{$answers['name']}}" @endif name="schedular_name[{{$key}}]" /></label>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <label>Make Ristriction <input type="checkbox" class="ristriction-checkbox" name="schedular_checbox[{{$key}}]" @if($answers != null && $answers["status"] == 1) checked="checked" @endif value="1" /></label>
                                                            </div>

                                                            <div class="col-sm-12">
                                                                <div class="schedual-restrictions">

                                                                    @if($restriction!= null && isset($restriction))
                                                                        <div class="adding-restrictions-options">
                                                                            @foreach($restriction as $row)

                                                                                <?php $assetArr = explode(',',$row['asset_id']);?>

                                                                                <div class="row" style=" margin-bottom: 15px;">
                                                                                    <div class="col-sm-5 ClassAssets">
                                                                                        <label style="padding-top:5px"><span>Select Class:</span></label>
                                                                                        <select multiple name="datetimeschedual['asset'][{{$key}}][]" style="width: 75%" class="selectpicker show-tick form-control" multiple data-size="8" data-selected-text-format="count>6"  id="allAssets" data-live-search="true">
                                                                                            @if(count($assets)>0)
                                                                                                {{--<option value="All">Select All</option>--}}
                                                                                                @foreach($assets as $asset)
                                                                                                    <option value="{{$asset['id']}}" {{ (collect(old('datetimeschedual["asset"]['.$key.']',$assetArr))->contains($asset['id'])) ? 'selected':'' }} > {{$asset['name']}}</option>
                                                                                                @endforeach
                                                                                            @endif
                                                                                        </select>

                                                                                    </div>

                                                                                    <div class="col-sm-6">
                                                                                        <label><span>Select Date and Time</span>
                                                                                            <input type="text" class="daterange form-control datetime-control"
                                                                                                   placeholder="Add Date" name="datetimeschedual['restriction'][{{$key}}][]"
                                                                                                   value="{{$row['restriction']}}" /></label>
                                                                                    </div>
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                    @else
                                                                        <div class="adding-restrictions-options">
                                                                            <div class="row dateCon">
                                                                                <div class="col-sm-5 ClassAssets">
                                                                                    <label style="padding-top:5px"><span>Select Class:</span></label>
                                                                                    <select multiple name="asset[{{$key}}][1][]" style="width: 75%" class="selectpicker show-tick form-control" multiple data-size="8" data-selected-text-format="count>6"  id="allAssets" data-live-search="true">
                                                                                        @if(count($assets)>0)
                                                                                            <option value="All">Select All</option>
                                                                                            @foreach($assets as $asset)
                                                                                                <option value="{{$asset['id']}}" @if(old("asset") != null) {{ (in_array($asset['id'], old("asset")) ? "selected":"") }} @endif> {{$asset['name']}}</option>
                                                                                            @endforeach
                                                                                        @endif
                                                                                    </select>

                                                                                </div>
                                                                                <div class="col-sm-6">
                                                                                    <label><span>Select Date and Time</span> <input type="text" disabled="disabled" class="daterange form-control datetime-control"
                                                                                                                                    placeholder="Add Date" name="datetimeschedual[{{$key}}][1]" value="" /></label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endif

                                                                    <div class="row">
                                                                        <div class="col-sm-8">
                                                                            <input type="hidden" value="{{json_encode($assets,true)}}"  id="asset{{$key}}">
                                                                            <input type="button" data-id="{{$key}}" class="btn btn-success add-more-restriction" value="Add More">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>


                                                        </div>

                                                    </div>

                                                    <div class="col-sm-12">
                                                        <div class="col-sm-2" style="margin-top: 30px;">
                                                            <label>Make Reminder <input type="checkbox" class="reminder-checkbox" name="isReminder[{{$key}}]" @if($answers != null && $answers["isReminder"] == 1) checked="checked" @endif value="1" /></label>
                                                        </div>
                                                        <div class="col-sm-10 makeRestriction">
                                                            <div class="col-sm-4">
                                                                <label>Select Days</label>
                                                                <select class="form-group" name="reminderDays[{{$key}}]">
                                                                    <option @if($answers == null) selected="selected" @endif   value="" >Select Days</option>
                                                                    <option @if($answers != null && $answers["reminderDays"] == '1') selected="selected" @endif value="1">1 Day before</option>
                                                                    <option @if($answers != null && $answers["reminderDays"] == '2') selected="selected" @endif value="2">2 Day before</option>
                                                                    <option @if($answers != null && $answers["reminderDays"] == '3') selected="selected" @endif value="3">3 Day before</option>
                                                                    <option @if($answers != null && $answers["reminderDays"] == '4') selected="selected" @endif value="4">4 Day before</option>
                                                                    <option @if($answers != null && $answers["reminderDays"] == '5') selected="selected" @endif value="5">5 Day before</option>
                                                                    <option @if($answers != null && $answers["reminderDays"] == '6') selected="selected" @endif value="6">6 Day before</option>
                                                                </select>
                                                            </div>

                                                            <div class="col-sm-4">
                                                                <label>Select Hours</label>
                                                                <select class="form-group" name="reminderHours[{{$key}}]">
                                                                    <option @if($answers == null) selected="selected" @endif  value="" >Select Hours</option>
                                                                    <option @if($answers != null && $answers["reminderHours"] == '1') selected="selected" @endif  value="1">1 Hour</option>
                                                                    <option @if($answers != null && $answers["reminderHours"] == '2') selected="selected" @endif  value="2">2 Hours</option>
                                                                    <option @if($answers != null && $answers["reminderHours"] == '3') selected="selected" @endif  value="3">3 Hours</option>
                                                                    <option @if($answers != null && $answers["reminderHours"] == '4') selected="selected" @endif  value="4">4 Hours</option>
                                                                    <option @if($answers != null && $answers["reminderHours"] == '5') selected="selected" @endif  value="5">5 Hours</option>
                                                                    <option @if($answers != null && $answers["reminderHours"] == '6') selected="selected" @endif  value="6">6 Hours</option>
                                                                    <option @if($answers != null && $answers["reminderHours"] == '7') selected="selected" @endif  value="7">7 Hours</option>
                                                                    <option @if($answers != null && $answers["reminderHours"] == '8') selected="selected" @endif  value="8">8 Hours</option>
                                                                    <option @if($answers != null && $answers["reminderHours"] == '9') selected="selected" @endif  value="9">9 Hours</option>
                                                                    <option @if($answers != null && $answers["reminderHours"] == '10') selected="selected" @endif  value="10">10 Hours</option>
                                                                    <option @if($answers != null && $answers["reminderHours"] == '11') selected="selected" @endif  value="11">11 Hours</option>
                                                                    <option @if($answers != null && $answers["reminderHours"] == '12') selected="selected" @endif  value="12">12 Hours</option>
                                                                    <option @if($answers != null && $answers["reminderHours"] == '13') selected="selected" @endif  value="13"> 13 Hour</option>
                                                                    <option @if($answers != null && $answers["reminderHours"] == '14') selected="selected" @endif  value="14"> 14 Hour</option>
                                                                    <option @if($answers != null && $answers["reminderHours"] == '15') selected="selected" @endif  value="15"> 15 Hour</option>
                                                                    <option @if($answers != null && $answers["reminderHours"] == '16') selected="selected" @endif  value="16"> 16 Hour</option>
                                                                    <option @if($answers != null && $answers["reminderHours"] == '17') selected="selected" @endif  value="17"> 17 Hour</option>
                                                                    <option @if($answers != null && $answers["reminderHours"] == '18') selected="selected" @endif  value="18"> 18 Hour</option>
                                                                    <option @if($answers != null && $answers["reminderHours"] == '19') selected="selected" @endif  value="19"> 19 Hour</option>
                                                                    <option @if($answers != null && $answers["reminderHours"] == '20') selected="selected" @endif  value="20"> 20 Hour</option>
                                                                    <option @if($answers != null && $answers["reminderHours"] == '21') selected="selected" @endif  value="21"> 21 Hour</option>
                                                                    <option @if($answers != null && $answers["reminderHours"] == '22') selected="selected" @endif  value="22"> 22 Hour</option>
                                                                    <option @if($answers != null && $answers["reminderHours"] == '23') selected="selected" @endif  value="23"> 23 Hour</option>

                                                                </select>
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <label>Select Minutes</label>
                                                                <input @if($answers != null) value="{{$answers["reminderMinutes"]}}" @endif type="text" min="5" step="1" id="reminderMinutes" onkeypress="return isNumber(event)" placeholder="Enter Minutes Before Scheduler" class="form-control" name="reminderMinutes[{{$key}}]">
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                        @endforeach
                                    @else
                                        <label>No Forms Associated yet!</label>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-3">
                                {!! Form::submit("Update" , ['class' =>"btn btn-lg btn-primary btn-close"]) !!}
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            @endif
            <div class="hide addMoreCon" style="margin-top: 10px; position: relative">


                <div class="panel panel-default addMoreScheduler">
                    <div class="panel-heading accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" data-target="">
                        <h2>Show Title</h2>
                    </div>
                    <div class="panel-collapse collapse">
                        <div class="panel-body">
                            {!! Form::open(['url'=>'master-template/schedular/add/restriction/','method'=>'post','class'=>'form-horizontal']) !!}

                            <div class="col-sm-12">



                                <div class="row" style="margin-top: 7px;">

                                    <div class="col-md-4">
                                        <label><span>Show Title :</span></label>
                                        <input  type="text" class="form-control c-control" required="required"  name="showTitle" />
                                    </div>

                                    <div class="col-md-4" >
                                        <label><span>Date From :</span></label>
                                        <input  type="text"  name="dateFrom" required="" class="form-control datetimepicker8 c-control">
                                    </div>
                                    <div class="col-md-4" >
                                        <label><span>Date To :</span></label>
                                        <input  type="text"  name="dateTo" required="" class="form-control datetimepicker8 c-control">
                                    </div>
                                    <div class="col-md-12" style="margin-top: 12px;">
                                        <label><span>Location :</span></label>

                                        {{ Form::text('location', isset($location)? $location :'', ['id' => '',
                                        'placeholder'=>"Location *",'required'=>'required' ,'class' => 'form-control search-input-scheduler','style'=>'float: right; width: 87.5%;']) }}
                                    </div>
                                </div>

                                <div class="invite-participant-history">
                                    @if($schedular_forms->count())
                                        @foreach($schedular_forms as $forms)
                                            <input type="hidden" name="template_id" value="{{$template_id}}">
                                            <input type="hidden" name="form_id[{{$forms->id}}]" value="{{$forms->id}}">
                                            <?php $answers =  restrictedScheduledDates($forms->id);?>
                                            <?php $assets = checkSchedulerTime($template_id,$forms->id);


                                            ?>

                                            <div class="create-form">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <h3>{{$forms->name}}</h3><br>
                                                        <div class="row fields">
                                                            <div class="col-sm-6">
                                                                <label><span>Schedular Name :</span><input type="text" class="form-control c-control"   name="schedular_name[{{$forms->id}}]" /></label>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <label>Make Ristriction <input type="checkbox" class="ristriction-checkbox" name="schedular_checbox[{{$forms->id}}]"  value="1" /></label>
                                                            </div>

                                                            <div class="col-sm-12">
                                                                <div class="schedual-restrictions">

                                                                    {{--@if($answers!= "" && isset($answers["restriction"]))--}}
                                                                    {{--<div class="adding-restrictions-options">--}}
                                                                    {{--@foreach($answers["restriction"] as $restriction)--}}

                                                                    {{--<div class="row" style=" margin-bottom: 15px;">--}}
                                                                    {{--<div class="col-sm-5 ClassAssets">--}}
                                                                    {{--<label style="padding-top:5px"><span>Select Class:</span></label>--}}
                                                                    {{--<select multiple name="asset[{{$forms->id}}][]" style="width: 75%" class="selectpicker show-tick form-control" multiple data-size="8" data-selected-text-format="count>6"  id="allAssets" data-live-search="true">--}}
                                                                    {{--@if(count($assets)>0)--}}
                                                                    {{--<option value="All">Select All</option>--}}
                                                                    {{--@foreach($assets as $asset)--}}
                                                                    {{--<option value="{{$asset['id']}}" @if(old("asset") != null) {{ (in_array($asset['id'], old("asset")) ? "selected":"") }} @endif> {{$asset['name']}}</option>--}}
                                                                    {{--@endforeach--}}
                                                                    {{--@endif--}}
                                                                    {{--</select>--}}

                                                                    {{--</div>--}}

                                                                    {{--<div class="col-sm-6">--}}
                                                                    {{--<label><span>Select Date and Time</span> <input type="text" class="daterange form-control datetime-control" placeholder="Add Date" name="datetimeschedual[{{$forms->id}}][]" value="{{$restriction}}" /></label>--}}
                                                                    {{--</div>--}}
                                                                    {{--</div>--}}
                                                                    {{--@endforeach--}}
                                                                    {{--@endif--}}
                                                                    <div class="row">
                                                                        <div class="col-sm-8">
                                                                            <input type="hidden" value="{{json_encode($assets,true)}}"  id="asset{{$forms->id}}">
                                                                            <input type="button" data-id="{{$forms->id}}" class="btn btn-success add-more-restriction" value="Add More">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>


                                                        </div>

                                                    </div>

                                                    <div class="col-sm-12">
                                                        <div class="col-sm-2" style="margin-top: 30px;">
                                                            <label>Make Reminder <input type="checkbox" class="reminder-checkbox" name="isReminder[{{$forms->id}}]" value="1" /></label>
                                                        </div>
                                                        <div class="col-sm-10 makeRestriction">
                                                            <div class="col-sm-4">
                                                                <label>Select Days</label>
                                                                <select class="form-group" name="reminderDays[{{$forms->id}}]">
                                                                    <option  value="" >Select Days</option>
                                                                    <option  value="1">1 Day before</option>
                                                                    <option   value="2">2 Day before</option>
                                                                    <option  value="3">3 Day before</option>
                                                                    <option  value="4">4 Day before</option>
                                                                    <option   value="5">5 Day before</option>
                                                                    <option   value="6">6 Day before</option>
                                                                </select>
                                                            </div>

                                                            <div class="col-sm-4">
                                                                <label>Select Hours</label>
                                                                <select class="form-group" name="reminderHours[{{$forms->id}}]">
                                                                    <option   value="" >Select Hours</option>
                                                                    <option value="1">1 Hour</option>
                                                                    <option  value="2">2 Hours</option>
                                                                    <option  value="3">3 Hours</option>
                                                                    <option value="4">4 Hours</option>
                                                                    <option   value="5">5 Hours</option>
                                                                    <option   value="6">6 Hours</option>
                                                                    <option   value="7">7 Hours</option>
                                                                    <option  value="8">8 Hours</option>
                                                                    <option  value="9">9 Hours</option>
                                                                    <option  value="10">10 Hours</option>
                                                                    <option  value="11">11 Hours</option>
                                                                    <option   value="12">12 Hours</option>
                                                                    <option   value="13"> 13 Hour</option>
                                                                    <option  value="14"> 14 Hour</option>
                                                                    <option  value="15"> 15 Hour</option>
                                                                    <option  value="16"> 16 Hour</option>
                                                                    <option  value="17"> 17 Hour</option>
                                                                    <option  value="18"> 18 Hour</option>
                                                                    <option  value="19"> 19 Hour</option>
                                                                    <option  value="20"> 20 Hour</option>
                                                                    <option  value="21"> 21 Hour</option>
                                                                    <option  value="22"> 22 Hour</option>
                                                                    <option   value="23"> 23 Hour</option>

                                                                </select>
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <label>Select Minutes</label>
                                                                <input   type="text" min="5" step="1" id="reminderMinutes" onkeypress="return isNumber(event)" placeholder="Enter Minutes Before Scheduler" class="form-control" name="reminderMinutes[{{$forms->id}}]">
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                        @endforeach
                                    @else
                                        <label>No Forms Associated yet!</label>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-3">
                                {!! Form::submit("Update" , ['class' =>"btn btn-lg btn-primary btn-close"]) !!}
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
                <a class="removeScheduler" href="javascript:" style="position: absolute; top: 0px; right: -16px;">
                    <span class="glyphicon glyphicon-remove"></span>
                </a>
            </div>
        </div>

    </div>


@endsection
@section('footer-scripts')
    @include('layouts.partials.datatable')
    <script src="{{ asset('/js/schedular-form-jquery.js') }}"></script>
    <script src="{{ asset('/js/schedual.js') }}"></script>
    <script src="{{ asset('/js/google-map-script-search.js') }}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCWp7OvMOkqzMjDTNHDstANUQatmbuWyWo&libraries=places&callback=initialize"
            async defer></script>
    <style>
        .adding-restrictions-options .bootstrap-select{ width: 75%!important;}
    </style>
@endsection