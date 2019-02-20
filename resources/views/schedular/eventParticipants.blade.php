<link rel="stylesheet" href="{{ asset('/css/vender/fullcalendar.min.css') }}"/>

<div class="allCon">

    <div class="row" style="margin-bottom: 10px;">

    <div class="col-sm-3 markDone" style="display: none"><input id="markDoneAll" type="button" value="Mark Done" class="btn btn-success"> </div>

    {{--<div class="col-sm-3"> <h2>Class</h2></div>--}}
    {{--<div class="col-sm-6"> <h3>{{GetAssetNamefromId($asset_id)}}</h3>--}}
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="info">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
            </div>
        </div>
    </div>




<table id="crudTable2" class="table primary-table">
    <div class="loader" style="display: none"></div>

    <thead class="hidden-xs">
    <tr>
        <th> @if($type==2)
                <div class="form-check">
                    <label class="form-check-label">
                        <input  class="checkbox-additional form-check-input" data-attr="additional-charges" type="checkbox" id="checkall">
                        <span>Select All</span>
                    </label>
                </div>
            @else # @endif
        </th>
        <th>Name</th>

        <th>Course Title</th>
        <th>Horse</th>
        <th>Rider</th>
        <th>Profile</th>
        <th>Status</th>
        <th>Actions</th>

    </tr>
    </thead>

@foreach($results as $row)

<?php
    $horseTitle = "No Horse Linked";

    $serial = $loop->index + 1;
        $isSpectator = session('isSpectator');
?>
    <tr>
        <td>
            @if($row->is_mark!=1 && $type==2)

                <div class="form-check">
                    <label class="form-check-label">
                        <input  value="1" name="markDone[{{$row->id}}]"  class="checkbox-additional form-check-input check checkedAll" data-attr="additional-charges" type="checkbox">
                        <span>{{$serial}}</span>
                    </label>
                    <input type="hidden" name="slot_Time" value="{{$slot_time}}"  class="checkedAll"  >
                </div>
            @else {{$serial}}
            @endif
        </td>
        <td>{{$row->user->name}}</td>

            <td>
                {{GetAssetNamefromId($row->asset_id)}}
            </td>
        <td>
            @if(isset($row->horse_id) && $row->horse_id!=0)
            @php     $horse = getHorsesForScheduler($row->horse_id,$row->asset_id); @endphp
        <a class="HorseAsset" target="_blank" href="{{URL::to('master-template') }}/{{nxb_encode($row->horse_id)}}/horseProfile" > {{GetAssetNamefromId($row->horse_id)}} </a>@if($horse) [Entry# {{$horse->horse_reg}}]@endif
            @endif
        </td>
        <td>
            @if($row->horse_id!=0)
                <?php     $horse_rider = getHorsesRiderForScheduler($row->horse_id,$row->asset_id);

                //echo $rider->horse_rider;
                ?>
                <a class="HorseAsset" target="_blank" href="{{URL::to('master-template') }}/{{nxb_encode($horse_rider)}}/horseProfile" > {{GetAssetNamefromId($horse_rider)}} </a>
            @endif
        </td>
        <td>
            @if($row->templateProfile($row->template_id))
            @foreach($row->templateProfile($row->template_id) as $r)
                <div class="col-md-12">
                    <a target="_blank" class="app-action-link"
                       href="{{URL::to('settings') }}/{{nxb_encode($r->form_id)}}/2/{{$row->user_id}}/view">{{$r->forms->name}}</a>
                </div>
            @endforeach
             @endif
        </td>

        <td>@if($row->is_mark==1) Completed @else Pending @endif</td>

        <td>
         @if($type==2)
                <a href="javascript:" onclick=" @if($row->is_mark==1) markDisabled();  @elseif($isSpectator!=null)  hideButtonSpectator(); @else markEnable(); @endif viewDetailInGroup('{{$row->id}}','{{$slot_time}}',1,'{{$type}}')">View Detail</a> </td>
        @else
            @if($user_id == $row->user_id)
            <a href="javascript:" onclick=" @if($row->is_mark==1) markDisabled(); @else markEnable();  @endif viewDetailInEvent('{{$row->id}}','{{$slot_time}}',1,'{{$type}}','{{$row->horse_id}}')">View Detail</a> </td>
            @endif
        @endif


    </tr>
@endforeach



</table>

</div>
<style>
    .modal-backdrop {
        visibility: hidden !important;
    }
    .modal.in {
        background-color: rgba(0,0,0,0.5);
    }
    .loader {
        border: 16px solid #f3f3f3; /* Light grey */
        border-top: 16px solid #3498db; /* Blue */
        border-radius: 50%;
        width: 120px;
        height: 120px;
        animation: spin 2s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

</style>
<script>
    var trainer = '{{$trainer}}';

    $('#checkall').change(function() {
        if($(this).prop('checked')==true)
            $(".markDone").show();
        else
            $(".markDone").hide();

        $("input.check").prop('checked', $(this).prop('checked'));

    });


    $('.checkedAll').change(function() {
       if($(this).prop('checked')==true)
        $(".markDone").show();
    });

    $("#markDoneAll").unbind("click").click(function (e) {
        e.preventDefault();

        if(trainer==4)
        var url = '/master-template/schedular/markDoneAllGroups';
        else
            var url = '/master-template/schedular/markDoneAll';

        $.ajax({
            url: url,
            type: "POST",
            beforeSend: function (xhr) {
                var token = $('#csrf-token').val();
                if (token) {
                    return xhr.setRequestHeader('X-CSRF-TOKEN', token);
                }
            },
            data: $(".checkedAll").serialize(),
            success: function (data) {
              //  $("#eventsUsers").modal('hide');
               $(".allCon").html(data);

            }
        });
    });

</script>