<div class="panel__action" x-data="{ open: false }">
    <button class="form__button form__button--text" x-on:click.stop="open = true; $refs.dialog.showModal();">
        {{ __('request.vote') }}
    </button>
    <dialog class="dialog" x-ref="dialog" x-show="open" x-cloak>
        <h3 class="dialog__heading">
            {{ __('request.vote-that') }}
        </h3>
        <form
            class="dialog__form"
            method="POST"
            action="{{ route("add_votes", ['id' => $torrentRequest->id]) }}"
            x-on:click.outside="open = false; $refs.dialog.close();"
        >
            @csrf
            <input id="type" type="hidden" name="request_id" value="{{ $torrentRequest->id }}">
            <p class="form__group">
                <input
                    id="bonus_value"
                    class="form__text"
                    inputmode="numeric"
                    name="bonus_value"
                    pattern="[0-9]*?[1-9][0-9]{2,}"
                    placeholder=""
                    type="text"
                >
                <label for="bonus_value" class="form__label form__label--floating">
                    {{ __('request.enter-bp') }}
                </label>
            </p>
            <p class="form__group">
                <input type="hidden" name="anon" value="0">
                <input
                    type="checkbox"
                    class="form__checkbox"
                    id="anon"
                    name="anon"
                    value="1"
                >
                <label class="form__label" for="anon">
                    {{ __('common.anonymous') }}?
                </label>
            </p>
            <p class="form__group">
                <button
                    class="form__button form__button--filled"
                    @if ($user->seedbonus < 100)
                        disabled
                        title="{{ __('request.dont-have-bps') }}"
                    @endif
                >
                    {{ __('request.vote') }}
                </button>
                <button x-on:click.prevent="open = false; $refs.dialog.close();" class="form__button form__button--outlined">
                    {{ __('common.cancel') }}
                </button>
            </p>
        </form>
    </dialog>
</div>
