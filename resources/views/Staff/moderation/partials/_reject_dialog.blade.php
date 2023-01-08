<li class="data-table__action" x-data="{ open: false }">
    <button class="form__button form__button--filled" x-on:click.stop="open = true; $refs.dialog.showModal();">
        <i class="{{ config('other.font-awesome') }} fa-thumbs-down"></i>
        {{ __('common.moderation-reject') }}
    </button>
    <dialog class="dialog" x-ref="dialog" x-show="open" x-cloak>
        <h3 class="dialog__heading">
            {{ __('common.moderation-reject') }} {{ __('torrent.torrent') }}: {{ $torrent->name }}
        </h3>
        <form
            class="dialog__form"
            method="POST"
            action="{{ route("staff.moderation.update", ['id' => $torrent->id]) }}"
            x-on:click.outside="open = false; $refs.dialog.close();"
        >
            @csrf
            <input id="type" type="hidden" name="type" value="{{ __('torrent.torrent') }}">
            <input id="id" type="hidden" name="id" value="{{ $torrent->id }}">
            <input type="hidden" name="old_status" value="{{ $torrent->status }}">
            <input type="hidden" name="status" value="2">
            <p class="form__group">
                <textarea id="message" class="form__textarea" name="message">{{ old('message') }}</textarea>
                <label for="message" class="form__label form__label__floating">Rejection Message</label>
            </p>
            <p class="form__group">
                <button class="form__button form__button--filled">
                    {{ __('common.moderation-reject') }}
                </button>
                <button x-on:click.prevent="open = false; $refs.dialog.close();" class="form__button form__button--outlined">
                    {{ __('common.cancel') }}
                </button>
            </p>
        </form>
    </dialog>
</li>
