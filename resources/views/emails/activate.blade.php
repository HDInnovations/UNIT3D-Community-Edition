@extends('emails.template')

@section('content')
<h1>{{ trans('email.register-header') }} {{ config('other.title') }}.</h1>
<p>{{ trans('email.register-code') }}:</p>
@include('emails.blocks.call-to-action', ['cta_url'=>url('/activate/'.$code),'cta_text'=>trans('email.activate-account')])
<p style="word-wrap: break-word; overflow-wrap: break-word; word-break: break-word;">{{ url('/activate/'.$code) }}</p>
<p>{{ trans('email.register-footer') }}.</p>
@endsection
