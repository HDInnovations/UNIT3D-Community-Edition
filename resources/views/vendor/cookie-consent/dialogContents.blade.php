<section
    class="alert alert-danger alert-dismissable js-cookie-consent cookie-consent"
    aria-label="{{ __('cookie-consent::texts.agree') }}"
    style="margin-bottom: 0; border-radius: 0;"
>
    <div class="text-center">
    <span class="cookie-consent__message">
        {!! __('cookie-consent::texts.message') !!}
    </span>

        <button class="btn btn-sm btn-primary js-cookie-consent-agree cookie-consent__agree">
            {{ __('cookie-consent::texts.agree') }}
        </button>
    </div>
</section>
