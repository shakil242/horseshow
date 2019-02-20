{!! $calendar->calendar() !!}
{!! $calendar->script() !!}

<div class="row ml-10">
@if($feedBackAssciated_opt || $feedBackAssciated_cmp)
<div class="linksToFeedbackForm hidden">
    <?php $feedbackCmp = json_decode($feedBackAssciated_cmp); ?>
    @if($feedbackCmp)
           <div class="col-md-12 mb-10 pl-0">
               <h4 class="modal-title pl-0" id="">Compulsory Feedback</h4>
           </div>
                <div class="col-md-12 pl-0 ml-0">
           @foreach($feedbackCmp as $form_id => $form_name)
            <?php $access = getFromAccessRights($form_id,$spectatorsId);?>
                @if($spectatorsId!='' && $access==2)
                        <a onclick="goToFeedBack({{$form_id}})" style="color: #fff;" class="btn btn-sm mb-10 btn-primary">{{$form_name}}</a>
                @elseif($access==1 && $spectatorsId=='')
                        <a onclick="goToFeedBack({{$form_id}})" style="color: #fff;" class="btn mb-10 btn-sm btn-primary">{{$form_name}}</a>
                @endif
                <input type="hidden" name="compulsory_form_ids[]" value="{{$form_id}}">
            @endforeach
                </div>
    @endif

    <?php $feedbackOpt = json_decode($feedBackAssciated_opt); ?>
    @if($feedbackOpt)
           <div class="col-md-12 mb-10 pl-0">
               <h4 class="modal-title" id="">Optional Feedback</h4>
           </div>
        @foreach($feedbackOpt as $form_id => $form_name)
            <?php $access = getFromAccessRights($form_id,$spectatorsId);?>
                @if($spectatorsId!='' && $access==2)
                        <a onclick="goToFeedBack({{$form_id}})" style="color: #fff;" class="btn mb-10 btn-sm btn-primary">{{$form_name}}</a>
                @elseif($access==1 && $spectatorsId=='')
                        <a onclick="goToFeedBack({{$form_id}})" style="color: #fff;" class="btn mb-10 btn-sm btn-primary">{{$form_name}}</a>
                @endif
        @endforeach
    @endif

@endif
</div>
</div>
@if($spectatorsId!='')

    @if($existingPositions)
        <div class="placing-ajax hidden">
            <div class="list-group">
                <div class="col-md-12"><h3>Placements</h3></div>

                    @php
                    $pos_answers = json_decode($existingPositions->position_fields,true);

                    $pos_answers = record_sort($pos_answers, "score",true);
                    array_unshift($pos_answers, "phoney");
                    unset($pos_answers[0]);
                    @endphp

                {{--<div class="col-md-12"> <h5>{{GetAssetNamefromId($existingPositions->asset_id)}}</h5></div>--}}
                    <table id="crudTable2" class="table primary-table">
                        <thead class="hidden-xs">
                        <tr>
                            <th style="width:25%">#</th>
                            <th>Participant</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($pos_answers as $key => $post)
                            @if(isset($post['horse_id']))
                                <tr>
                                    <td style="padding-right: 0px;">
                                        <h5 class="text-secondary">
                                            <img class="pr-5" src="{{asset('img/icons/icon-rank.svg')}} "><strong>{!! getPostionText($key) !!}</strong>
                                        </h5>
                                    </td>
                                    <td><span style="overflow-wrap: break-word;">{!! getHorseNameAndUserfromid($post['horse_id'],$existingPositions->asset_id,$existingPositions->show_id,'participant') !!}</span></td>
                                </tr>
                            @endif
                        @endforeach

                        </tbody>

                    </table>
            </div>
        </div>
    @endif

 @if(isset($champ))
<div class="champion-division hidden">
    <div class="col-md-12"><h3>{{$champ->division_name}}</h3></div>
    <div class="col-lg-12 detail-area">
        <div class="pb-20">
            <span class="label"><i class="fa fa-trophy text-lg pr-5 text-secondary"></i>Champion</span>
            <span class="block"><a class="text-secondary underline" href="#"><?php if(isset($champ->champions)){echo getDivisionChampion($champ);}else{ echo "Not set.";}?></a> </span>
        </div>
        <div class="pb-20">
            <span class="label"><i class="fa fa-trophy text-lg pr-5 text-muted"></i>Reserve  Champion</span>
            <span class="block"><a class="text-secondary underline" href="#"> <?php if(isset($champ->champions)){echo getDivisionChampion($champ,2);}else{ echo "Not set.";}?></a></span>
        </div>
    </div>

