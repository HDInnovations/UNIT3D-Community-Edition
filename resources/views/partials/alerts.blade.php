@if ((float) config('other.multi_down') <= 0 || config('other.invite-only') == false || (float) config('other.multi_up') > 1)
    <div class="alert alert-info" id="alert1">
        <div class="text-center">
            <span>
            @if (config('other.multi_down') <= 0) @lang('common.freeleech_activated')! @endif
            @if (config('other.invite-only') == false) @lang('common.openreg_activated')! @endif
            @if (config('other.multi_up') > 1) @lang('common.doubleup_activated')! @endif
            </span>
            <strong>
                <div id="promotions"></div>
            </strong></div>
    </div>
@endif
