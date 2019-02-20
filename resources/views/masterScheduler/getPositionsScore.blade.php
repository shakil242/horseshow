@if(isset($placing))
@foreach ($placing->place as $post)

    <div class="col-lg-12 detail-area pb-30">

        <h5 class="text-secondary">
            <img class="pr-5" src="{{asset('img/icons/icon-rank.svg')}} "><strong>{!! getPostionText($post->position) !!}</strong></h5>

        @if($participants != null)
            <div class="col-sm-12 p-0">
                <fieldset class="form-group select-bottom-line-only">
                    <select onchange="checkScore($(this))" name="participants[{{$post->position}}][horse_id]" class="check-select-opt form-control form-control-bb-only form-control-sm">
                        <option value="">Select Horse</option>
                        @foreach($participants as $horses)
                            @if($horses->horse)
                                @if($pos_answers)
                                    <option value="{{$horses->horse_id}}" {{getSelectedValuesMultiple($pos_answers,$post->position,$horses->horse_id)}} >{{GetAssetName($horses->horse). ' [Entry# '.$horses->horse_reg.'] ('. $horses->user->name.')' }}</option>
                                @else
                                    <option value="{{$horses->horse_id}}">{{GetAssetName($horses->horse).' [Entry# '.$horses->horse_reg.'] ('.$horses->user->name.')'}}</option>
                                @endif
                            @endif
                        @endforeach
                    </select>
                </fieldset>
            </div>
        @endif

        {{--@if(isset($pos_answers[$post->position]['scoreFrom']))--}}
            {{--@if(isset($pos_answers))--}}
                {{--<span class="label">TOTAL SCORE: <strong class="pl-5">--}}
                        {{--@if(isset($pos_answers[$post->position]['score']))--}}
                            {{--{{$pos_answers[$post->position]['score']}}</strong>--}}
                    {{--@endif--}}
                {{--</span>--}}
            {{--@endif--}}

            {{--<a class="text-secondary hover-display" href="#"><i class="fa fa-edit"></i></a>--}}
            {{--@foreach($pos_answers[$post->position]['scoreFrom'] as $sc)--}}
                {{--<span class="label">  {{GetAssetNamefromId($sc["class_id"])}} : <strong> {{$sc["ClassScore"]}}</strong></span>--}}
                {{--<div class="form-group form-inline">--}}
                    {{--<label for="first_name" class="col-xs-3 col-form-label pr-2">R#1</label>--}}
                    {{--<div class="col-xs-9">--}}
                        {{--<input type="text" class="form-control form-control-sm form-control-bb-only" id="first_name" name="first_name">--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<input type="hidden" name="participants[{{$post->position}}][scoreFrom][{{$sc["class_id"]}}][class_id]" value="{{$sc["class_id"]}}">--}}
                {{--<input type="hidden" name="participants[{{$post->position}}][scoreFrom][{{$sc["class_id"]}}][ClassScore]" value="{{$sc["ClassScore"]}}">--}}

            {{--@endforeach--}}
        {{--@endif--}}

        @if($horse_rating_type!=1)

            @foreach($restrictions as $k=>$r)
                <div class="form-group form-inline">
                    <label for="first_name" class="col-xs-3 col-form-label pr-2">R#{{$k+1}}</label>
                    <div class="col-xs-9">
                        <input  class="scores form-control form-control-sm form-control-bb-only"  type="number"
                                placeholder="Enter Score" name="participants[{{$post->position}}][rounds][{{$r}}]" @if(isset($pos_answers)) value="{{getScore($pos_answers,$post->position,$r)}}" @endif>
                    </div>
                </div>
                <input type="hidden" name="participants[{{$post->position}}][position]" value="{{$post->position}}">
                <input type="hidden" name="participants[{{$post->position}}][price]" value="{{$post->price}}">
            @endforeach
        @endif

        <input type="hidden" name="restrictions" value="{{json_encode($restrictions)}}">


        @if(isset($pos_answers[$post->position]['scoreFrom']))
            {{--<a class="text-secondary hover-display" href="#"><i class="fa fa-edit"></i></a>--}}
            @foreach($pos_answers[$post->position]['scoreFrom'] as $sc)
                <div class="row" style="color: #001e46;">
                    <div class="col-md-6 pr-0"><strong>{{GetAssetNamefromId($sc["class_id"])}}</strong></div>
                    <div class="col-md-6">  <strong class="pull-right"> {{$sc["ClassScore"]}}</strong></div>
                </div>
                <input type="hidden" name="participants[{{$post->position}}][scoreFrom][{{$sc["class_id"]}}][class_id]" value="{{$sc["class_id"]}}">
                <input type="hidden" name="participants[{{$post->position}}][scoreFrom][{{$sc["class_id"]}}][ClassScore]" value="{{$sc["ClassScore"]}}">

            @endforeach
        @endif

        @if(isset($pos_answers))
            @if(isset($pos_answers[$post->position]['score']))
                <div class="col-sm-12 p-0">
                    <div class="row" style="color: #001e46;">
                        <div class="col-md-6 pr-0"><strong>Total Score</strong></div>
                        <div class="col-md-6">  <strong class="pull-right"> {{$pos_answers[$post->position]['score']}}</strong></div>
                    </div>
                </div>


            @endif
        @endif


    </div>

    @endforeach

@endif
    {{--<tr><td colspan="2" style="background: #cdcdcd;padding-bottom: 0px;"><table class="table" style="table-layout:fixed; margin-bottom: 10px;">--}}

                {{--<tr>--}}
                    {{--<td colspan="2" style="text-align: center"><h2>{{getPostionText($post->position)}}</h2></td>--}}
                {{--</tr><tr>--}}
                    {{--<td colspan="2" style="padding-left:0px; padding-right: 0px; ">--}}

                        {{--@if($participants != null)--}}
                            {{--<select class="selectpickers form-control check-select-opt" onchange="checkScore($(this))" name="participants[{{$post->position}}][horse_id]" data-live-search="true" multiple data-max-options="1" data-style="btn-success" style="color:white">--}}
                                {{--@foreach($participants as $horses)--}}
                                    {{--@if($horses->horse)--}}
                                        {{--@if($pos_answers)--}}
                                            {{--<option value="{{$horses->horse_id}}" {{getSelectedValuesMultiple($pos_answers,$post->position,$horses->horse_id)}} >{{GetAssetName($horses->horse). ' [Entry# '.$horses->horse_reg.'] ('. $horses->user->name.')' }}</option>--}}
                                        {{--@else--}}
                                            {{--<option value="{{$horses->horse_id}}">{{GetAssetName($horses->horse).' [Entry# '.$horses->horse_reg.'] ('.$horses->user->name.')'}}</option>--}}
                                        {{--@endif--}}
                                    {{--@endif--}}
                                {{--@endforeach--}}
                            {{--</select>--}}
                        {{--@endif--}}
                    {{--</td></tr>--}}
                {{--@if(isset($pos_answers[$post->position]['scoreFrom']))--}}
                    {{--<tr style="border-top: none"><td style=" padding-left: 0px;padding-right: 0px;" colspan="2">--}}
                            {{--<table style="border: solid 1px #cdcdcd; width: 100%;">--}}
                                {{--<tr style="border-top: none"><td  style="border-top: none; background:#ededed;padding-top: 10px;padding-bottom: 10px;"><h6 style="text-align: center">Scoring Classes</h6></td></tr>--}}
                                {{--@foreach($pos_answers[$post->position]['scoreFrom'] as $sc)--}}
                                    {{--<tr style="border-top: solid 1px;"><td style="padding: 10px 5px 10px 0px;"><span style="width: 125px; float:left; overflow: inherit"> <strong>{{GetAssetNamefromId($sc["class_id"])}}  </strong>--}}
                                       {{--</span><span style="font-weight: bold; float: right; color: #00C851">{{$sc["ClassScore"]}}</span></td></tr>--}}
                                    {{--<input type="hidden" name="participants[{{$post->position}}][scoreFrom][{{$sc["class_id"]}}][class_id]" value="{{$sc["class_id"]}}">--}}
                                    {{--<input type="hidden" name="participants[{{$post->position}}][scoreFrom][{{$sc["class_id"]}}][ClassScore]" value="{{$sc["ClassScore"]}}">--}}

                                {{--@endforeach--}}

                            {{--</table></td></tr>--}}
                {{--@endif--}}

                {{--@if($horse_rating_type!=1)--}}

                    {{--@foreach($restrictions as $k=>$r)--}}
                        {{--<tr style="border-top: none" >--}}
                            {{--<td colspan="2" style="border-top: none; "><label style="padding-top: 5px;">R#{{$k+1}}</label>--}}
                                {{--<input  class="scores" style="width: 120px; float: right" type="number"--}}
                                        {{--placeholder="Enter Score Round {{$k}}" name="participants[{{$post->position}}][rounds][{{$r}}]" @if(isset($pos_answers)) value="{{getScore($pos_answers,$post->position,$r)}}" @endif>--}}
                            {{--</td>--}}
                        {{--</tr>--}}
                        {{--<input type="hidden" name="participants[{{$post->position}}][position]" value="{{$post->position}}">--}}
                        {{--<input type="hidden" name="participants[{{$post->position}}][price]" value="{{$post->price}}">--}}
                    {{--@endforeach--}}
                {{--@endif--}}

                {{--@if(isset($pos_answers))--}}
                    {{--<tr style="border-top: solid 1px;">--}}
                        {{--<td style="border-top: none; text-align: center" colspan="2"><label>Total Score :</label>--}}
                            {{--@if(isset($pos_answers[$post->position]['score']))--}}
                                {{--<span style="font-weight: bold; color: #00C851;"> {{$pos_answers[$post->position]['score']}}</span>--}}
                            {{--@endif--}}
                        {{--</td>--}}
                    {{--</tr>--}}
                {{--@endif--}}
                {{--<input type="hidden" name="restrictions" value="{{json_encode($restrictions)}}">--}}
            {{--</table></td></tr>--}}
{{--@endforeach--}}
{{--<tr>--}}
    {{--<td colspan="1"><input type="submit" class="btn btn-success btn-medium setPosition" value="Save Position"></td>--}}
{{--</tr>--}}