    <label class="text-content-dark" for="">Select Classes</label>
    <select multiple name="assets[]" title=" --- Select Classes --- "   class="form-control selectClasses selectpicker form-control-bb-only allAssets" multiple data-size="8" data-selected-text-format="count>6" data-live-search="true" required>
        @if($AllAssets->count()>0)
            @foreach($AllAssets as $asset)
                <option value="{{$asset->id}}" > {{GetAssetName($asset)}}</option>
            @endforeach
        @endif
    </select>
