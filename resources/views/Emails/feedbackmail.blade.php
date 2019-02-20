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
            <p style="color:#6a6a6a;font-size:16px;line-height:14px;font-family:Arial, sans, serif;">Hi <strong style="color:#001E46;font-size:16px;line-height:16px;font-family:Arial, sans, serif;text-transform:uppercase">Admin,</strong></p>
            <p style="color:#6a6a6a;font-size:16px;line-height:14px;font-family:Arial, sans, serif;">Hoping you are doing well today.</p>
          </td>
        </tr>
        <tr>
          <td style="padding-top:20px;padding-bottom:20px;">
            <p style="color:#6a6a6a;font-size:16px;line-height:14px;font-family:Arial, sans, serif;"> <strong style="color:#001E46;font-size:16px;line-height:16px;font-family:Arial, sans, serif; text-transform: capitalize">{{ getUserNamefromid($event->user_id) }}</strong> has Submitted a feedback. Below is the feedback details:</p>
          </td>
        </tr>
        <tr>
          <td style="padding-top:5px;padding-bottom:20px;">
            <p style="color:#3c3c3c;font-size:16px;line-height:14px;font-family:Arial, sans, serif;">What do you like about the application?</p>
            <strong style="color:#001E46;font-size:16px;line-height:14px;font-family:Arial, sans, serif;">{{ $questionResponse->qno1}}</strong>
            
            <p style="color:#3c3c3c;font-size:16px;line-height:14px;font-family:Arial, sans, serif;">What do you dislike / didn't like about the application?</p>
            <strong style="color:#001E46;font-size:16px;line-height:14px;font-family:Arial, sans, serif;">{{ $questionResponse->qno2}}</strong>
            
            <p style="color:#3c3c3c;font-size:16px;line-height:14px;font-family:Arial, sans, serif;">Any ideas to improve the application?</p>
            <strong style="color:#001E46;font-size:16px;line-height:14px;font-family:Arial, sans, serif;">{{ $questionResponse->qno3}}</strong>
            
            <p style="color:#3c3c3c;font-size:16px;line-height:14px;font-family:Arial, sans, serif;">Any issues face in the application?</p>
            <strong style="color:#001E46;font-size:16px;line-height:14px;font-family:Arial, sans, serif;">{{ $questionResponse->qno4}}</strong>
            
          </td>
        </tr>
        <tr>
          <td>
            <table cellspacing="0" cellpadding="0" border="0" style="width:100%;">
              <tr>
                <td style="background:#001E46;text-align:center">
                  <p style="color:#6a6a6a;font-size:16px;line-height:14px;font-family:Arial, sans, serif;"></p>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </div>
  </body>
</html>