</div>
@endif
@else
@if(isset($champ))
<div class="champion-division hidden">
    <div class="col-md-12"><h3>{{$champ->division_name}}</h3></div>
    <div class="col-lg-12 detail-area">
    <div class="pb-20">
        <span class="label"><i class="fa fa-trophy text-lg pr-5 text-secondary"></i>Champion</span>
        <span class="block"><a class="text-secondary underline" href="#"><?php if(isset($champ->champions)){echo getDivisionChampion($champ);}else{ echo "Not set.";}?></a> </span>
    </div>
    <div class="pb-20">
        <span class="label"><i class="fa fa-trophy text-lg pr-5 text-muted"></i>Reserve  Champion</span>
        <span class="block"><a class="text-secondary underline" href="#"> <?php if(isset($champ->champions)){echo getDivisionChampion($champ,2);}else{ echo "Not set.";}?></a></span>
    </div>
    </div>

</div>
@endif

@if(isset($placing->place) )
<div class="placing-ajax hidden">
    <div class="col-md-12"><h3>Positions</h3></div>
    {!! Form::open(['method'=>'post','id'=>'SaveHorsePosition']) !!}
    <input type="hidden" class="ast_id" name="show_id" value="{{$showId}}">
    <input type="hidden"  class="shw_id" name="asset_id" value="{{$assetId}}">
    <input type="hidden" name="template_id" value="{{$templateId}}">
    <input type="hidden" name="form_id" value="{{$formId}}">


    <input type="hidden" name="positioning_id" value="{{$positioning_id}}">
    <div class="positionCon">
    @foreach ($placing->place as $post)
    <div class="col-lg-12 detail-area pb-30">

        <h5 class="text-secondary">
            <img class="pr-5" src="{{asset('img/icons/icon-rank.svg')}} "><strong>{!! getPostionText($post->position) !!}</strong>
        </h5>

        @if($participants != null)
            <div class="col-sm-12 p-0">
            <fieldset class="form-group select-bottom-line-only">
                <select onchange="checkScore($(this))" name="participants[{{$post->position}}][horse_id]" class="check-select-opt form-control form-control-bb-only form-control-sm">
                  <option value="">Select Horse</option>
                    @foreach($participants as $horses)
                        @if($horses->horse)
                            @if($pos_answers)
                                <option value="{{$horses->horse_id}}" {{getSelectedValuesMultiple($pos_answers,$post->position,$horses->horse_id)}} >{{GetAssetName($horses->horse). ' [Entry# '.$horses->horse_reg.'] ('. $horses->user->name.')' }}</option>
                            @else
                                <option value="{{$horses->horse_id}}">{{GetAssetName($horses->horse).' [Entry# '.$horses->horse_reg.'] ('.$horses->user->name.')'}}</option>
                            @endif
                        @endif
                    @endforeach
                </select>
            </fieldset>
            </div>
        @endif




        {{--@if($horse_rating_type!=1)--}}

            @foreach($restrictions as $k=>$r)
                <div class="form-group form-inline">
                    <label for="first_name" class="col-xs-3 col-form-label pr-2">R#{{$k+1}}</label>
                    <div class="col-xs-9">
                        <input  class="scores form-control form-control-sm form-control-bb-only"  type="number"
                                placeholder="Enter Score" name="participants[{{$post->position}}][rounds][{{$r}}]"
                                @if(isset($pos_answers)) value="{{getScore($pos_answers,$post->position,$r)}}" @endif>
                    </div>
                </div>
                <input type="hidden" name="participants[{{$post->position}}][position]" value="{{$post->position}}">
                <input type="hidden" name="participants[{{$post->position}}][price]" value="{{$post->price}}">
            @endforeach
        {{--@endif--}}

        <input type="hidden" name="restrictions" value="{{json_encode($restrictions)}}">

        @if(isset($pos_answers[$post->position]['scoreFrom']))
            {{--<a class="text-secondary hover-display" href="#"><i class="fa fa-edit"></i></a>--}}
            @foreach($pos_answers[$post->position]['scoreFrom'] as $sc)
                <div class="row" style="color: #001e46;">
                    <div class="col-md-6 pr-0"><strong>{{GetAssetNamefromId($sc["class_id"])}}</strong></div>
                    <div class="col-md-6">  <strong class="pull-right"> {{$sc["ClassScore"]}}</strong></div>
                </div>
                <input type="hidden" name="participants[{{$post->position}}][scoreFrom][{{$sc["class_id"]}}][class_id]" value="{{$sc["class_id"]}}">
                <input type="hidden" name="participants[{{$post->position}}][scoreFrom][{{$sc["class_id"]}}][ClassScore]" value="{{$sc["ClassScore"]}}">

            @endforeach
        @endif

        @if(isset($pos_answers))
            @if(isset($pos_answers[$post->position]['score']))
                <div class="col-sm-12 p-0">
                    <div class="row" style="color: #001e46;">
                        <div class="col-md-6 pr-0"><strong>Total Score</strong></div>
                        <div class="col-md-6">  <strong class="pull-right"> {{$pos_answers[$post->position]['score']}}</strong></div>
                    </div>
                </div>


                    @endif
        @endif


    </div>
    @endforeach
    </div>
    <div class="col-lg-12 detail-area">
        <input type="submit" class="btn btn-primary setPosition" value="Save Placement">
    </div>

    {!! Form::close()!!}
