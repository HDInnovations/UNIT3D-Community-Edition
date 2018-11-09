@component('mail::message')
# {{ trans('email.unban-header') }}!

**Reason:** {{ $ban->unban_reason }}

*{{ trans('email.unban-footer') }}*
@endcomponent
