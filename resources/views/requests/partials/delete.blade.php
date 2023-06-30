<li class="form__group form__group--short-horizontal">
    <form
        action="{{ route("requests.destroy", ['torrentRequest' => $torrentRequest]) }}"
        method="POST"
        x-data
        style="display: contents"
    >
        @csrf
        @method('DELETE')
        <button
            x-on:click.prevent="Swal.fire({
                title: 'Are you sure?',
                text: 'Are you sure you want to delete this torrent request and lose the BON?',
                icon: 'warning',
                showConfirmButton: true,
                showCancelButton: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    $root.submit();
                }
            })"
            class="form__button form__button--outlined form__button--centered"
        >
            {{ __('common.delete') }}
        </button>
    </form>
</li>
