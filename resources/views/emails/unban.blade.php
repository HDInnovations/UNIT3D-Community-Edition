@component('mail::message')
    # @lang('email.unban-header')!
    
    **Reason:** {{ $ban->unban_reason }}
    
    *@lang('email.unban-footer')*
@endcomponent
