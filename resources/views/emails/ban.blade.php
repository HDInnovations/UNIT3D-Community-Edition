@component('mail::message')
    # {{ __('email.banned-header') }}!
    **Reason:** {{ $ban->ban_reason }}
    *{{ __('email.banned-footer') }}*
@endcomponent
