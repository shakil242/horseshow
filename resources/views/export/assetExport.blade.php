<html>

{{--<head>--}}
    {{--<style>--}}
        {{--h2, h4{margin: 0; padding: 0;}--}}
        {{--table {border-collapse: collapse}--}}
        {{--td {width: 14px; height: 16px; font-size: 10px; padding: 1px 4px; vertical-align: middle;}--}}
        {{--.h {font-size: 12px; font-weight: normal;}--}}
        {{--.cntr{text-align: center;}--}}
        {{--.brdr{border: 1px #666 solid;}--}}
        {{--.h_col{height: 24px; font-size: 16px;}--}}
        {{--.h2_col{height: 36px; font-size: 22px;}--}}
    {{--</style>--}}
{{--</head>--}}

<body>

<table>
    <tr>
        <td colspan="17" class="cntr h h2_col">
            {{--Actual Reporting {{ $fd->format('F-Y') }}{{ ($qd == 'defined')?' to '.$td->format('F-Y'):'' }}--}}
        </td>
    </tr>
    <tr>
        <td colspan="17"></td>
    </tr>
    @foreach ($columns as $key => $value)
                <tr>
                    <td  class="cntr h h_col">{{$key}}</td>
                </tr>
            <tr>
                <td class="h brdr" style="width:10px;">Project #</td>
            </tr>
                <tr>
                    <td class="brdr">
                       new Data
                        {{--{{ $month['format'] }}--}}
                        {{--@if (!is_null($spend->project_number) && $spend->project_number != '')--}}
                            {{--{{ $spend->project_number }}--}}
                        {{--@endif--}}
                    </td>
                    <td class="brdr">
                    test
                    </td>
                </tr>
            @endforeach


</table>

</body>

</html>