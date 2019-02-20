<div class="row"><div class="col-sm-12">
{!! Form::open(['url'=>'/shows/addAjax/trainer/','method'=>'post','id'=>'SaveUsers']) !!}
<input type="hidden" name="msr_id" class="msr_id" value="{{$MSR_id}}">
	<select multiple name="trainer" class="selectpicker show-tick form-control" title="Select trainer" id="allAssets" data-live-search="true" data-max-options="1" required>
        @if($trainers->count() > 0)
            @foreach ($trainers as $trainer) 
                <option value='{{$trainer->id}}'> {{$trainer->user->name}}</option>;
            @endforeach
		@else 
		<option value=''>No Trainer added</option>
		@endif
    </select>
<div><input type="submit" class="btn btn-primary" value="Save"></div>
{!! Form::close()!!}
</div>
</div>       