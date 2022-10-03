<form
    method="POST"
    action="{{ route('unclaimRequest', ['id' => $torrentRequest->id]) }}"
>
    @csrf
    <div class="form__group form__group--short-horizontal">
        <button class="form__button form__button--filled form__button--centered">
            {{ __('request.unclaim') }}
        </button>
    </div>
</form>
