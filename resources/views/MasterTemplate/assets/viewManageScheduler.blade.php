@extends('layouts.equetica2')

@section('custom-htmlheader')
    @include('layouts.partials.form-header')
@endsection

@section('main-content')
    <!-- ================= CONTENT AREA ================== -->

    <div class="main-contents">

    <div class="container-fluid">

    @php
        $title = GetAssetNamefromId($id);
        $added_subtitle = Breadcrumbs::render('master-template-assets-secondary',['asset_id'=>$id,'template_id'=>$template_id]);
        $templateType = GetTemplateType($template_id);

    @endphp
    @include('layouts.partials.pagemenu',['added_title'=>$title,'added_subtitle'=>$added_subtitle])

    <!-- Content Panel -->
        <div class="white-board">
    <div class="row">
        <div class="col-sm-12">
        @if($templateType==TRAINER)
        <a style="text-align: center;" href="{{URL::to('master-template') }}/{{nxb_encode($template_id)}}/{{nxb_encode($app_id)}}/list/schedular/1" class="btn btn-success">Create Scheduler</a>
        @endif
        @if(!$subAsset->count())
                <div class="row">
                    <div class="col-md-12">{{NO_PARTICIPANT_RESPONSE}}</div>
                </div>
            @else
                <div class="row">
                    <div class="table-responsive module-holer rr-datatable">
                        <table id="crudTable2" class="table table-line-braker mt-10 custom-responsive-md">
                            <thead class="hidden-xs">
                            <tr>
                                <th scope="col">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input data-target="commulativeInvoice"  class="form-check-input allCheck"   id="legendCheck1" type="checkbox">
                                            <span>#</span>
                                        </label>
                                    </div></th>
                                <th scope="col">Asset Name</th>

                                @if($templateType==TRAINER)
                                <th scope="col">Scheduler Name</th>
                                @endif

                                <th scope="col">Date Time From</th>
                                <th scope="col">Date Time To</th>
                                <th class="action">Action
                                    <div class="commulativeInvoicess">
                                        <input type="button"  onclick="getSchedualTime('all')" class="btn btn-success" value="Update All Scheduler">
                                    </div>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($templateType==TRAINER)
                                @if(sizeof($subAsset)>0)
                                @foreach($subAsset as $p)
                                    @php
                                    $serial = $loop->index + 1;
                                    $roleName = '';
                                    $timeFrom = '';
                                    $timeTo = '';
                                    $title ="Add Schedule";
                                    $data =  getSchedulers($p->parent_id,$p->asset_id);
                                    if($data->count()>0){
                                    foreach($data as $pResponse)
                                    {
                                    $time =  getScheulderTime($pResponse->asset_id,$pResponse->show_id);
                                    $title ="Edit Schedule";
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input value="{{$pResponse->asset_id}}" data-target="commulativeInvoice"  class="form-check-input singleCheck"   name="MultiScheduler[]" type="checkbox">
                                                    <span>&nbsp</span>
                                                </label>
                                            </div>
                                          </td>
                                        <td><span class="table-title">Asset Name</span>{{ GetAssetNamefromId($pResponse->asset_id) }}</td>
                                        <td><span class="table-title">Show Name</span>{{ getShowName($pResponse->show_id) }}</td>
                                        <td><span class="table-title">Date Time From</span><?php echo $time['timeFrom']; ?>
                                        </td>
                                        <td><span class="table-title">Date Time To</span><?php echo $time['timeTo']; ?></td>
                                        <td class="action">

                                        <a href="javascript:" onclick="getSchedualTime('{{$pResponse->asset_id}}','{{$id}}','{{$form_id}}','{{$pResponse->show_id}}')" data-id="{{$pResponse->asset_id}}">{{$title}}</a>
                                        <a href="{{URL::to('master-template')}}/{{nxb_encode($pResponse->asset_id)}}/history/assets"><i data-toggle='tooltip' data-placement='top'
                                                                                                                                        data-title='View History' class='fa fa-eye' aria-hidden='true'></i></a></td>
                                    </tr>
                           @php
                              }
                             }
                            else{
                           @endphp
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input value="{{$p->asset_id}}" data-target="commulativeInvoice"  class="form-check-input singleCheck"   name="MultiScheduler[]" type="checkbox">
                                            <span>&nbsp</span>
                                        </label>
                                    </div>
                                </td>
                                <td><span class="table-title">Asset Name</span>{{ GetAssetNamefromId($p->asset_id) }}</td>
                                <td><span class="table-title">Show Name</span></td>

                                <td><span class="table-title">Date Time From</span>
                                </td>
                                <td><span class="table-title">Date Time To</span></td>
                                <td class="action">

                                    <a href="javascript:" onclick="getSchedualTime('{{$p->asset_id}}','{{$id}}','{{$form_id}}',false)" data-id="{{$p->asset_id}}">{{$title}}</a>
                                    <a href="{{URL::to('master-template')}}/{{nxb_encode($p->asset_id)}}/history/assets"><i data-toggle='tooltip' data-placement='top'
                                                                                                                                    data-title='View History' class='fa fa-eye' aria-hidden='true'></i></a></td>
                            </tr>
                            <?php } ?>

                                @endforeach
                            @endif
                            @else

                            @if(sizeof($subAsset)>0)
                                @foreach($subAsset as $pResponse)
                                    <?php
                                    $serial = $loop->index + 1;
                                    $roleName = '';
                                    $timeFrom = '';
                                    $timeTo = '';


                                    if($pResponse->assetsScheduler->count()>0)
                                    {
                                        foreach ($pResponse->assetsScheduler as $row)
                                        {

                                            $restrcition = $row->restriction;
                                            $times = explode(' - ',$restrcition);
                                            $timeFrom .=  $times[0].'<br>';
                                            $timeTo .=  $times[1].'<br/>';
                                        }

                                        $title ="Edit Schedule";
                                    }else
                                    {
                                        $title ="Add Schedule";
                                    }
                                    ?>

                                    {{--{{ $serial }}--}}

                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input value="{{$pResponse->asset_id}}" data-target="commulativeInvoice"  class="form-check-input singleCheck"   name="MultiScheduler[]" type="checkbox">
                                                    <span>&nbsp</span>
                                                </label>
                                            </div>
                                        </td>
                                        <td><span class="table-title">Asset Name</span>{{ GetAssetNamefromId($pResponse->asset_id) }}</td>
                                        <td><span class="table-title">Date Time From</span><?php echo $timeFrom; ?>
                                        </td>
                                        <td><span class="table-title">Date Time To</span><?php echo $timeTo; ?></td>
                                        <td class="action">

                                            <a href="javascript:" onclick="getSchedualTime('{{$pResponse->asset_id}}','{{$id}}','{{$form_id}}')" data-id="{{$pResponse->asset_id}}">{{$title}}</a>
                                            <a href="{{URL::to('master-template')}}/{{nxb_encode($pResponse->asset_id)}}/history/assets"><i data-toggle='tooltip' data-placement='top'
                                                                                                                                            data-title='View History' class='fa fa-eye' aria-hidden='true'></i></a></td>
                                    </tr>
                                @endforeach
                            @endif
                            @endif

                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>

        </div>
    </div>
    <!-- Tab containing all the data tables -->
        <div id="schedulaTime" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="schedulaTime">
            <div class="modal-dialog modal-lg" role="document" >
                <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title" id="modalLabel">Schedual Date Time<span class="assetTitle"></span> </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>

                    {!! Form::open(['url'=>'master-template/assets/updateScheduleTime','method'=>'post','id'=>'updateSchedual']) !!}

                    <input type="hidden" name="parentAsset" value="{{$id}}">

                    <input type="hidden" name="form_id" value="{{$form_id}}">

                    <input type="hidden" name="template_id" value="{{$template_id}}">

                    <input type="hidden" id="asset_id" name="asset_id" >

                    <div class="modal-body ml-20">

                        <div class="row">
                            <div class="info">
                                <p style="display: none" class="addNotesMessage text-center alert alert-success"></p>
                            </div>
                        </div>

                        <div class="invite-wrapper">
                            <div class="invite-holder">
                                <div class="col-md-12">
                                    <div class="row master">
                                    @if($templateType==TRAINER)
                                    <div class="col-sm-12 mb-20">
                                    <div class="row">
                                    <div class="col-md-2">
                                    <label class="mt-5 pl-5">Select Scheduler</label>
                                    </div>
                                    <div class="col-md-10">
                                    <select required name="show_id" class="selectpicker">
                                    @foreach($manageShows as $show)
                                    <option value="{{$show->id}}">{{$show->title}}</option>
                                    @endforeach
                                    </select>
                                    </div>
                                    </div>
                                    </div>
                                    @endif
                                        <div class="TimeShceduler">
                                            <div class="col-sm-12 remove">

                                            <div class="row second ml-20">
                                                <a class="pull-right" href="javascript:" onclick="removeSlot(this)"> <i aria-hidden="true" class="fa fa-trash-o"></i></a>
                                                <div class="row ml-20">
                                                    <div class="col-sm-10">
                                                        <label>Date(s)</label>

                                                        <div class="form-group">
                                                            <input class="form-control multiDatePicker" type="text" name="date[]">
                                                            <span style="font-size:12px;padding:0px 0px 15px 0px;color: #651e1c; float: left;">Note: Select all dates on calendar that apply to the time you want the service offered</span>

                                                        </div>
                                                    </div>


                                                    <div class="col-sm-5">

                                                        <label>Time From</label>
                                                        <div class="form-group ClassHorse">
                                                            <input class="form-control timePicker" type="text" name="timeFrom[]">

                                                        </div>
                                                    </div>
                                                    <div class="col-sm-5">

                                                        <label>Time To</label>
                                                        <div class="form-group ClassHorse">
                                                            <input class="form-control timePicker" type="text" name="timeTo[]">

                                                        </div>
                                                    </div>
                                                </div>

                                                    <div class="col-sm-12">
                                                    <div class="form-check">
                                                        <label class="form-check-label">
                                                            <input value="1"  class="form-check-input"   name="is_multiple[0]" type="checkbox">
                                                            <span>Group Class</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                    </div>

                                </div>
                                <div class="col-sm-2 mb-20">
                                    <a  class="addRow btn-success btn"> Add More</a>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />

                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>


                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>

    </div>
