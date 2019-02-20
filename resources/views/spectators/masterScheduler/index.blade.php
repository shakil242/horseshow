@extends('layouts.equetica2')
@section('main-content')
    <div class="row">
        <div class="col-sm-8">
            <h1>Master Scheduler</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            {{--{!! Breadcrumbs::render('master-template-assets',$template_id) !!}--}}
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            @if($spectatorsForms->count())
                <ul id="display-scheduler-assets" class="nav nav-tabs">
                    @foreach($spectatorsForms->spectatorsForm as $index => $row)
                        <li {{($index == 0)? "class=active":""}}><a data-toggle="tab" data-templateId="{{nxb_encode($variables['templateId'])}}" href="#tab_{{$row->form_id}}" data-attr="{{nxb_encode($row->form_id)}}">{{getFormNamefromid($row->form_id)}}</a></li>
                    @endforeach
                </ul>
            @endif

        </div>
        <input id="csrf-token" type="hidden" name="_token" value="{{ csrf_token() }}">

    </div>
    <!-- Tab containing all the data tables -->
    <div class="tab-pane fade in active">
        <div class="row">

            <div class="col-sm-12">
                <div class="ToggleColumb">

                </div>
                <div id="ajax-loading" class="loading-ajax"></div>



                @if($spectatorsForms->count())
                    <div id="calendarContainer">

                        {!! $calendar->calendar() !!}
                        {!! $calendar->script() !!}

                    </div>
                @else

                    <div class="info">
                        <p class="alert alert-class alert-info">There are no Forms against this scheduler</p>
                    </div>
                @endif
            </div>
        </div>
        <!--- App listing -->

        <div id="eventContent" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h2 class="modal-title" id="exampleModalLabel">Appointments</h2>
                    </div>


                    {!! Form::open(['url'=>'','method'=>'post','id'=>'addNotes']) !!}

                    <div class="modal-body">

                        <div class="row" style="padding: 0px 15px">
                            <div class="info">
                                <p style="display: none" class="addNotesMessage text-center alert alert-success"></p>
                            </div>
                        </div>

                        <div class="invite-wrapper">
                            <div class="invite-holder">
                                <input type="hidden" name="template_id" value="" class="addtemplateid">
                                <a class="btn-remove"><i class="fa fa-times" aria-hidden="true"></i></a>



                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Start Time</label>
                                            <select id="timeFrom" name="timeFrom" class="selectpicker">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>End Time</label>
                                            <select id="timeTo" name="timeTo"  class="selectpicker">
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Score</label>
                                            <input type="number"  name="score" class="form-control score" />
                                        </div>
                                    </div>


                                    <div id="myDiv" style="color: red; padding-left: 16px; padding-bottom: 10px;"></div>

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <textarea style="background: #ffffff" name="notes" id="notes"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-buttons">
                            <div class="row">
                                <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                                <input type="hidden" value="" name="startTime"  id="startTime"/>
                                <input type="hidden" value="" name="endTime"  id="endTime"/>

                                <input type="hidden" value="{{$variables['templateId']}}" name="template_id"  id="templateId">
                                <input type="hidden" value="" name="userId"  id="userId">
                                <input type="hidden" value="" name="form_id"  id="form_id">

                                <input type="hidden" value="" name="masterScheduler"  id="masterScheduler">

                                <input type="hidden" value="" name="backgrounbdSlotId"  id="backgrounbdSlotId">

                                <input type="hidden" value="" name="schedule_id"  id="schedule_id">

                                <div class="col-sm-2" style="padding-right: 8px;">
                                    <input type="submit" value="Save"   id="markSave" class="btn btn-sm btn-success btn-invite-more" />
                                </div>
                                <div class="col-sm-2" style="padding-left: 8px;padding-right: 8px;">
                                    <input type="button" value="Cancel" data-dismiss="modal" aria-label="Close" class="btn btn-sm btn-defualt" />
                                </div>

                                <div class="col-sm-3" style="padding-left: 8px;padding-right: 8px;">
                                    <input type="button" value="Mark Done"  id="markDone"  class="btn btn-sm btn-primary markDone" />
                                </div>

                                <div class="col-sm-2" style="padding-left: 8px;padding-right: 8px;">
                                    <a  onclick="goToFeedBack()"  style="color: #fff;" class="btn btn-sm btn-primary">Feedback</a>
                                </div>
                                <div class="col-sm-3" style="padding-left: 8px;">
                                    <input type="button" value="Send Reminder"  id="sendReminder"  class="btn btn-primary sendReminder" />
                                </div>


                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>


    </div>



@endsection

@section('footer-scripts')


    <script>
        var calId='{{$variables['calId']}}';
    </script>


    <script src="{{ asset('/js/custom-tabs-cookies.js') }}"></script>

    <script src="{{ asset('/js/vender/moment.min.js') }}"></script>
    <script src="{{ asset('/js/vender/fullcalendar.min.js') }}"></script>
    <script src='{{ asset('/js/vender/home.js?3.3.1-1.6.1-3') }}'></script>
    <link rel="stylesheet" href="{{ asset('/css/vender/fullcalendar.min.css') }}"/>
    <script src="{{ asset('/js/vender/jquery-ui.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('/css/vender/jquery-ui.css') }}" />
    <script type="text/javascript" src="{{ asset('/js/vender/bootstrap-tooltip.js') }}"></script>
    <link href="{{ asset('/css/vender/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('/css/vender/daterangepicker.css') }}" rel="stylesheet" />
    <script type="text/javascript" src="{{ asset('/js/vender/transition.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/vender/collapse.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/vender/bootstrap-datetimepicker.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/vender/daterangepicker.js') }}"></script>

    <script type="text/javascript" src="{{ asset('/js/schedular-calendar.js') }}"></script>

@endsection
