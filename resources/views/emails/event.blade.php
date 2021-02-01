<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@300;700&display=swap" rel="stylesheet">
    <title>new event</title>
    <style>
      body{
        font-family: 'Roboto Condensed', sans-serif;
      }
    </style>
  </head>
  <body  margintop="0" marginbottom="0" marginright="0" marginleft="left" marginwidth="0" marginheight="0" offset="0">
    <table width="900" align="center" cellpadding="0" cellspacing="0" border="0">
      <thead>
        <tr>
          <th>
            <img src="{{ asset('assets/images/email/new-event-bg.png' ) }} " alt="new-event-bg" border="0" width="100%">
          </th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td height="40"></td>
        </tr>
        <tr>
          <td style="font-size:26px;color:#444444;text-align:center;font-weight:300"><strong>You are assigned as participant to new event titled </strong></td>
        </tr>
        <tr>
          <td height="30"></td>
        </tr>
        <tr>
          <td style="font-size:26px;color:#444444;text-align:center;font-weight:300">{!! $data['eventname'] !!}</td>
        </tr>
        <tr>
          <td height="40"></td>
        </tr>
        <tr>
          <td align="center"><a href="javascript:void(0)">Start Date : {!! $data['starttime'] !!}</a></td>
        </tr>
        <tr>
          <td height="40">Event Link: {!! $data['event_url'] !!}</td>
        </tr>
        <tr>
          <td style="font-size:18px;color:#444444;text-align:center;">Still Stuck at problems or questions? email <strong> {!! $data['creater_email'] !!}</strong></td>
        </tr>
        <tr>
          <td height="20"></td>
        </tr>
        <tr>
          <td style="font-size:18px;color:#444444;text-align:center;">you receive this email because you or someone created a new event on Ambiplatforms account.</td>
        </tr>
        <tr>
          <td height="20"></td>
        </tr>
        <tr>
          <td style="font-size:18px;color:#444444;text-align:center;font-weight:bold">© 2020 AmbiPlatforms - All right reserved.</td>
        </tr>
        <tr>
          <td height="40"></td>
        </tr>
        <tr>
          <td style="background-color:#323232  ;font-size:18px;color:#fff;text-align:center;padding:5px 0px;">www.ambicamplatfoms.com</td>
        </tr>
        <tr>
          <td height="20"></td>
        </tr>
      </tbody>
    </table>
  </body>
</html>
