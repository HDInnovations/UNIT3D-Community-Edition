<!doctype html>
<html>
<head>
<meta name="viewport" content="width=device-width"/>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<title>{{ $subject }}</title>
@include('emails.blocks.style')
</head>
<body class="">
<table border="0" cellpadding="0" cellspacing="0" class="body">
<tr>
<td>&nbsp;</td>
<td class="container">
<div class="content">
<table class="main">
<tr>
<td class="wrapper">
<table border="0" cellpadding="0" cellspacing="0">
<tr>
<td>
@section('content')
<p>Hi there,</p>
<p>Sometimes you just want to send a simple HTML email with a simple design and clear call to action. This is it.</p>
@include('emails.blocks.call-to-action', ['cta_url'=>url('/'),'cta_text'=>'Call to Action'])
<p>This is a really simple email template. Its sole purpose is to get the recipient to click the button with no distractions.</p>
<p>Good luck! Hope it works.</p>
@show
@section('signature')
<p>Best Regards,</p>
<p>{{ env('SITE_NAME') }} Team.</p>
@show
</td>
</tr>
</table>
</td>
</tr>
</table>
<div class="footer">
<table border="0" cellpadding="0" cellspacing="0">
<tr>
<td class="content-block">
@section('footer')
<a href="{{ url('about/terms') }}">Terms</a> |
<a href="{{ url('about/privacy-policy') }}">Privacy</a> |
<a href="{{ url('email/unsubscribe?email='.$to) }}"><unsubscribe>Unsubscribe</unsubscribe></a>
@show
</td>
</tr>
<tr>
<td>
@section('disclaimer')
This is a service email for <span style="color: #2F90E2; text-decoration: none;">{{ $to }}</span> containing necessary account information.<br>Please do not reply to this message.<br><span class="apple-link"><a href="{{ url('/') }}">{{ env('SITE_NAME') }}</a></span>
@show
</td>
</tr>
</table>
</div>
</div>
</td>
<td> </td>
</tr>
</table>
</body>
</html>