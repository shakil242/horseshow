@foreach($collection as $row)
    <?php $serial = $loop->index + 1;?>
    <div class="col-xs-5 mb-30">
        <label>{{$row->stall_type}}</label>
        <input type="text"  class="form-control" numeric name="quantity[{{$row->id}}]"  />
    </div>
@endforeach