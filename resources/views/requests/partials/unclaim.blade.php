<li class="form__group form__group--short-horizontal">
    <form
        method="POST"
        action="{{ route('unclaimRequest', ['id' => $torrentRequest->id]) }}"
        style="display: contents"
    >
        @csrf
        <button class="form__button form__button--outlined form__button--centered">
            {{ __('request.unclaim') }}
        </button>
    </form>
</li>
