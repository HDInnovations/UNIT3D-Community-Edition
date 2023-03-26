<li class="form__group form__group--short-horizontal">
    <a
        class="form__button form__button--outlined form__button--centered"
        href="{{ route('edit_request', ['id' => $torrentRequest->id]) }}"
    >
        {{ __('common.edit') }}
    </a>
</li>
