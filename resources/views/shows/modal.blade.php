
<div id="{{$modalData['id']}}" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4>{{$modalData['title']}}</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
            <div class="" style="width:100%;display:block">
               <div class="row"> 
                  @if($modalData['theLooper'])
                    <div class="col-sm-12">
                    {!! Form::open(['url'=>$modalData['url'],'method'=>'post','files'=>true,'class'=>'form-horizontal dropzone targetvalue']) !!}
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
                           <option value="{{$val->horse_id}}">
                              {{getAssetName($val->horse)}} [entry# {{$val->horse_reg}}]
                           </option>
                        @endforeach
                      </select>
                      <input type="hidden" name="show_id" value="{{$show_id}}">
                      <input type="hidden" name="status" value="{{$modalData['status']}}">
                      <input type="hidden" name="user_id" value="{{$user_id}}">

                    <div class="col-sm-2">
                      <br>
                      <input type="submit" class="btn btn-primary btn-small" value="Print">
                    </div>
                    {!! Form::close() !!}
                    </div>
                  @else
                      <div class="col-md-8"> No value found.</div>
                  @endif
              </div>
            </div>
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>