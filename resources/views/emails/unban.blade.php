@component('mail::message')
# You Have Been Unbanned!

**Reason:** {{ $ban->unban_reason }}

*Someone felt pitty for you*
@endcomponent
