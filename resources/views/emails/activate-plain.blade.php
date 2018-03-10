@extends('emails.template-plain')

@section('content')
{{ trans('email.register-header') }} {{ env('SITE_NAME') }}.
{{ trans('email.register-code') }}:

{{ url('/activate/'.$code) }}

{{ trans('email.register-footer') }}.
@endsection
