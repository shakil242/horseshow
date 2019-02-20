@if($assetSecondary->count()>0)

    <select  name="asset[]"  title="Please Select Secondary Asset..." required class="selectpicker form-control" multiple multiple data-size="8" data-selected-text-format="count>6"  id="allAssets"   data-live-search="true">
        <option data-hidden="true"></option>
    @if($assetSecondary->count())
        @foreach($assetSecondary as $option)
                <option value="{{$option->id}}" @if(old("asset") != null) {{ (in_array($option->id, old("asset")) ? "selected":"") }} @endif> {{ GetAssetNameWithType($option) }}</option>
            @endforeach
        @endif
    </select>

    @else
<span>No Secondary asset found</span>
    @endif