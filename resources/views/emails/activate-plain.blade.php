@extends('emails.template-plain')

@section('content')
{{ trans('email.register-header') }} {{ config('other.title') }}.
{{ trans('email.register-code') }}:

{{ url('/activate/'.$code) }}

{{ trans('email.register-footer') }}.
@endsection
