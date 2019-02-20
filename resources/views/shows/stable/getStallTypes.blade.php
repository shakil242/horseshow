@foreach($collection as $row)
    <?php $serial = $loop->index + 1;
    $arr[]=$row->is_utility;
    ?>

    @if($row->is_utility==0)
    <div class="row types mb-30" >
    <div class="col-sm-5">
        <input type="text" class="form-control" value="{{$row->stall_type}}" name="stallTypes[{{$row->id}}][stall_type]" placeholder="Type 1" />
    </div>
    <div class="col-sm-5">
        <input type="text" class="form-control" value="{{$row->price}}" name="stallTypes[{{$row->id}}][price]" placeholder="$ Price" />
    </div>
        <input type="hidden" name="stallTypes[{{$row->id}}][is_update]" value="{{$row->id}}">

    @if($serial==1)
        <div class="col-sm-1">
            <button type="button" class="btn btn-default addStallType"><i class="fa fa-plus"></i></button>
        </div>
        @else
        <div class="col-sm-1">
            <button type="button" onclick="removeFileds($(this),'{{$row->id}}')" class="btn btn-default removeButton"><i class="fa fa-minus"></i></button>
        </div>
        @endif
</div>
@else
        <div class="row hide mb-30" id="bookTemplate">
            <div class="col-sm-5">
                <input type="text" class="form-control" name="typ" placeholder="Type" />
            </div>
            <div class="col-sm-5">
                <input type="text" class="form-control" name="prc" placeholder="Price" />
            </div>
            <div class="col-sm-1">
                <button type="button" onclick="removeFileds($(this))" class="btn btn-default removeButton"><i class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class="row types" >
            <div class="col-sm-5">
                <input type="text" class="form-control" value="{{$row->stall_type}}" name="stallTypes[{{$row->id}}][utility_type]" placeholder="Utility Type" />
            </div>
            <div class="col-sm-5">
                <input type="text" class="form-control" value="{{$row->price}}" name="stallTypes[{{$row->id}}][utility_price]" placeholder="$ Utility Price" />
            </div>
        </div>

        <input type="hidden" name="stallTypes[{{$row->id}}][is_update]" value="{{$row->id}}">

    @endif

@endforeach

@if(!in_array('1',$arr))

    <div class="row hide mb-30" id="bookTemplate">
        <div class="col-sm-5">
            <input type="text" class="form-control" name="typ" placeholder="Type" />
        </div>
        <div class="col-sm-5">
            <input type="text" class="form-control" name="prc" placeholder="Price" />
        </div>
        <div class="col-sm-1">
            <button type="button" onclick="removeFileds($(this))" class="btn btn-default removeButton"><i class="fa fa-minus"></i></button>
        </div>
    </div>
    <div class="row types" >
        <div class="col-sm-5">
            <input type="text" class="form-control" value="" name="stallTypes[100][utility_type]" placeholder="Utility Type" />
        </div>
        <div class="col-sm-5">
            <input type="text" class="form-control" value="" name="stallTypes[100][utility_price]" placeholder="$ Utility Price" />
        </div>
    </div>


@endif
<script>


    $(document).ready(function() {
        addStallType = 0;
        // Add button click handler

        $('.addStallType').on('click',function() {

            addStallType++;
            var totalRows = $("#bookForm").find(".types").length;


            var $template = $('#bookTemplate'),
                $clone    = $template
                    .clone()
                    .removeClass('hide')
                    .addClass('types')
                    .removeAttr('id')
                    .attr('data-book-index', addStallType)
                    .insertBefore($template);

            // Update the name attributes
            $clone.find('[name="typ"]').attr('placeholder', 'Type ' + parseInt(totalRows+1));
            $clone.find('[name="prc"]').attr('placeholder', '$ Price');
            $clone.find('[name="typ"]').attr('name', 'stallTypes['+parseInt(totalRows+1)+'][stall_type]');
            $clone.find('[name="prc"]').attr('name', 'stallTypes['+parseInt(totalRows+1)+'][price]');
        });



    });

</script>



