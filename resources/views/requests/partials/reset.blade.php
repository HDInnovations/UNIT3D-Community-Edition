<li class="form__group form__group--short-horizontal">
    <form
        method="POST"
        action="{{ route("resetRequest", ['id' => $torrentRequest->id]) }}"
        x-data
        style="display: contents"
    >
        @csrf
        <button
            x-on:click.prevent="Swal.fire({
                title: 'Are you sure?',
                text: 'Are you sure you want to reset this torrent request?',
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
            {{ __('request.reset') }}
        </button>
    </form>
</li>

