@component('mail::message')
# Invitation to {{ Config::get('other.title') }} !

**Message:** You have been invited to {{ Config::get('other.title') }}

@component('mail::button', ['url' => route('register', $invite->code), 'color' => 'blue'])
Sign Up Now
@endcomponent

@endcomponent
