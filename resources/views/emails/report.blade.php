@component('mail::message')
    # @lang('email.report-header') {{ config('other.title') }} !
    **@lang('email.report-email'):** {{ $report->email }}
    
    **@lang('email.report-link'):** {{ $report->url }}
    
    **@lang('email.report-link-hash'):** {{ $report->link->hash }}
    
    **@lang('email.report-comment'):** {{ $report->comment }}
@endcomponent
