@component('mail::message')
    # {{ __('email.unban-header') }}!
    **Reason:** {{ $ban->unban_reason }}
    *{{ __('email.unban-footer') }}*
@endcomponent
