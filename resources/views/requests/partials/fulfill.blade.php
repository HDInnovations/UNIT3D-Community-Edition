<div class="form__group form__group--short-horizontal" x-data="{ open: false }">
    <button class="form__button form__button--filled form__button--centered" x-on:click.stop="open = true; $refs.dialog.showModal();">
        {{ __('request.fulfill') }}
    </button>
    <dialog class="dialog" x-ref="dialog" x-show="open" x-cloak>
        <h3 class="dialog__heading">
            {{ __('request.fill-request') }}
        </h3>
        <form
            class="dialog__form"
            method="POST"
            action="{{ route("fill_request", ['id' => $torrentRequest->id]) }}"
            x-on:click.outside="open = false; $refs.dialog.close();"
        >
            @csrf
            <input id="type" type="hidden" name="request_id" value="{{ $torrentRequest->id }}">
            <p class="form__group">
                <input
                    id="torrent_id"
                    class="form__text"
                    name="torrent_id"
                    placeholder=""
                    type="text"
                >
                <label for="torrent_id" class="form__label form__label--floating">
                    {{ __('request.enter-hash') }}
                </label>
            </p>
            <p class="form__group">
                <input type="hidden" name="filled_anon" value="0">
                <input
                    type="checkbox"
                    class="form__checkbox"
                    id="filled_anon"
                    name="filled_anon"
                    value="1"
                >
                <label class="form__label" for="filled_anon">
                    {{ __('common.anonymous') }}?
                </label>
            </p>
            <p class="form__group">
                <button class="form__button form__button--filled">
                    {{ __('request.fulfill') }}
                </button>
                <button x-on:click.prevent="open = false; $refs.dialog.close();" class="form__button form__button--outlined">
                    {{ __('common.cancel') }}
                </button>
            </p>
        </form>
    </dialog>
</div>