@endsection

@section('footer-scripts')
    {{--<link href="{{ asset('/css/vender/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" />--}}
    {{--<script src="{{ asset('/js/vender/moment.min.js') }}"></script>--}}

    <script src="{{ asset('/js/ajax-dynamic-assets-table.js') }}"></script>
    @include('admin.layouts.partials.datatable')
    {{--<script type="text/javascript" src="{{ asset('/js/vender/bootstrap-datetimepicker.min.js') }}"></script>--}}


    <link href="{{ asset('/js/multiDatePickker/jquery.timepicker.css') }}" rel="stylesheet" />
    <script src="{{ asset('/js/multiDatePickker/jquery.timepicker.js') }}"></script>


    <link href="{{ asset('/js/multiDatePickker/jquery-ui.multidatespicker.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="{{ asset('/js/multiDatePickker/jquery-ui.min.js') }}"></script>


    <script src="{{ asset('/js/multiDatePickker/jquery-ui.multidatespicker.js') }}"></script>

    <script src="{{ asset('/js/assetSchedule.js') }}"></script>


    <script>

        $( document ).on( 'click', '.addRow', function () {

            var html = '';
            html =  $(this).parent().prev().find('.second');
            var c = html.length;

            var tmp = $(".TimeShceduler").append('<div class="col-sm-12 remove"><div class="row second ml-20" xmlns="http://www.w3.org/1999/html">\
            <div class="col-sm-10">\
            <label>Date(s)</label>\
             <div class="form-group">\
            <input class="form-control multiDatePicker_'+c+'" type="text" value="" name="date[]">\
            <span style="font-size:12px;padding:0px 0px 15px 0px;color: #651e1c; float: left;">Note: Select all dates on calendar that apply to the time you want the service offered</span>\
            </div>\
            </div>\
            <div class="col-md-2"> \
            <a href="javascript:" style="float: right; margin-top: -7px;" onclick="removeSlot(this)"> \
            <i aria-hidden="true" class="fa fa-trash-o"></i></a>\
            </div>\
            <div class="col-sm-5">\
            <label>Time From</label>\
            <div class="form-group ClassHorse">\
            <input class="form-control timePicker" type="text" name="timeFrom[]">\
            </div></div>\
            <div class="col-sm-5">\
            <label>Time To</label>\
        <div class="form-group ClassHorse">\
            <input class="form-control timePicker" type="text" name="timeTo[]">\
            </div></div>\
            <div class="col-sm-7 form-check">\
            <label class="form-check-label">\
            <input class="form-check-input" type="checkbox" value="1" name="is_multiple['+c+']">\
            <span>Group Class</span></label></div>\
            </div></div>');


            $('.multiDatePicker_'+c).multiDatesPicker();

            $('.timePicker').timepicker({
                'step': 15,
                'minTime': '7:00 AM',
                'maxTime': '9:00 PM'
            });

        });




</script>


    <style>

        .remove:first-of-type a {
            display: none;
        }

        .row.second {
            border: solid 1px #cdcdcd;
            margin-bottom: 20px;
            padding: 10px;
        }
    </style>
@endsection
