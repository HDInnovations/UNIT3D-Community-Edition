@component('mail::message')
# {{ __('email.report-header') }} {{ config('other.title') }} !
**{{ __('email.report-email') }}:** {{ $report->email }}
**{{ __('email.report-link') }}:** {{ $report->url }}
**{{ __('email.report-link-hash') }}:** {{ $report->link->hash }}
**{{ __('email.report-comment') }}:** {{ $report->comment }}
@endcomponent
