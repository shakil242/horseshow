@foreach($stableCollection as $pResponse)
    <?php $serial = $loop->index + 1;
    $show_id = $pResponse->show_id;
    ?>
    <tr>
        <td>{{ $serial }}</td>
        <td><strong class="visible-xs">Stable Name</strong>{{$pResponse->name}}</td>
        <td><strong class="visible-xs">Number Of Stalls</strong>{!! getStallTypes($pResponse->stall_types) !!}</td>
        <td><strong class="visible-xs">Remaining</strong>{!! getRemainingStallTypes($pResponse->id,$pResponse->stall_types) !!}</td>

    </tr>
@endforeach