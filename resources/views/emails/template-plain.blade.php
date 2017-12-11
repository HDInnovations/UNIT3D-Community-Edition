@section('content')
Hi there,
Sometimes you just want to send a simple HTML email with a simple design and clear call to action. This is it.

CALL TO ACTION LINK

This is a really simple email template. Its sole purpose is to get the recipient to click the button with no distractions.
Good luck! Hope it works.
@show

@section('signature')
Best Regards,
{{ env('SITE_NAME') }} Team.
@show

@section('footer')
Terms: {{ url('about/terms') }}
Privacy: {{ url('about/privacy-policy') }}
Unsubscribe: {{ url('email/unsubscribe?email='.$to) }}
@show

@section('disclaimer')
This is a service email for "{{ $to }}" containing necessary account information. Please do not reply to this message.
@show