@extends('emails.template')

@section('content')
<p>{{ trans('email.hi') }},</p>
<p>{{ trans('email.register-thanks') }} {{ env('SITE_NAME') }}.</p>
<p>{{ trans('email.act-code-below') }}:</p>
@include('emails.blocks.call-to-action', ['cta_url'=>url('/activate/'.$code),'cta_text'=>trans('email.activate-account')])
<p>{{ trans('email.not-working-url') }}.</p>
<p style="word-wrap: break-word; overflow-wrap: break-word; word-break: break-word;">{{ url('/activate/'.$code) }}</p>
@endsection
