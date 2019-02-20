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
                  <p style="margin:0;font-size:14px; color:#979797;font-family:Arial, sans, serif;">{{ Carbon\Carbon::parse(Carbon\Carbon::now())->format('d-m-Y') }}</p>
                  <a href="{{URL::to('/') }}" style="color:#001E46;font-size:14px;font-family:Arial, sans, serif;text-decoration:none;"><span style="color:#001E46;font-size:14px;line-height:14px;font-family:Arial, sans, serif;">Link To App</span></a>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr style="color:grey">
          <td>{!! $header !!}</td>
        </tr>

        <tr>
          <td style="padding-top:20px;">
            {!! $body !!}
          </td>
        </tr>
      </table>
    </div>
  </body>
</html>
