<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<style>
    .page-break {
        page-break-after: always;
    }

    table{ border: solid 1px #cdcdcd}
    td{ border: solid 1px #cdcdcd; text-align: center;padding: 10px;}
    th{ border: solid 1px #cdcdcd; text-align: center; padding: 10px;}

</style>


<html>
<head>


</head>
<body>




<table  width="100%" border="0" cellpadding="0" cellspacing="0" style="margin:0 auto;">

    <tr><td colspan="2"><h2>{{$templateName}} Responses</h2></td></tr>


    <thead class="hidden-xs">
    <tr>
        <th>Name</th>
        <th>Values</th>
    </tr>
    <tbody>

    @if(sizeof($arr)>0)
        @foreach($arr as $key=>$value)

            <tr>
                <td>{{ $key }}</td>
                <td>{{ $value }}</td>
               </tr>
        @endforeach
    @endif
    </tbody>



</table>
</body>


</html>

