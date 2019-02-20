@php
  if(isset($calutionNo)){
    $calEqNo = $calutionNo;
  }else{
    $calEqNo = 1;
  }
@endphp
<div class="row">
        <div class="col-sm-12 operators-holder-div">
          <div class="form-group native-operators-select">
            <div class="col-sm-2"><label>Select Field :</label></div>
              <div class="col-sm-2">
                  <select class="set-operator" name="fields[{{$index}}][form_field_options][{{$calEqNo}}][operator]" Required>
                      <option value="">Select Operator</option>
                      <option value="1" {{ (isset($option['additionfield']) && $option['operator']== 1) ? "selected":"" }}> + </option>
                      <option value="2" {{ (isset($option['additionfield']) && $option['operator']== 2) ? "selected":"" }}> - </option>
                  </select>
              </div>
              <div class="col-sm-5">
              <select class="options-fields-drp" name="fields[{{$index}}][form_field_options][{{$calEqNo}}][additionfield]" Required>
               <option value="">Please select</option>
                @foreach($pre_fields as $N_fields)
                  @if($N_fields['form_field_type'] == OPTION_NUMARIC || $N_fields['form_field_type'] == OPTION_MONETERY || $N_fields['form_field_type'] == OPTION_CALCULATE_TOTAL )
                    <option value="{{$N_fields['unique_id']}}" {{ (isset($option['additionfield']) && $option['additionfield']== $N_fields['unique_id']) ? "selected":"" }}>{{$N_fields['form_name']}}</option>
                  @endif
                @endforeach
              </select>
            </div>
            <div class="col-sm-2">
              <button type="button" class="btn btn-primary btn-xs add-new-numeric-value"><span class="glyphicon glyphicon-plus"></span></button>
              <button type="button" class="btn btn-primary btn-xs delete-new-numeric-value"><span class="glyphicon glyphicon-trash"></span></button>
            </div>

          </div>
        </div>
        </div>