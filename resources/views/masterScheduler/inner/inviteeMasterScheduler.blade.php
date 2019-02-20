<div class="row master">

    <div class="{{(count($heights)>0?'col-sm-2':'col-sm-3')}}">
        <fieldset class="form-group  userAlreadyCheck">
            <label  class="text-content-dark" for="">Select User</label>
            <select required name="users[]" onchange="getMultipleHorseAssets('{{$showId}}','{{$assetId}}',this,1)" class="selectpicker form-control">
                <option value="">Select User</option>
                @foreach($userArr as $key=>$v)
                    <option value="{{$key}}">{{$v}}</option>
                @endforeach
            </select>
        </fieldset>
    </div>


    <div class="{{(count($heights)>0?'col-sm-2':'col-sm-3')}}">
        <fieldset class="form-group ClassHorse">
            <label  class="text-content-dark" for="">Select Horse</label>
            <select required class="form-control selectpicker">
                <option value="">No Horse Selected</option>
            </select>
        </fieldset>
    </div>


    @if(isset($heights))
        @if(count($heights)>0)
            <div class="col-sm-2">
                <fieldset class="form-group">
                    <label  class="text-content-dark" for="">Select Height</label>
                    <select required name="heights[]"  class="form-control selectpicker mySelect">
                        @foreach($heights as $key=>$v)
                            <option value="{{$v}}">{{$v}}</option>
                        @endforeach
                    </select>
                </fieldset>
            </div>
    @endif
    @endif
    <div class="col-sm-2">
        <fieldset class="form-group select-bottom-line-only">
            <label  class="text-content-dark" for="">Start Time</label>
            <select id="timeFromInvite" name="timeFrom[]"  class="form-control selectpicker">
                <option value="{{$new_date_From}}">{{$new_time_From}}</option>
            </select>
        </fieldset>
    </div>
    <div class="col-sm-2">
        <fieldset class="form-group select-bottom-line-only">
            <label  class="text-content-dark" for="">End Time</label>
            <select id="timeToInvite" name="timeTo[]"  class="form-control selectpicker">
                <option value="{{$new_date_to}}">{{$new_Time_to}}</option>
            </select>
        </fieldset>
    </div>

    <div class="col-sm-2">
        <a href="javascript:" class="addRowMaster mt-30 btn-default btn removeRow">Remove</a>
    </div>

</div>

<script>

    $('.removeRow').on('click', function (e) {
        $(this).parent().parent().remove();
    });

</script>