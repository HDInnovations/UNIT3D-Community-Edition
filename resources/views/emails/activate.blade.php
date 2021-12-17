@component('mail::message')
    # @lang('email.register-header') {{ config('other.title') }} !
    **@lang('email.register-code')**
    @component('mail::button', ['url' => route('activate', $code), 'color' => 'blue'])
        @lang('email.activate-account')
    @endcomponent
    <p>@lang('email.register-footer')</p>
    <p style="word-wrap: break-word; overflow-wrap: break-word; word-break: break-word;">{{ route('activate', $code) }}</p>
@endcomponent