</div>
@endif
@endif

    <input type="hidden" value="{{$slots_duration}}" id="slots_duration">

    <?php $templateType = GetTemplateType($templateId); ?>

<div id="masterInviteRiders" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg" role="document" style="max-width: 1000px!important">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel">Invite Riders</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>


            {!! Form::open(['url'=>'','method'=>'post','id'=>'masterInvite']) !!}

            <input type="hidden" value="" id="is_multiple_selection" class="is_multiple_selection">
            <input type="hidden" value="" id="restriction_id" class="restriction_id">

            <div class="modal-body">

                <div class="row" style="padding: 0px 15px">
                    <div class="info">
                        <p style="display: none" class="addNotesMessage text-center alert alert-success"></p>
                    </div>
                </div>

                <div class="invite-wrapper">
                    <div class="invite-holder">
                        <input type="hidden" name="template_id" value="" class="addtemplateid">
                        <div class="inviteeUsers">

                        <div class="row master">
                            @php $heights = array(); @endphp
                            @if(isset($combinedClass))
                                @if($combinedClass->count()>0)
                                    @php
                                        $heights = json_decode($combinedClass->first()->heights);
                                    @endphp
                                @endif
                            @endif

                                <div class="{{(count($heights)>0?'col-sm-2':'col-sm-3')}}">
                                <div class="form-group userAlreadyCheck">
                                    <label for="">Select User</label>
                                    <select required name="users[]" onchange="getMultipleHorseAssets('{{$showId}}','{{$assetId}}',this,1)" class="form-control selectpicker">
                                     <option value="">Select User</option>
                                    @foreach($userArr as $key=>$v)
                                             <option value="{{$key}}">{{$v}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="{{(count($heights)>0?'col-sm-2':'col-sm-3')}}">
                                <div class="form-group ClassHorse">
                                    <label for="">Select Horse</label>
                                    <select class="form-control selectpicker" required>
                                        <option value="">No Horse Selected</option>
                                    </select>
                                </div>
                            </div>
                            @if(isset($combinedClass))
                            @if($combinedClass->count()>0)
                                @php
                                 $heights = json_decode($combinedClass->first()->heights);
                                @endphp
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label for="">Select Height</label>
                                        <select required name="heights[]"  class="form-control selectpicker">
                                            @foreach($heights as $key=>$v)
                                                <option value="{{$v}}">{{$v}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                            @endif
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="">Start Time</label>
                                    <select id="timeFromInvite" name="timeFrom[]"  class="form-control selectpicker">
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="">End Time</label>
                                    <select id="timeToInvite" name="timeTo[]"  class="form-control selectpicker">
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <a  href="javascript:" class="addRowMaster mt-30 btn-primary btn"> Add More </a>
                            </div>

                        </div>
                        </div>
                        <div class="row">

                            <div id="myDiv" style="color: red; padding-left: 16px; padding-bottom: 10px;"></div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <textarea style="background: #ffffff" name="notes" class="notes form-control form-control-lg" id="notes1"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-buttons">
                    <div class="row">
                        <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                        <input type="hidden" value="" name="startTime" id="startTime1"   class="startTime"/>
                        <input type="hidden" value="" name="endTime"  id="endTime1" class="endTime" />

                        <input type="hidden" value="{{$templateId}}" name="template_id"  id="templateId1" class="templateId">
                        <input type="hidden" value="" name="userId"  id="userId1" class="userId">
                        <input type="hidden" value="{{$formId}}" name="form_id"  id="formId1"   class="formId" >
                        <input type="hidden" value="{{$assetId}}" name="asset_id"  id="assetId1" class="assetId">
                        <input type="hidden" value="{{$showId}}" name="show_id"  id="showId1"  class="showId">

                        <input type="hidden" value="" name="masterScheduler"  id="masterScheduler1" class="masterScheduler">
                        <input type="hidden" value="" name="restriction_id"  id="restriction_id"  class="restriction_id">
                        <input type="hidden" value="" name="class_group_key"  id="class_group_key"  class="class_group_key">

                        <input type="hidden" value="" name="is_multiple_selection" class="is_multiple_selection"  id="is_multiple_selection">
                        <input type="hidden" value="" name="multiple_scheduler_key" class="multiple_scheduler_key"  id="multiple_scheduler_key">




                        <input type="hidden" value="" name="backgrounbdSlotId"  id="backgrounbdSlotId1" class="backgrounbdSlotId">

                        <input type="hidden" value="" name="schedule_id"  id="schedule_id1" class="schedule_id">

                        <div class="modal-footer">
                            <button type="submit"  id="masterInviteMarkSave" class="btn btn-primary btn-invite-more">Save</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>


                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

@php

    if(isset($heights) && count($heights)>0)
         $heightsJ = json_encode($heights);
    else
        $heightsJ = '';
@endphp

<script>

    @if($isCombined > 0)
    $(".combined").show();
    $(".combined").html($($.parseHTML("{!! $htmlContent !!}")).text());
    $("#calendarContainer").css({ opacity: 0.5 });
   $(".calendarCon").find("*").prop("disabled", true);
    $(".calendarCon").find("input,select,textarea,button").prop("disabled",true);
//    $(".calendarCon").attr('disabled','disabled');
    @else
    $(".combined").hide();
    $(".combined").html('');

    $("#calendarContainer").css({ opacity:1 });
    $(".calendarCon").attr('disabled','');
    @endif

    var isCombined ='{{$isCombined}}';

        var heights =eval({!! $heightsJ !!});

    var users = '@php echo json_encode($userArr) @endphp';

    var  assetId = '{{$assetId}}';
    var showId = '{{$showId}}';

    $('#timeFrom, #timeTo').change(function () {
        $(".ReasonCon").removeClass('hide');
        $(".reason").attr('required',true);
        if (new Date($('#timeFrom').val()) >= new Date($('#timeTo').val())) {

            $("#myDiv").html('Start Time must be less then the end Time');
        }
        else {

            $("#myDiv").html('');
        }
    });

    var calId='{{$calendar->getId()}}';

    if('{{$dateFrom}}'!='') {
        $('#calendar-' + calId).fullCalendar('gotoDate', '{{$dateFrom}}');
    }
    var id="{{session('feedBackAssciated')}}";

    $("#event_asset_id").val("{{session('assetId')}}");
    $("#asset_id").val("{{session('assetId')}}");


    function addDeleteButtonOnEvents() {
        var $fcEvent = $(document).find('.fc-event');
        $fcEvent.each(function () {
            var $fcEvent = $(this);
            var splittedHREF = $fcEvent.attr('href').split('/');

            // GET SECOND LAST ITEM FROM ARRAY
            var eventId = splittedHREF.slice(-2, -1)[0];

            $(this).find('.fc-event-inner').append(
                '<div id="fc-event-delete-button-' + eventId + '" title="Delete" rel="tooltip" class="fc-event-delete-button"></div>'
            );
        });

        // Assign fancy tooltip to delete button recently added to the  DOM
        $(document).find('.fc-event-delete-button').tooltip();
    }


</script>

<script type="text/javascript" src="{{ asset('/js/masterScheduler.js') }}"></script>
<style>

    /*.master .col-sm-3 .bootstrap-select:not([class*="col-"]):not([class*="form-control"]):not(.input-group-btn)*/
    /*{ width: 180px!important;}*/
    /*.master .col-sm-2 .bootstrap-select:not([class*="col-"]):not([class*="form-control"]):not(.input-group-btn)*/
    /*{ width: 125px!important;}*/
    /*.col-sm-4 .bootstrap-select:not([class*="col-"]):not([class*="form-control"]):not(.input-group-btn)*/
    /*{ width: 157px!important;}*/
    /*.col-sm-3 .bootstrap-select:not([class*="col-"]):not([class*="form-control"]):not(.input-group-btn)*/
    /*{ width: 120px!important;}*/
</style>