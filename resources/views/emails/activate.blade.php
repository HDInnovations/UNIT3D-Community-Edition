@extends('emails.template')

@section('content')
<p>Hi,</p>
<p>Thank you for signing up on {{ env('SITE_NAME') }}.</p>
<p>To complete your <b>account activation</b> click the button below:</p>
@include('emails.blocks.call-to-action', ['cta_url'=>url('/activate/'.$code),'cta_text'=>'Activate Account'])
<p >If the button above does not work, copy and paste the link below into your browser's address bar.</p>
<p style="word-wrap: break-word; overflow-wrap: break-word; word-break: break-word;">{{ url('/activate/'.$code) }}</p>
@endsection
