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
                <p style="color:#6a6a6a;font-size:16px;line-height:14px;font-family:Arial, sans, serif;">Hi <strong style="color:#001E46;font-size:16px;line-height:16px;font-family:Arial, sans, serif;text-transform:capitalize">{{ $user->name }},</strong></p>
                <p style="color:#6a6a6a;font-size:16px;line-height:20px;font-family:Arial, sans, serif;">Hoping you are doing well today.<br>
                    You have the following stall number(s) which have not been paid.<br>
                    Please address the following unpaid stalls.
                </p>
            </td>
        </tr>
        <tr>
            <td style="padding-top:10px;padding-bottom:20px;">

                <table class="table-bordered" style="width: 100%;border: 1px solid #efefef;border-collapse: collapse;border-spacing: 0;">
                    <tr>
                        <td style="color:#001E46;font-size:16px;line-height:16px;font-family:Arial, sans, serif;text-transform:uppercase">Stall Type</td>
                        <td style="color:#001E46;font-size:16px;line-height:16px;font-family:Arial, sans, serif;text-transform:uppercase">Stall Number</td>
                    </tr>
                    @foreach ($collection as $k=>$v)

                    @php
                        if(isset($v))
                            $stall = '#'.implode(' , #',$v);
                        else
                            $stall = '';
                    @endphp

                        <tr><td>{{getStallTypeById($k)}}</td><td>{{$stall}}</td></tr>

                    @endforeach


                </table>

            </td>
        </tr>


    </table>
</div>
</body>
</html>
