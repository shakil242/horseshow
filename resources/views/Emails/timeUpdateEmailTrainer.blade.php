<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        .table-bordered tr{
            border-bottom: 1px solid #efefef;
        }
        .table-bordered td{
            padding: 8px;
            line-height: 1.42857143;
            vertical-align: top;
            border: 1px solid #dddddd;
        }
    </style>
</head>
<body style="margin:0;padding:0;">
<div style="max-width:600px;margin:0 auto;padding-left:10px;padding-right:10px;">
    <table cellspacing="0" cellpadding="0" border="0" style="width:100%;">



        <tr>
            <td style="height:10px;background:#001E46;"></td>
        </tr>
        <tr>
            <td>
                <table cellspacing="0" cellpadding="0" border="0" style="width:100%;">
                    <tr>
                        <td style="padding-top:20px;padding-bottom:20px;border-bottom:1px solid #e1e1e1;">
                            <a href="#"><img style="border:0;line-height:0;width:140px;" src="{{asset('adminstyle/images/logo.svg') }}" /></a>
                        </td>
                        <td style="padding-top:20px;padding-bottom:20px;border-bottom:1px solid #e1e1e1;text-align:right;">
                            <p style="margin:0;font-size:14px; color:#979797;font-family:Arial, sans, serif;">{{ Carbon\Carbon::parse(Carbon\Carbon::now())->format('m-d-Y') }}</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr><td><h3 style="text-align: center">{{$showTitle}}</h3></td></tr>

        <tr>
            <td style="padding-top:20px;">
                <p style="color:#6a6a6a;font-size:16px;line-height:14px;font-family:Arial, sans, serif;">Hi <strong style="color:#001E46;font-size:16px;line-height:16px;font-family:Arial, sans, serif;text-transform:capitalize">{{ $trainer }},</strong></p>
                <p style="color:#6a6a6a;font-size:16px;line-height:14px;font-family:Arial, sans, serif;">Hoping you are doing well today.</p>
            </td>
        </tr>
        <tr>
            <td style="padding-top:10px;padding-bottom:20px;">

                <p style="color:#6a6a6a;font-size:16px;line-height:18px;font-family:Arial, sans, serif;">Class time has been rescheduled by {{$reminderMinutes}} Minutes  for {{$horse_title}}.</p>

                <table class="table-bordered" style="width: 100%;border: 1px solid #efefef;border-collapse: collapse;border-spacing: 0;">
                    <tr>
                        <th colspan="3" style="text-align: center;padding:10px 0px;">Previous Time</th>
                    </tr>
                    <tr>
                        <td>Time From</td>
                        <td>Time To</td>
                        <td>Class</td>
                    </tr>
                    <tr>
                        <td>{{ Carbon\Carbon::parse($time_slot[0])->format('m-d-Y g:i A') }}</td>
                        <td>{{ Carbon\Carbon::parse($time_slot[1])->format('m-d-Y g:i A') }}</td>
                        <td>{{$asset_title}}</td>
                    </tr>
                    <tr><th colspan="3"  style="text-align: center; padding:10px 0px;">Updated Time</th></tr>

                    <tr>
                        <td>Time From</td>
                        <td>Time To</td>
                        <td>Class</td>
                    </tr>
                    <tr>
                        <td>{{ Carbon\Carbon::parse($timeFrom)->format('m-d-Y g:i A') }}</td>
                        <td>{{ Carbon\Carbon::parse($timeTo)->format('m-d-Y g:i A') }}</td>
                        <td>{{$asset_title}}</td>
                    </tr>

                </table>


            </td>
</tr>
        <tr>
            <td style="padding-bottom: 10px;">

                <strong style="color:#6a6a6a;font-size:16px;line-height:18px;font-family:Arial, sans, serif;"> Reason : </strong>
                <span style="color:#6a6a6a;font-size:14px;line-height:18px;font-family:Arial, sans, serif;">{{ $reason  }}</span>

            </td>

        </tr>

    </table>
</div>
</body>
</html>
