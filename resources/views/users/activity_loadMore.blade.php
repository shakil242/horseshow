@if(isset($appCollection))
@if($appCollection->count()>0)
<div class="col-md-3">
    <ul class="nav flex-column">
@foreach($appCollection as $row)
    <li class="nav-item">
        <a class="nav-link" onclick="getActivityView('{{$row->id}}','1','{{$row->asset_id}}')" href="javascript:">

            {{GetAssetNamefromId($row->asset_id)}}

            @if($row->hastemplate->category==SHOW)

                @if(isset($row->show))
                <small>{{$row->show->title}}</small>
                @endif

                @if(isset($row->showRegistration))
                    <small>{{$row->showRegistration->created_at->format('m-d-Y')}}</small>
                @endif
            @else
                <small>{{$row->hastemplate->name}}</small>
            @endif

        </a>
        <a class="setting" href="#"><i class="fa fa-gear"></i></a>
    </li>
    @if($loop->iteration % 5 ==0 && $loop->iteration<20)
    </ul>
    </div>
    <div class="col-md-3">
        <ul class="nav flex-column">
    @endif
@endforeach
        </ul>
    </div>
</div>
<div class="col-md-12">
    <div class="d-flex justify-content-center" id="remove-row">
        <button id="btn-more"   data-id="{{$row->id}}"   class="btn btn-rounded btn-secondary btn-sm"> Load More </button>
    </div>
</div>
    @endif
@endif