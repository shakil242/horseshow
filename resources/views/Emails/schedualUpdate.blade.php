<!DOCTYPE html>
<html lang="en">
<head>
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
        <tr>
            <td style="padding-top:20px;">
                <p style="color:#6a6a6a;font-size:16px;line-height:14px;font-family:Arial, sans, serif;">Hi <strong style="color:#001E46;font-size:16px;line-height:16px;font-family:Arial, sans, serif;text-transform:capitalize">{{ $event['userName'] }},</strong></p>
                <p style="color:#6a6a6a;font-size:16px;line-height:14px;font-family:Arial, sans, serif;">Hoping you are doing well today.</p>
                <h3>{{$event['show_name']}} - {{$event['asset_name']}}</h3>
            </td>
        </tr>
        <tr>
            <td style="padding-top:20px;padding-bottom:20px;">
                <p style="color:#6a6a6a;font-size:16px;line-height:14px;font-family:Arial, sans, serif;"> Schedualer time has been changed to  <strong style="color:#001E46;font-size:16px;line-height:16px;font-family:Arial, sans, serif;">{{ Carbon\Carbon::parse($event['timeSlots'])->format('m-d-Y g:i A') }}
                       </strong>
                     For Class <strong style="color:#001E46;font-size:16px;line-height:16px;font-family:Arial, sans, serif;">{{$event['asset_name']}}</strong> </p>
            </td>
</tr>



    </table>
</div>
</body>
</html>
