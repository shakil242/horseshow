
<div id="{{$modalData['id']}}" class="modal fade" role="dialog">
  <div class="modal-dialog">
      {!! Form::open(['url'=>$modalData['url'],'method'=>'post','files'=>true,'class'=>'form-horizontal dropzone targetvalue']) !!}
      <div class="modal-content">
      <div class="modal-header">
          <h4 class="modal-title" id="modalLabel">{{$modalData['title']}}</h4>

          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
          </button>
      </div>
      <div class="modal-body p-20">
                  @if($modalData['theLooper'])
                    <div class="col-md-12">

                     <div class="row p-0">
                    <div class="col-md-8  p-0  text-left">
                       @if(isset($modalData['labeltitle']))
                      <label>
                        {{$modalData['labeltitle']}}
                      </label>
                      @else
                      <label>
                        Select Invoices: 
                      </label>
                      @endif
                      <select multiple name="printHorseInvoice[]" class="selectpicker form-control" multiple data-size="8" data-selected-text-format="count>6" min="1" data-live-search="true" required="required">
                        @foreach($modalData['theLooper'] as $key => $val)
                            @if($val->horse_id)
                           <option value="{{$val->horse_id}}">
                              {{getAssetName($val->horse)}}
                           </option>
                              @endif
                        @endforeach
                      </select>
                      <input type="hidden" name="show_id" value="{{$show_id}}">
                      <input type="hidden" name="status" value="{{$modalData['status']}}">
                      <input type="hidden" name="user_id" value="{{$user_id}}">
                      @php
                        $totalUTPrice = $utilityCount*$utilityPrice;
                      @endphp
                      <input type="hidden" name="total_utility_price" value="{{$totalUTPrice}}">
                      <input type="hidden" name="utility_stall_count" value="{{$utilityCount}}">
                           @if(isset($utility_stalls) && !is_null($utility_stalls))
                      <input type="hidden" name="utility_stalls" value="{{$utility_stalls}}">
                    @endif



                    </div>
                     </div>


                  @else
                      <div class="col-md-8"> No value found.</div>
                  @endif
              <div class="row p-0 pt-10">
                <div class="col-md-12 p-0 text-left">
                   <p> Number of Utility stalls: <label>{{$utilityCount}}</label></p>
                   <p> Total Price for Utility Stalls: ($)<label>{{$totalUTPrice}}</label></p>
                </div>
              </div>
                    </div>
    </div>
      <div class="modal-footer">
              <input type="submit" class="btn btn-primary btn-small" value="Save">

        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
      {!! Form::close() !!}
  </div>
</div>