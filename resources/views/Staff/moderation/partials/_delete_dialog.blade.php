<li class="data-table__action" x-data="{ open: false }">
    <button class="form__button form__button--filled" x-on:click.stop="open = true; $refs.dialog.showModal();">
        <i class="{{ config('other.font-awesome') }} fa-thumbs-down"></i>
        {{ __('common.delete') }}
    </button>
    <dialog class="dialog" x-ref="dialog" x-show="open" x-cloak>
        <h4 class="dialog__heading">
            {{ __('common.delete') }} {{ __('torrent.torrent') }}: {{ $torrent->name }}
        </h4>
        <form
            class="dialog__form"
            method="POST"
            action="{{ route('delete') }}"
            x-on:click.outside="open = false; $refs.dialog.close();"
        >
            @csrf
            <p class="form__group">
                <input id="type" type="hidden" name="type" value="{{ __('torrent.torrent') }}">
                <input id="id" type="hidden" name="id" value="{{ $torrent->id }}">
            </p>
            <p class="form__group">
                <textarea class="form__textarea" name="message" id="message"></textarea>
                <label class="form__label form__label--floating" for="message">Deletion Reason</label>
            </p>
            <p class="form__group">
                <button class="form__button form__button--filled">
                    {{ __('common.delete') }}
                </button>
                <button x-on:click.prevent="open = false; $refs.dialog.close();" class="form__button form__button--outlined">
                    {{ __('common.cancel') }}
                </button>
            </p>
        </form>
    </dialog>
</li>
