@extends('emails.template-plain')

@section('content')
Hi,
Thank you for signing up on {{ env('SITE_NAME') }}.
To complete your account activation click the link below:

{{ url('/activate/'.$code) }}

If the link above does not work, copy and paste it into your browser's address bar.
@endsection
