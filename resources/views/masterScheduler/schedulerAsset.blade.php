<table class="table primary-table">
<thead class="hidden-xs">
<tr>
    <td>

    <div class="form-group" style="margin-top: 15px;">
        <div class="col-sm-2"> <label style="padding-top:5px">Select Class</label> </div>
        <div class="col-sm-5">

            <select  class="selectpicker form-control" onchange="getCalendar($(this).val(),'{{nxb_encode($variables['templateId'])}}','{{nxb_encode($variables['formId'])}}')" name="asset" data-style="btn-danger" data-live-search="true">
                    @foreach($Assets as $index =>$asset)
                        <option value="{{nxb_encode($asset)}}" @if(old("asset") != null)
                            {{ (in_array($asset, old("asset")) ? "selected":"") }}
                                @endif> {{ GetAssetNamefromId($asset) }}
                        </option>
                    @endforeach
            </select>

        </div>
        </div>
    </div>
    </td>

</tr>
</thead>

    <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />


    {{--these hidden fields are for realTime scheduler--}}

    <input type="hidden" name="templateId" id="templateId" value="" />
    <input type="hidden" name="assetId" id="assetId" value="" />
    <input type="hidden" name="formId" id="formId" value="" />

</table>


<style>

    .radio-inline, .checkbox-inline
    { line-height: 28px;}
</style>

@section('footer-scripts')

    <script>
        var calId='{{$variables['calId']}}';
        var assetId='{{nxb_encode($variables['assetId'])}}';

        $('.selectpicker').selectpicker();
        $('select[name=asset]').val(1);
        $('.selectpicker').selectpicker('refresh')
        </script>

    <script src="{{ asset('/js/vender/moment.min.js') }}"></script>
    <script src="{{ asset('/js/vender/fullcalendar.min.js') }}"></script>
    <script src='{{ asset('/js/vender/home.js?3.3.1-1.6.1-3') }}'></script>
    <link rel="stylesheet" href="{{ asset('/css/vender/fullcalendar.min.css') }}"/>
    <script src="{{ asset('/js/vender/jquery-ui.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('/css/vender/jquery-ui.css') }}" />
    <script type="text/javascript" src="{{ asset('/js/vender/bootstrap-tooltip.js') }}"></script>

    <link href="{{ asset('/adminstyle/css/vender/bootstrap-select.css') }}" rel="stylesheet">

@endsection