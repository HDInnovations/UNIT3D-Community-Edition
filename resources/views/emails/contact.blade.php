@component('mail::message')
    # {{ __('email.contact-header') }} {{ $input['email'] }}
    **{{ __('email.contact-name') }}:** {{ $input['contact-name'] }}
    **{{ __('email.contact-message') }}:** {{ $input['message'] }}
@endcomponent
