<link href="{{ asset('/css/vender/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" />
<script src="{{ asset('/js/vender/moment.min.js') }}"></script>
{{--<script type="text/javascript" src="{{ asset('/js/vender/bootstrap-datetimepicker.min.js') }}"></script>--}}

<script src="{{ asset('/js/assetSchedule.js') }}"></script>

<link href="{{ asset('/js/multiDatePickker/jquery.timepicker.css') }}" rel="stylesheet" />
<script src="{{ asset('/js/multiDatePickker/jquery.timepicker.js') }}"></script>


<link href="{{ asset('/js/multiDatePickker/jquery-ui.multidatespicker.css') }}" rel="stylesheet" />
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="{{ asset('/js/multiDatePickker/jquery-ui.min.js') }}"></script>


<script src="{{ asset('/js/multiDatePickker/jquery-ui.multidatespicker.js') }}"></script>


@if($assetScheduler->count()>0)
    @foreach($assetScheduler as $row)
    @php
    $serial = $loop->index + 1;
    $serial = $serial-1;
    @endphp

@if($row->scheduler_date!='')
  <script>
      var dates = '{{$row->scheduler_date}}'.split(',');
    $('.multiDatePicker_{{$row->id}}').multiDatesPicker({
    addDates:dates
    });


    </script>
    @else
    <script>
        $('.multiDatePicker_{{$row->id}}').multiDatesPicker();
    </script>


@endif
   <div class="row second">

        <a href="javascript:" style="float: right; margin-top: -7px;" onclick="removeSlot(this)"> <i aria-hidden="true" class="fa fa-trash-o"></i></a>

<div class="col-sm-10">
    <label>Date(s)</label>

    <div class="form-group">
        <input class="form-control multiDatePicker_{{$row->id}}" id="dates_{{$row->id}}"  type="text" value="" name="date[]">
        <span style="font-size:12px;padding:0px 0px 15px 0px;color: #651e1c; float: left;">Note: Select all dates on calendar that apply to the time you want the service offered</span>

    </div>

    </div>


        <div class="col-sm-5">

            <label>Time From</label>
            <div class="form-group ClassHorse">
                <input class="form-control timePicker" type="text" value="{{$row->timeFrom}}" name="timeFrom[]">

            </div>
        </div>
        <div class="col-sm-5">

            <label>Time To</label>
            <div class="form-group ClassHorse">
                <input class="form-control timePicker" type="text" value="{{$row->timeTo}}" name="timeTo[]">

            </div>
        </div>


           <div class="form-check col-sm-8">
               <label class="form-check-label">
                   <input value="1"  class="form-check-input" {{($row->is_multiple_selection==1)?'checked':''}}  name="is_multiple[{{$serial}}]" type="checkbox">
                   <span>Group Class</span>
               </label>

           </div>
    </div>

    @php
    @endphp

    @endforeach
 @else
    <div class="row second">
        <a href="javascript:" onclick="removeSlot(this)" style="float: right; margin-top: -7px;"> <i aria-hidden="true" class="fa fa-trash-o"></i></a>

    <div class="col-sm-10">
        <label>Date(s)</label>

        <div class="form-group">
            <input class="form-control multiDatePicker" value="" type="text"   name="date[]">
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

        <div class="form-check col-sm-8 ml-10">
            <label class="form-check-label">
                <input value="1"  class="form-check-input"   name="is_multiple[0]" type="checkbox">
                <span>Group Class</span>
            </label>

        </div>

    </div>
    @endif



<script>
    $('#schedulaTime').on('hidden.bs.modal', function (e) {
        console.log("Modal hidden");
        $(this).removeData('bs.modal');
    });



    //$('#simpliest-usage').multiDatesPicker('value');
    $('.multiDatePicker').multiDatesPicker();

    $('.timePicker').timepicker({
        'step': 15,
        'minTime': '7:00 AM',
        'maxTime': '9:00 PM'
    });
</script>


<style>

    .second:first-of-type a {
        display: none;
    }

    .remove.second:first-of-type a {
        display: block;
    }

    .row.second {
        border: solid 1px #cdcdcd;
        margin-bottom: 20px;
        padding: 10px;
    }
</style>