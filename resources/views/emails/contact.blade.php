@component('mail::message')
# New contact mail from {{ $input['email'] }}

**Name:** {{ $input['contact-name'] }}

**Message:** {{ $input['message'] }}

@endcomponent
