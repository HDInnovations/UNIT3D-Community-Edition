@extends('emails.template')

@section('content')
<p>{{ trans('email.report-header') }} {{ env('SITE_NAME') }}.</p>
<p><strong>{{ trans('email.report-email') }}</strong>: {{ $report->email }}</p>
<p><strong>{{ trans('email.report-link') }}</strong>: {{ url($report->url) }}</p>
<p><strong>{{ trans('email.report-link-hash') }}</strong>: {{ url($report->link->hash) }}</p>
<p><strong>{{ trans('email.report-comment') }}</strong>: {{ $report->comment }}</p>
@endsection
