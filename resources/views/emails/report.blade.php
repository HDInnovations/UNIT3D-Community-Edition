@component('mail::message')
    # {{ trans('email.report-header') }} {{ config('other.title') }} !
    **{{ trans('email.report-email') }}:** {{ $report->email }}

    **{{ trans('email.report-link') }}:** {{ $report->url }}

    **{{ trans('email.report-link-hash') }}:** {{ $report->link->hash }}

    **{{ trans('email.report-comment') }}:** {{ $report->comment }}
@endcomponent